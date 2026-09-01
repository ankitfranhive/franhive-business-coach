<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * OrderModel
 *
 * Stores deposit checkout info and manages balance collection flows.
 *
 * Expected table (simplified):
 *  - id (PK, AI)
 *  - landing_page_id INT
 *  - session_id VARCHAR(64)
 *  - customer_id VARCHAR(64)
 *  - payment_method_id VARCHAR(64) NULL
 *  - currency VARCHAR(8) DEFAULT 'AUD'
 *  - total_cents INT
 *  - deposit_cents INT
 *  - remaining_cents INT
 *  - plan_mode ENUM('auto','manual') DEFAULT 'auto'
 *  - balance_due_date DATE NULL
 *  - status ENUM('deposit_paid','charging','balance_paid','invoice_sent','failed','cancelled') DEFAULT 'deposit_paid'
 *  - promotion_code_id VARCHAR(64) NULL
 *  - created_at DATETIME DEFAULT CURRENT_TIMESTAMP
 *  - updated_at DATETIME NULL
 */
class OrderModel extends CI_Model
{
    protected $table = 'orders';

    public function __construct()
    {
        parent::__construct();
    }

    /** Insert a new order row. Returns insert id. */
    public function create(array $data)
    {
        $row = $this->filter_fields($data, [
            'landing_page_id','session_id','customer_id','payment_method_id',
            'currency','total_cents','deposit_cents','remaining_cents',
            'plan_mode','balance_due_date','status','promotion_code_id',
            'created_at','updated_at'
        ]);

        if (empty($row['created_at'])) $row['created_at'] = gmdate('Y-m-d H:i:s');

        $this->db->insert($this->table, $row);
        return (int)$this->db->insert_id();
    }

    /** Update an order by primary key id. Returns affected rows. */
    public function update($id, array $data)
    {
        if (!$id) return 0;
        $row = $this->filter_fields($data, [
            'customer_id','payment_method_id','currency',
            'total_cents','deposit_cents','remaining_cents',
            'plan_mode','balance_due_date','status','promotion_code_id','updated_at'
        ]);
        $row['updated_at'] = gmdate('Y-m-d H:i:s');
        $this->db->where('id', (int)$id)->update($this->table, $row);
        return $this->db->affected_rows();
    }

    /** Find one row by session_id (array or null). */
    public function find_by_session($session_id)
    {
        if (!$session_id) return null;
        return $this->db->get_where($this->table, ['session_id' => $session_id])->row_array();
    }

    /** Update by session_id (returns affected rows). */
    public function update_by_session($session_id, array $data)
    {
        if (!$session_id) return 0;
        $row = $this->filter_fields($data, [
            'customer_id','payment_method_id','currency',
            'total_cents','deposit_cents','remaining_cents',
            'plan_mode','balance_due_date','status','promotion_code_id','updated_at'
        ]);
        $row['updated_at'] = gmdate('Y-m-d H:i:s');
        $this->db->where('session_id', $session_id)->update($this->table, $row);
        return $this->db->affected_rows();
    }

    /**
     * Return all AUTO-plan orders that:
     *  - are still in 'deposit_paid'
     *  - have a positive remaining_cents
     *  - balance_due_date <= $asOfDate (YYYY-MM-DD)
     */
    public function get_due_auto_balances($asOfDate)
    {
        return $this->db->from($this->table)
            ->where('plan_mode', 'auto')
            ->where('status', 'deposit_paid')
            ->where('remaining_cents >', 0, false)
            ->where('balance_due_date <=', $asOfDate)
            ->order_by('balance_due_date', 'ASC')
            ->get()->result_array();
    }

    /**
     * Try to "claim" a row for charging (avoid double processing).
     * Atomically flips status from 'deposit_paid' -> 'charging'.
     * Returns true if we claimed it, false otherwise.
     */
    public function claim_for_charge($id)
    {
        if (!$id) return false;
        $this->db->trans_start();
        $this->db->set('status', 'charging')
                 ->set('updated_at', gmdate('Y-m-d H:i:s'))
                 ->where('id', (int)$id)
                 ->where('status', 'deposit_paid')
                 ->update($this->table);
        $updated = $this->db->affected_rows() === 1;
        $this->db->trans_complete();
        return $updated;
    }

    /** Mark the balance paid (sets remaining_cents=0, status='balance_paid'). */
    public function mark_balance_paid($id)
    {
        if (!$id) return 0;
        $this->db->where('id', (int)$id)->update($this->table, [
            'remaining_cents' => 0,
            'status'          => 'balance_paid',
            'updated_at'      => gmdate('Y-m-d H:i:s'),
        ]);
        return $this->db->affected_rows();
    }

    /** Mark that an invoice has been sent for the balance. */
    public function mark_invoice_sent($id)
    {
        if (!$id) return 0;
        $this->db->where('id', (int)$id)->update($this->table, [
            'status'     => 'invoice_sent',
            'updated_at' => gmdate('Y-m-d H:i:s'),
        ]);
        return $this->db->affected_rows();
    }

    /** Mark a failure (charging issue etc.). */
    public function mark_failed($id)
    {
        if (!$id) return 0;
        $this->db->where('id', (int)$id)->update($this->table, [
            'status'     => 'failed',
            'updated_at' => gmdate('Y-m-d H:i:s'),
        ]);
        return $this->db->affected_rows();
    }

    /** Generic status setter. */
    public function set_status($id, $status)
    {
        if (!$id || !$status) return 0;
        $this->db->where('id', (int)$id)->update($this->table, [
            'status'     => $status,
            'updated_at' => gmdate('Y-m-d H:i:s'),
        ]);
        return $this->db->affected_rows();
    }

    /** Reduce remaining by a given amount (e.g., partial payment). */
    public function decrement_remaining($id, $amount_cents)
    {
        if (!$id || $amount_cents <= 0) return 0;
        // remaining_cents = GREATEST(remaining_cents - amount, 0)
        $this->db->set('remaining_cents', "GREATEST(remaining_cents - ".(int)$amount_cents.", 0)", false)
                 ->set('updated_at', gmdate('Y-m-d H:i:s'))
                 ->where('id', (int)$id)
                 ->update($this->table);
        return $this->db->affected_rows();
    }

    /** Convenience: list recent orders (for an admin page). */
    public function list_recent($limit = 50, $offset = 0)
    {
        return $this->db->from($this->table)
            ->order_by('created_at', 'DESC')
            ->limit((int)$limit, (int)$offset)
            ->get()->result_array();
    }

    /* -------------------- helpers -------------------- */

    private function filter_fields(array $data, array $allowed)
    {
        $out = [];
        foreach ($allowed as $k) {
            if (array_key_exists($k, $data)) $out[$k] = $data[$k];
        }
        return $out;
    }
}
