<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cloudtalk_webhook extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Call_logs_model');
        $this->load->helper('url');
    }

    // POST /webhooks/cloudtalk
    public function receive()
    {
        // --- 0) Secret check
        // application/controllers/Cloudtalk_webhook.php (inside receive())
        $expected = (defined('CT_SECRET') ? CT_SECRET : null)
        ?: getenv('CT_SECRET')
        ?: $this->config->item('ct_secret'); // fallback if you also put it in config

        $got = $this->input->get_request_header('X-CT-Secret', true);

        // (Optional) rotation: accept either CT_SECRET or CT_SECRET_2
        if (defined('CT_SECRET_2') && CT_SECRET_2) {
        $ok = ($expected && hash_equals($expected, $got)) || hash_equals(CT_SECRET_2, $got);
        } else {
        $ok = ($expected && $got && hash_equals($expected, $got));
        }

        if (!$ok) {
        return $this->output->set_status_header(401)->set_content_type('application/json')
                    ->set_output(json_encode(['ok'=>false,'error'=>'invalid_secret']));
        }


        // --- 1) Parse body (JSON or form)
        $raw = file_get_contents('php://input');
        $ct  = $this->input->get_request_header('Content-Type', true);

        $in = [];
        if ($raw !== '' && stripos((string)$ct, 'application/json') !== false) {
            $in = json_decode($raw, true);
            if (!is_array($in)) $in = [];
        }
        if (!$in) {
            // try POST vars
            $post = $this->input->post(NULL, true);
            if (!empty($post)) {
                $in = $post;
            } elseif ($raw !== '') {
                // some senders post urlencoded but wrong content-type
                parse_str($raw, $in);
            }
        }

        if (!is_array($in) || !$in) {
            log_message('error', 'Cloudtalk_webhook: empty/invalid payload. CT:'.$ct.' RAW:'.$raw);
            return $this->json(400, ['ok'=>false, 'error'=>'invalid_payload']);
        }

        // --- 2) Normalize keys (handle dotted keys like agent.name → ['agent'=>['name'=>...]])
        $in = $this->undot($in);

        // --- 3) Map to our canonical event shape expected by Call_logs_model
        $e = $this->map_event($in);

        // minimal sanity
        if (empty($e['call_id'])) {
            log_message('error', 'Cloudtalk_webhook: missing call_id after mapping. IN='.json_encode($in));
            return $this->json(400, ['ok'=>false, 'error'=>'missing_call_id']);
        }

        // --- 4) Upsert into DB (idempotent on call_id)
        $ok = $this->Call_logs_model->upsert_from_event($e);

        if (!$ok) {
            log_message('error', 'Cloudtalk_webhook: DB upsert failed for call_id='.$e['call_id']);
        }

        return $this->json(200, ['ok'=> (bool)$ok ]);
    }

    /** Convert dotted keys to nested arrays */
    private function undot(array $arr): array {
        $out = [];
        foreach ($arr as $k => $v) {
            if (strpos($k, '.') === false) {
                // keep as-is
                $out[$k] = $v;
                continue;
            }
            $parts = explode('.', $k);
            $ref =& $out;
            foreach ($parts as $i => $p) {
                if ($i === count($parts) - 1) {
                    $ref[$p] = $v;
                } else {
                    if (!isset($ref[$p]) || !is_array($ref[$p])) $ref[$p] = [];
                    $ref =& $ref[$p];
                }
            }
        }
        // merge originals that had no dots (prefer nested over dotted dupes)
        foreach ($arr as $k => $v) {
            if (strpos($k, '.') === false && !array_key_exists($k, $out)) {
                $out[$k] = $v;
            }
        }
        return $out;
    }

    /** Map many possible CloudTalk field names into one canonical array our model understands */
    private function map_event(array $in): array {
        // Call id (several possibilities)
        $call_id =
            $in['call_id'] ??
            ($in['call']['id'] ?? null) ??
            ($in['call_uuid'] ?? null) ??
            ($in['id'] ?? null);

        // Direction/status
        $direction = $in['direction'] ?? ($in['call']['direction'] ?? null);
        $status    = $in['status']    ?? ($in['call']['status'] ?? null);

        // Times
        $started_at = $in['started_at'] ?? ($in['call']['started_at'] ?? null);
        $ended_at   = $in['ended_at']   ?? ($in['call']['ended_at'] ?? null);

        // Duration (talking time / duration / duration_seconds)
        $duration_seconds = null;
        foreach (['duration_seconds','talk_time','talking_time','duration'] as $cand) {
            if (isset($in[$cand]) && $in[$cand] !== '') { $duration_seconds = (int)$in[$cand]; break; }
            if (isset($in['call'][$cand]) && $in['call'][$cand] !== '') { $duration_seconds = (int)$in['call'][$cand]; break; }
        }

        // Agent
        $agent_name  = $in['agent']['name']  ?? ($in['user']['name']  ?? null);
        $agent_email = $in['agent']['email'] ?? ($in['user']['email'] ?? null);

        // Contact (customer)
        $customer_phone =
            $in['contact']['phone'] ??
            ($in['customer']['phone'] ?? null) ??
            ($in['external_number']   ?? null);

        $customer_email =
            $in['contact']['email'] ??
            ($in['customer']['email'] ?? null);

        // Recording
        $recording_url =
            $in['recording_url'] ??
            ($in['recording']['url'] ?? null);

        // Tags may be array or comma string
        $tags = $in['tags'] ?? ($in['call']['tags'] ?? null);
        if (is_string($tags)) {
            // If it’s like "tag1, tag2"
            $tags = trim($tags);
            if ($tags !== '') { $tags = array_map('trim', explode(',', $tags)); }
            else $tags = [];
        } elseif (!is_array($tags)) {
            $tags = [];
        }

        // Build canonical event
        return [
            'call_id'          => $call_id,
            'direction'        => $direction,
            'status'           => $status,
            'started_at'       => $started_at,
            'ended_at'         => $ended_at,
            'duration_seconds' => $duration_seconds,
            'agent'            => ['name'=>$agent_name, 'email'=>$agent_email],
            'contact'          => ['phone'=>$customer_phone, 'email'=>$customer_email],
            'recording_url'    => $recording_url,
            'tags'             => $tags,
        ];
    }

    private function json($code, $arr) {
        return $this->output->set_status_header($code)
            ->set_content_type('application/json')
            ->set_output(json_encode($arr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
    }
}
