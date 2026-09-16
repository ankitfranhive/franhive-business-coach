<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_dashboard_data()
    {
        $lead_status = $this->lead_status_counts();
        $task_status = $this->task_status_counts();
        $campaigns = $this->campaign_status_counts();
        $pa_status = $this->payment_agreement_status_counts();

        $leads_total = array_sum($lead_status);
        $leads_30d = $this->count_sql("
            SELECT COUNT(*) AS cnt FROM ENTITY
            WHERE RECORD_STATUS = 0 AND IS_LEAD = 'Y'
              AND CREATED_ON >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");
        $clients = $this->count_sql("
            SELECT COUNT(*) AS cnt FROM ENTITY
            WHERE RECORD_STATUS = 0 AND IS_LEAD = 'N'
        ");
        $tasks_open = (int)($task_status['Not started'] ?? 0) + (int)($task_status['In progress'] ?? 0);
        $ef_total = $this->table_count('ef_submissions');
        $ef_30d = $this->count_sql("SELECT COUNT(*) AS cnt FROM ef_submissions WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)", 'ef_submissions');
        $ef_awaiting = $this->count_sql("
            SELECT COUNT(*) AS cnt FROM ef_requests
            WHERE status = 'sent'
              AND (expires_at IS NULL OR expires_at > NOW())
        ", 'ef_requests');
        $ef_expired = $this->count_sql("SELECT COUNT(*) AS cnt FROM ef_requests WHERE status = 'expired' OR (status = 'sent' AND expires_at IS NOT NULL AND expires_at <= NOW())", 'ef_requests');
        $pa_submitted = (int)($pa_status['submitted'] ?? 0);
        $pa_30d = $this->count_sql("SELECT COUNT(*) AS cnt FROM payment_agreement_requests WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)", 'payment_agreement_requests');
        $pa_awaiting = (int)($pa_status['sent'] ?? 0) + (int)($pa_status['opened'] ?? 0);
        $pa_expired = (int)($pa_status['expired'] ?? 0);
        $campaigns_live = (int)($campaigns['scheduled'] ?? 0) + (int)($campaigns['in_progress'] ?? 0);
        $tests_done = $this->count_sql("SELECT COUNT(*) AS cnt FROM TEST_USER_RESPONSE WHERE IFNULL(IS_SUBMITTED, 0) = 1", 'TEST_USER_RESPONSE');
        $tests_30d = $this->count_sql("
            SELECT COUNT(*) AS cnt FROM TEST_USER_RESPONSE
            WHERE IFNULL(IS_SUBMITTED, 0) = 1
              AND COALESCE(SUBMITTED_AT, created_at) >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ", 'TEST_USER_RESPONSE');

        return [
            'kpis' => [
                [
                    'label' => 'Leads',
                    'value' => $leads_total,
                    'hint' => $leads_30d . ' new in last 30 days',
                    'icon' => 'fa-user-plus',
                    'href' => base_url('leads'),
                ],
                [
                    'label' => 'Clients',
                    'value' => $clients,
                    'hint' => 'Active client records',
                    'icon' => 'fa-briefcase',
                    'href' => base_url('clients'),
                ],
                [
                    'label' => 'eForm submissions',
                    'value' => $ef_30d,
                    'hint' => $ef_total . ' all time · ' . $ef_awaiting . ' awaiting',
                    'icon' => 'fa-file-signature',
                    'href' => base_url('admin_eforms/submissions'),
                ],
                [
                    'label' => 'Agreements signed',
                    'value' => $pa_30d,
                    'hint' => $pa_submitted . ' submitted · ' . $pa_awaiting . ' waiting',
                    'icon' => 'fa-file-invoice-dollar',
                    'href' => base_url('payment-agreement/requests'),
                ],
                [
                    'label' => 'Live campaigns',
                    'value' => $campaigns_live,
                    'hint' => ((int)($campaigns['sent'] ?? 0)) . ' sent · ' . ((int)($campaigns['draft'] ?? 0)) . ' drafts',
                    'icon' => 'fa-bullhorn',
                    'href' => base_url('campaign-dashboard'),
                ],
                [
                    'label' => 'Tests submitted',
                    'value' => $tests_done,
                    'hint' => $tests_30d . ' in last 30 days',
                    'icon' => 'fa-clipboard-check',
                    'href' => base_url('test-reports'),
                ],
            ],
            'lead_pipeline' => [
                'labels' => array_keys($lead_status),
                'values' => array_values($lead_status),
            ],
            'task_status' => [
                'labels' => array_keys($task_status),
                'values' => array_values($task_status),
            ],
            'pa_status' => [
                'labels' => array_map('ucfirst', array_keys($pa_status)),
                'values' => array_values($pa_status),
            ],
            'campaign_status' => $campaigns,
            'recent_campaigns' => $this->recent_campaigns(6),
            'recent_eforms' => $this->recent_eform_submissions(8),
            'recent_pa' => $this->recent_payment_agreements(8),
            'recent_leads' => $this->recent_leads(8),
            'recent_tasks' => $this->recent_tasks(8),
            'recent_tests' => $this->recent_tests(8),
            'attention' => $this->attention_items($ef_awaiting, $pa_awaiting, $pa_expired, (int)($lead_status['Red flag'] ?? 0), $campaigns_live, $tasks_open, $ef_expired),
        ];
    }

    private function attention_items($ef_awaiting, $pa_awaiting, $pa_expired, $red_flags, $campaigns_live, $tasks_open, $ef_expired = 0)
    {
        $items = [];
        if ($pa_awaiting > 0) {
            $items[] = [
                'count' => (int)$pa_awaiting,
                'title' => 'Waiting to sign',
                'group' => 'Agreements',
                'icon' => 'fa-file-signature',
                'tone' => 'warn',
                'href' => base_url('payment-agreement/requests'),
            ];
        }
        if ($pa_expired > 0) {
            $items[] = [
                'count' => (int)$pa_expired,
                'title' => 'Links expired',
                'group' => 'Agreements',
                'icon' => 'fa-link-slash',
                'tone' => 'danger',
                'href' => base_url('payment-agreement/requests'),
            ];
        }
        if ($ef_awaiting > 0) {
            $items[] = [
                'count' => (int)$ef_awaiting,
                'title' => 'Awaiting reply',
                'group' => 'eForms',
                'icon' => 'fa-envelope-open-text',
                'tone' => 'info',
                'href' => base_url('admin_eforms/requests'),
            ];
        }
        if ($ef_expired > 0) {
            $items[] = [
                'count' => (int)$ef_expired,
                'title' => 'Links expired',
                'group' => 'eForms',
                'icon' => 'fa-clock',
                'tone' => 'danger',
                'href' => base_url('admin_eforms/requests'),
            ];
        }
        if ($red_flags > 0) {
            $items[] = [
                'count' => (int)$red_flags,
                'title' => 'Need a follow-up',
                'group' => 'Red-flag leads',
                'icon' => 'fa-flag',
                'tone' => 'danger',
                'href' => base_url('leads'),
            ];
        }
        if ($campaigns_live > 0) {
            $items[] = [
                'count' => (int)$campaigns_live,
                'title' => 'Triggered / sending',
                'group' => 'Campaigns',
                'icon' => 'fa-bullhorn',
                'tone' => 'info',
                'href' => base_url('campaign-dashboard'),
            ];
        }
        if ($tasks_open > 0) {
            $items[] = [
                'count' => (int)$tasks_open,
                'title' => 'Still open',
                'group' => 'Tasks',
                'icon' => 'fa-list-check',
                'tone' => 'muted',
                'href' => base_url('tasks'),
            ];
        }
        return $items;
    }

    private function lead_status_counts()
    {
        $map = [
            '1' => 'New',
            '2' => 'Closed',
            '-1' => 'Closed',
            '3' => 'Red flag',
        ];
        $out = ['New' => 0, 'Closed' => 0, 'Red flag' => 0, 'Other' => 0];
        $rows = $this->db->query("
            SELECT LEAD_STATUS, COUNT(*) AS cnt
            FROM ENTITY
            WHERE RECORD_STATUS = 0 AND IS_LEAD = 'Y'
            GROUP BY LEAD_STATUS
        ")->result_array();
        foreach ($rows as $row) {
            $key = (string)($row['LEAD_STATUS'] ?? '');
            $label = $map[$key] ?? 'Other';
            $out[$label] += (int)$row['cnt'];
        }
        return $out;
    }

    private function task_status_counts()
    {
        $map = [
            '1' => 'Not started',
            '2' => 'In progress',
            '3' => 'Completed',
            '-1' => 'Closed',
        ];
        $out = ['Not started' => 0, 'In progress' => 0, 'Completed' => 0, 'Closed' => 0];
        if (!$this->db->table_exists('FD_TASKS')) {
            return $out;
        }
        $rows = $this->db->query("
            SELECT STATUS, COUNT(*) AS cnt
            FROM FD_TASKS
            WHERE RECORD_STATUS = 0
            GROUP BY STATUS
        ")->result_array();
        foreach ($rows as $row) {
            $key = (string)($row['STATUS'] ?? '');
            $label = $map[$key] ?? 'Other';
            if (!isset($out[$label])) {
                $out[$label] = 0;
            }
            $out[$label] += (int)$row['cnt'];
        }
        return $out;
    }

    private function campaign_status_counts()
    {
        $out = [
            'draft' => 0,
            'setup' => 0,
            'scheduled' => 0,
            'in_progress' => 0,
            'sent' => 0,
            'paused' => 0,
        ];
        if (!$this->db->table_exists('CAMPAIGN')) {
            return $out;
        }
        $rows = $this->db->query("
            SELECT WORKFLOW_STATUS, COUNT(*) AS cnt
            FROM CAMPAIGN
            WHERE RECORD_STATUS = 0
            GROUP BY WORKFLOW_STATUS
        ")->result_array();
        foreach ($rows as $row) {
            $key = strtolower(trim((string)($row['WORKFLOW_STATUS'] ?? '')));
            if ($key === '') {
                $key = 'draft';
            }
            if (!isset($out[$key])) {
                $out[$key] = 0;
            }
            $out[$key] += (int)$row['cnt'];
        }
        return $out;
    }

    private function payment_agreement_status_counts()
    {
        $out = ['sent' => 0, 'opened' => 0, 'submitted' => 0, 'expired' => 0];
        if (!$this->db->table_exists('payment_agreement_requests')) {
            return $out;
        }
        $rows = $this->db->query("
            SELECT status, COUNT(*) AS cnt
            FROM payment_agreement_requests
            GROUP BY status
        ")->result_array();
        foreach ($rows as $row) {
            $key = strtolower((string)($row['status'] ?? ''));
            if (!isset($out[$key])) {
                $out[$key] = 0;
            }
            $out[$key] += (int)$row['cnt'];
        }
        return $out;
    }

    private function recent_leads($limit = 8)
    {
        return $this->db->query("
            SELECT L.ENTITY_ID, L.NAME, L.EMAIL, L.LEAD_STATUS, L.CREATED_ON, L.CITY,
                   U.NAME AS OWNER_NAME, CD.OPTION_VALUE AS LEAD_SOURCE
            FROM ENTITY L
            LEFT JOIN USERS U ON L.LEAD_OWNER = U.USER_ID
            LEFT JOIN COMMON_DROPDOWNS CD ON L.LEAD_SOURCE = CD.OPTION_ID AND CD.DROPDOWN_TYPE = 'leadsource'
            WHERE L.RECORD_STATUS = 0 AND L.IS_LEAD = 'Y'
            ORDER BY L.CREATED_ON DESC, L.ENTITY_ID DESC
            LIMIT " . (int)$limit
        )->result_array();
    }

    private function recent_tasks($limit = 8)
    {
        if (!$this->db->table_exists('FD_TASKS')) {
            return [];
        }
        return $this->db->query("
            SELECT LT.TASK_ID, LT.SUBJECT, LT.STATUS, LT.CREATED_ON, LT.START_DATE, U.NAME AS ASSIGNED_TO_USER
            FROM FD_TASKS LT
            LEFT JOIN USERS U ON LT.ASSIGN_TO = U.USER_ID
            WHERE LT.RECORD_STATUS = 0
            ORDER BY LT.CREATED_ON DESC
            LIMIT " . (int)$limit
        )->result_array();
    }

    private function recent_eform_submissions($limit = 8)
    {
        if (!$this->db->table_exists('ef_submissions')) {
            return [];
        }
        $sql = "
            SELECT s.id, s.created_at, s.static_form_slug, r.client_name, r.client_email, t.title AS template_title
            FROM ef_submissions s
            LEFT JOIN ef_requests r ON r.id = s.request_id
            LEFT JOIN ef_templates t ON t.id = s.template_id
            ORDER BY s.created_at DESC, s.id DESC
            LIMIT " . (int)$limit;
        return $this->db->query($sql)->result_array();
    }

    private function recent_payment_agreements($limit = 8)
    {
        if (!$this->db->table_exists('payment_agreement_requests')) {
            return [];
        }
        return $this->db->query("
            SELECT id, client_name, client_email, status, sent_at, submitted_at, agreement_id, total_inc_gst
            FROM payment_agreement_requests
            ORDER BY COALESCE(submitted_at, sent_at) DESC, id DESC
            LIMIT " . (int)$limit
        )->result_array();
    }

    private function recent_campaigns($limit = 6)
    {
        if (!$this->db->table_exists('CAMPAIGN')) {
            return [];
        }
        return $this->db->query("
            SELECT CAMPAIGN_ID, TITLE, MODULE_NAME, WORKFLOW_STATUS, TOTAL_RECIPIENTS, SENT_COUNT, CREATED_ON
            FROM CAMPAIGN
            WHERE RECORD_STATUS = 0
            ORDER BY CAMPAIGN_ID DESC
            LIMIT " . (int)$limit
        )->result_array();
    }

    private function recent_tests($limit = 8)
    {
        if (!$this->db->table_exists('TEST_USER_RESPONSE')) {
            return [];
        }
        return $this->db->query("
            SELECT r.ID, r.MARKS_OBTAINED, COALESCE(r.TOTAL_MARKS, t.TOTAL_MARKS) AS TOTAL_MARKS,
                   r.SUBMITTED_AT, r.created_at, t.TEST_NAME, u.NAME
            FROM TEST_USER_RESPONSE r
            LEFT JOIN TEST_SERIES t ON t.TEST_ID = r.TEST_ID
            LEFT JOIN USERS u ON u.USER_ID = r.USER_ID
            WHERE IFNULL(r.IS_SUBMITTED, 0) = 1
            ORDER BY COALESCE(r.SUBMITTED_AT, r.created_at) DESC, r.ID DESC
            LIMIT " . (int)$limit
        )->result_array();
    }

    private function table_count($table)
    {
        if (!$this->db->table_exists($table)) {
            return 0;
        }
        return (int)$this->db->count_all($table);
    }

    private function count_sql($sql, $table = null)
    {
        if ($table && !$this->db->table_exists($table)) {
            return 0;
        }
        $row = $this->db->query($sql)->row_array();
        return (int)($row['cnt'] ?? 0);
    }
}
