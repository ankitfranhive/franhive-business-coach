<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar_model extends CI_Model {

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
