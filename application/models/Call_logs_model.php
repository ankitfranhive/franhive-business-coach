<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Call_logs_model extends CI_Model
{
    private $table = 'call_logs';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function table_exists(): bool {
        return $this->db->table_exists($this->table);
    }

    private function apply_filters($q, $params)
    {
        if (!empty($params['from'])) {
            $q->where('started_at >=', date('Y-m-d 00:00:00', strtotime($params['from'])));
        }
        if (!empty($params['to'])) {
            $q->where('started_at <=', date('Y-m-d 23:59:59', strtotime($params['to'])));
        }
        if (!empty($params['direction']) && in_array($params['direction'], ['inbound','outbound'], true)) {
            $q->where('direction', $params['direction']);
        }
        if (!empty($params['status'])) {
            $q->like('status', $params['status']);
        }
        if (!empty($params['agent'])) {
            $q->group_start()
                ->like('agent_name', $params['agent'])
                ->or_like('agent_email', $params['agent'])
              ->group_end();
        }
        if (!empty($params['number'])) {
            $q->like('customer_number', preg_replace('/\s+/', '', $params['number']));
        }
        if (!empty($params['search'])) {
            $s = $params['search'];
            $q->group_start()
                ->like('customer_number', $s)
                ->or_like('agent_name', $s)
                ->or_like('agent_email', $s)
                ->or_like('status', $s)
                ->or_like('tags', $s)
              ->group_end();
        }
        return $q;
    }

    public function datatable($req)
    {
        if (!$this->table_exists()) {
            // No table yet: return empty set, avoid fatal
            log_message('error', 'Call_logs_model: table "'.$this->table.'" does not exist.');
            return [0, 0, []];
        }

        $cols = [
            0 => 'started_at',
            1 => 'direction',
            2 => 'status',
            3 => 'customer_number',
            4 => 'agent_email',
            5 => 'duration_seconds',
            6 => 'recording_url',
            7 => 'tags',
        ];

        // total
        $recordsTotal = 0;
        try {
            $recordsTotal = (int) $this->db->count_all($this->table);
        } catch (\Throwable $e) {
            log_message('error', 'Call_logs_model: count_all failed: '.$e->getMessage());
        }

        // filtered count
        $this->db->from($this->table);
        $this->apply_filters($this->db, [
            'from'     => $req['from'] ?? '',
            'to'       => $req['to'] ?? '',
            'direction'=> $req['direction'] ?? '',
            'status'   => $req['status'] ?? '',
            'agent'    => $req['agent'] ?? '',
            'number'   => $req['number'] ?? '',
            'search'   => $req['search']['value'] ?? '',
        ]);
        $recordsFiltered = 0;
        $query_ok = $this->db->count_all_results('', true); // resets QB
        if ($query_ok === 0 && $this->db->error()['code']) {
            log_message('error', 'Call_logs_model: filtered count error: '.json_encode($this->db->error()));
        }
        $recordsFiltered = (int) $query_ok;

        // data page
        $this->db->from($this->table);
        $this->apply_filters($this->db, [
            'from'     => $req['from'] ?? '',
            'to'       => $req['to'] ?? '',
            'direction'=> $req['direction'] ?? '',
            'status'   => $req['status'] ?? '',
            'agent'    => $req['agent'] ?? '',
            'number'   => $req['number'] ?? '',
            'search'   => $req['search']['value'] ?? '',
        ]);

        $orderColIdx = isset($req['order'][0]['column']) ? (int)$req['order'][0]['column'] : 0;
        $orderDir    = (isset($req['order'][0]['dir']) && $req['order'][0]['dir']==='asc') ? 'ASC' : 'DESC';
        $orderCol    = $cols[$orderColIdx] ?? 'started_at';
        $this->db->order_by($orderCol, $orderDir);

        $start = isset($req['start']) ? (int)$req['start'] : 0;
        $len   = isset($req['length']) ? (int)$req['length'] : 25;
        if ($len > 500) $len = 500;
        $this->db->limit($len, $start);

        $res = $this->db->get();
        if ($res === false) {
            log_message('error', 'Call_logs_model: data query error: '.json_encode($this->db->error()));
            return [$recordsTotal, 0, []];
        }

        return [$recordsTotal, $recordsFiltered, $res->result_array()];
    }
}
