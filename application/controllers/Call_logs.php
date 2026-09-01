<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Call_logs extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('Call_logs_model');
        $this->load->helper(['url','html']);
    }

    // Page with filters + table
    public function index() {
        // defaults: last 7 days
        $data['from'] = date('Y-m-d', strtotime('-7 days'));
        $data['to']   = date('Y-m-d');
        $this->load->view('call_logs/index', $data);
    }

    // DataTables server-side JSON
    public function data() {
        $req = $this->input->post(NULL, true); // all POST (sanitized)

        list($total, $filtered, $rows) = $this->Call_logs_model->datatable($req);

        // format for DataTables
        $data = [];
        foreach ($rows as $r) {
            $when = $r['started_at'] ? date('d M Y H:i', strtotime($r['started_at'])) : '';
            $agent = trim(($r['agent_name'] ?: '') . ($r['agent_email'] ? ' ('.$r['agent_email'].')' : ''));
            $dur = $r['duration_seconds'] ? intval($r['duration_seconds']).'s' : '';
            $rec = !empty($r['recording_url'])
                ? '<a href="'.html_escape($r['recording_url']).'" target="_blank" rel="noopener">Play</a>'
                : '';

            $data[] = [
                $when,
                html_escape($r['direction']),
                html_escape($r['status']),
                html_escape($r['customer_number']),
                html_escape($agent),
                $dur,
                $rec,
                html_escape($r['tags']),
            ];
        }

        $out = [
            'draw'            => intval($req['draw'] ?? 1),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($out));
    }
}
