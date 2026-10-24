<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->ensure_portal_calendar_schema();
    }

    public function ensure_portal_calendar_schema()
    {
        if ($this->db->table_exists('PORTAL_CALENDAR_ENTRIES')) {
            return true;
        }
        return (bool)$this->db->query("CREATE TABLE IF NOT EXISTS PORTAL_CALENDAR_ENTRIES (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            entry_type VARCHAR(32) NOT NULL DEFAULT 'EVENT',
            title VARCHAR(255) NOT NULL,
            body TEXT NULL,
            owner_user_id INT UNSIGNED NULL,
            start_date DATE NOT NULL,
            end_date DATE NULL,
            deadline_date DATE NULL,
            client_entity_id INT UNSIGNED NULL,
            remind_days_before INT UNSIGNED NOT NULL DEFAULT 1,
            last_reminder_sent DATE NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_by INT UNSIGNED NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY idx_dates (start_date, end_date),
            KEY idx_owner (owner_user_id),
            KEY idx_type (entry_type)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public function save_portal_entry(array $data, $id = null)
    {
        $this->ensure_portal_calendar_schema();
        $row = [
            'entry_type' => $data['entry_type'] ?? 'EVENT',
            'title' => $data['title'] ?? '',
            'body' => $data['body'] ?? null,
            'owner_user_id' => !empty($data['owner_user_id']) ? (int)$data['owner_user_id'] : null,
            'start_date' => $data['start_date'],
            'end_date' => !empty($data['end_date']) ? $data['end_date'] : null,
            'deadline_date' => !empty($data['deadline_date']) ? $data['deadline_date'] : null,
            'client_entity_id' => !empty($data['client_entity_id']) ? (int)$data['client_entity_id'] : null,
            'remind_days_before' => (int)($data['remind_days_before'] ?? 1),
            'is_active' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        if ($id) {
            $this->db->where('id', (int)$id)->update('PORTAL_CALENDAR_ENTRIES', $row);
            return (int)$id;
        }
        $row['created_by'] = !empty($data['created_by']) ? (int)$data['created_by'] : null;
        $row['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('PORTAL_CALENDAR_ENTRIES', $row);
        return (int)$this->db->insert_id();
    }

    public function delete_portal_entry($id)
    {
        $this->db->where('id', (int)$id)->update('PORTAL_CALENDAR_ENTRIES', [
            'is_active' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function get_portal_entries_for_year(int $year): array
    {
        if (!$this->db->table_exists('PORTAL_CALENDAR_ENTRIES')) {
            return [];
        }
        $start = $year . '-01-01';
        $end = $year . '-12-31';
        $rows = $this->db->query(
            "SELECT * FROM PORTAL_CALENDAR_ENTRIES
             WHERE is_active = 1
               AND (
                    (start_date >= ? AND start_date <= ?)
                 OR (deadline_date >= ? AND deadline_date <= ?)
               )",
            [$start, $end, $start, $end]
        )->result_array();

        $colors = [
            'NOTE' => '#9b59b6',
            'TASK' => '#e67e22',
            'EVENT' => '#3498db',
            'BIRTHDAY' => '#e91e63',
            'AGREEMENT_END' => '#c0392b',
        ];
        $out = [];
        foreach ($rows as $r) {
            $type = strtoupper($r['entry_type'] ?? 'EVENT');
            $color = $colors[$type] ?? '#7f8c8d';
            $startDate = $r['start_date'];
            $endDate = !empty($r['end_date']) ? $r['end_date'] : $startDate;
            $out[] = [
                'id' => 'p' . $r['id'],
                'title' => $r['title'],
                'start' => $startDate,
                'end' => $endDate,
                'allDay' => true,
                'color' => $color,
                'extendedProps' => [
                    'category' => 'PORTAL_' . $type,
                    'entry_type' => $type,
                    'portal_id' => (int)$r['id'],
                    'owner_user_id' => (int)($r['owner_user_id'] ?? 0),
                    'description' => $r['body'] ?? '',
                ],
            ];
        }
        return $out;
    }

    public function get_system_calendar_events_for_year(int $year): array
    {
        $out = [];
        if ($this->db->table_exists('ENTITY') && $this->db->field_exists('BIRTH_DATE', 'ENTITY')) {
            $this->db->select('ENTITY_ID, NAME, BIRTH_DATE')->from('ENTITY');
            $this->db->where('BIRTH_DATE IS NOT NULL', null, false);
            $this->db->where('BIRTH_DATE !=', '0000-00-00');
            if ($this->db->field_exists('RECORD_STATUS', 'ENTITY')) {
                $this->db->where('RECORD_STATUS', 0);
            }
            $rows = $this->db->get()->result_array();
            foreach ($rows as $r) {
                $bd = $r['BIRTH_DATE'];
                if (!$bd) {
                    continue;
                }
                $md = date('m-d', strtotime($bd));
                $eventDate = $year . '-' . $md;
                $out[] = [
                    'id' => 'b' . $r['ENTITY_ID'],
                    'title' => 'Birthday: ' . $r['NAME'],
                    'start' => $eventDate,
                    'allDay' => true,
                    'color' => '#e91e63',
                    'extendedProps' => ['category' => 'PORTAL_BIRTHDAY', 'entry_type' => 'BIRTHDAY'],
                ];
            }
        }
        if ($this->db->table_exists('payment_agreement_requests')
            && $this->db->field_exists('selected_course_end_date', 'payment_agreement_requests')) {
            $start = $year . '-01-01';
            $end = $year . '-12-31';
            $rows = $this->db->select('id, client_name, selected_course_end_date')
                ->from('payment_agreement_requests')
                ->where('selected_course_end_date >=', $start)
                ->where('selected_course_end_date <=', $end)
                ->get()->result_array();
            foreach ($rows as $r) {
                if (empty($r['selected_course_end_date'])) {
                    continue;
                }
                $label = !empty($r['client_name']) ? $r['client_name'] : 'Client';
                $out[] = [
                    'id' => 'ae' . $r['id'],
                    'title' => 'Agreement end: ' . $label,
                    'start' => $r['selected_course_end_date'],
                    'allDay' => true,
                    'color' => '#c0392b',
                    'extendedProps' => ['category' => 'PORTAL_AGREEMENT_END', 'entry_type' => 'AGREEMENT_END'],
                ];
            }
        }
        return $out;
    }

    public function sync_entry_reminder_notifications()
    {
        if (!$this->db->table_exists('PORTAL_CALENDAR_ENTRIES')) {
            return;
        }
        $this->load->model('Portal_notification_model');
        $today = date('Y-m-d');
        $rows = $this->db->from('PORTAL_CALENDAR_ENTRIES')
            ->where('is_active', 1)
            ->where('owner_user_id IS NOT NULL', null, false)
            ->get()->result_array();
        foreach ($rows as $r) {
            $uid = (int)$r['owner_user_id'];
            if ($uid <= 0) {
                continue;
            }
            $target = !empty($r['deadline_date']) ? $r['deadline_date'] : $r['start_date'];
            $days = max(0, (int)($r['remind_days_before'] ?? 1));
            $remindOn = date('Y-m-d', strtotime($target . ' -' . $days . ' days'));
            if ($remindOn !== $today) {
                continue;
            }
            if (!empty($r['last_reminder_sent']) && $r['last_reminder_sent'] === $today) {
                continue;
            }
            $this->Portal_notification_model->create_notification(
                $uid,
                'Calendar reminder',
                $r['title'] . ' — due ' . $target,
                site_url('calendar'),
                'portal_calendar',
                (int)$r['id']
            );
            $this->db->where('id', (int)$r['id'])->update('PORTAL_CALENDAR_ENTRIES', [
                'last_reminder_sent' => $today,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function get_events_for_year(int $year): array
    {
        $start = $year . '-01-01';
        $end   = $year . '-12-31';

        $this->db->select('ID, TITLE, START_DATE, END_DATE, CATEGORY');
        $this->db->from('CALENDAR_EVENTS');
        $this->db->where('IS_ACTIVE', 1);
        $this->db->where('START_DATE >=', $start);
        $this->db->where('START_DATE <=', $end);
        $this->db->order_by('START_DATE', 'ASC');
        $rows = $this->db->get()->result_array();

        // Convert to FullCalendar event objects
        $out = [];
        foreach ($rows as $r) {
            $category = $r['CATEGORY'];

            // Small per-category colors (optional)
            $color = null;
            if ($category === 'INDIA_FESTIVAL') $color = '#f39c12';
            if ($category === 'GLOBAL_HOLIDAY') $color = '#3498db';
            if ($category === 'AU_HOLIDAY')     $color = '#2ecc71';

            $out[] = [
                'id'    => (int)$r['ID'],
                'title' => $r['TITLE'],
                'start' => $r['START_DATE'], // date-only => allDay
                'end'   => !empty($r['END_DATE']) ? $r['END_DATE'] : null,
                'allDay'=> true,
                'color' => $color,
                'extendedProps' => [
                    'category' => $category
                ]
            ];
        }

        return $out;
    }

    /**
     * Fetch AU holidays from Nager.Date and cache into CALENDAR_EVENTS (CATEGORY=AU_HOLIDAY)
     * so you don't hit API every time.
     */
    public function sync_au_holidays_from_nager(int $year): void
    {
        // If already cached (any records exist for AU_HOLIDAY + year), skip
        $this->db->from('CALENDAR_EVENTS');
        $this->db->where('CATEGORY', 'AU_HOLIDAY');
        $this->db->where('SOURCE', 'NAGER');
        $this->db->where('START_DATE >=', $year . '-01-01');
        $this->db->where('START_DATE <=', $year . '-12-31');
        $count = (int)$this->db->count_all_results();
        if ($count > 0) return;

        // Nager API: https://date.nager.at/api/v3/PublicHolidays/{year}/{countryCode}
        $url = "https://date.nager.at/api/v3/PublicHolidays/{$year}/AU";
        $json = $this->_http_get_json($url);

        if (!is_array($json)) return;

        foreach ($json as $item) {
            $date = $item['date'] ?? null;
            $name = $item['name'] ?? ($item['localName'] ?? 'Holiday');
            if (!$date) continue;

            // Upsert safe: avoid duplicates on same date+title+category
            $exists = $this->db->from('CALENDAR_EVENTS')
                ->where('CATEGORY', 'AU_HOLIDAY')
                ->where('SOURCE', 'NAGER')
                ->where('START_DATE', $date)
                ->where('TITLE', $name)
                ->limit(1)
                ->get()
                ->row_array();

            if (!$exists) {
                $this->db->insert('CALENDAR_EVENTS', [
                    'TITLE'      => $name,
                    'START_DATE' => $date,
                    'END_DATE'   => null,
                    'CATEGORY'   => 'AU_HOLIDAY',
                    'SOURCE'     => 'NAGER',
                    'IS_ACTIVE'  => 1,
                ]);
            }
        }
    }

    private function _http_get_json(string $url)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        $resp = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http < 200 || $http >= 300 || !$resp) return null;

        $decoded = json_decode($resp, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
    }
}
