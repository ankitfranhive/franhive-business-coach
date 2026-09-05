<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function campaign_merge_tags()
{
    return [
        '{{name}}' => 'Full name',
        '{{first_name}}' => 'First name',
        '{{last_name}}' => 'Last name',
        '{{email}}' => 'Email',
        '{{phone}}' => 'Phone / mobile',
        '{{company}}' => 'Company / store',
        '{{city}}' => 'City',
        '$Name$' => 'Full name (legacy)',
        '$Email$' => 'Email (legacy)',
        '$Phone$' => 'Phone (legacy)',
        '$Company$' => 'Company (legacy)',
    ];
}

function campaign_fill_merge_tags($text, $entity)
{
    $name = trim((string)($entity['NAME'] ?? $entity['user_name'] ?? ''));
    $parts = preg_split('/\s+/', $name, 2);
    $first = $parts[0] ?? '';
    $last = $parts[1] ?? '';
    $email = (string)($entity['EMAIL'] ?? $entity['email_id'] ?? '');
    $phone = (string)($entity['MOBILE'] ?? $entity['PHONE'] ?? '');
    $company = (string)($entity['COMPANY'] ?? $entity['STORE_NAME'] ?? '');
    $city = (string)($entity['CITY'] ?? '');

    $map = [
        '{{name}}' => $name,
        '{{first_name}}' => $first,
        '{{last_name}}' => $last,
        '{{email}}' => $email,
        '{{phone}}' => $phone,
        '{{company}}' => $company,
        '{{city}}' => $city,
        '$Name$' => $name,
        '$Email$' => $email,
        '$Phone$' => $phone,
        '$Company$' => $company,
    ];

    return str_replace(array_keys($map), array_values($map), (string)$text);
}

function campaign_status_meta($status)
{
    $map = [
        'draft' => ['label' => 'Draft', 'class' => 'badge-secondary', 'help' => 'Still being built'],
        'setup' => ['label' => 'Setup done', 'class' => 'badge-info', 'help' => 'Emails and contacts are attached'],
        'scheduled' => ['label' => 'Triggered', 'class' => 'badge-warning', 'help' => 'Scheduled and waiting to send'],
        'in_progress' => ['label' => 'In progress', 'class' => 'badge-primary', 'help' => 'Emails are going out'],
        'sent' => ['label' => 'Sent', 'class' => 'badge-success', 'help' => 'All emails sent'],
        'paused' => ['label' => 'Paused', 'class' => 'badge-dark', 'help' => 'Sending is paused'],
    ];
    $key = $status ?: 'draft';
    return $map[$key] ?? $map['draft'];
}

function campaign_default_timezone()
{
    return 'Australia/Melbourne';
}

function campaign_timezones()
{
    return [
        'Australia/Melbourne' => ['label' => 'Melbourne', 'abbr' => 'AET'],
        'Asia/Kolkata' => ['label' => 'India', 'abbr' => 'IST'],
        'Asia/Manila' => ['label' => 'Philippines', 'abbr' => 'PHT'],
    ];
}

function campaign_timezone_clocks()
{
    $all = campaign_timezones();
    return [
        'Asia/Kolkata' => $all['Asia/Kolkata'],
        'Asia/Manila' => $all['Asia/Manila'],
        'Australia/Melbourne' => $all['Australia/Melbourne'],
    ];
}

function campaign_normalize_timezone($timezone)
{
    $timezone = trim((string)$timezone);
    return array_key_exists($timezone, campaign_timezones()) ? $timezone : campaign_default_timezone();
}

function campaign_timezone_select($name, $selected = null, $extra_class = 'template-timezone')
{
    $selected = campaign_normalize_timezone($selected);
    $class = trim('custom-select ' . $extra_class);
    $html = '<select class="' . htmlspecialchars($class) . '" name="' . htmlspecialchars($name) . '">';
    foreach (campaign_timezones() as $id => $meta) {
        $sel = ($id === $selected) ? ' selected' : '';
        $html .= '<option value="' . htmlspecialchars($id) . '"' . $sel . '>'
            . htmlspecialchars($meta['label'] . ' (' . $meta['abbr'] . ')')
            . '</option>';
    }
    $html .= '</select>';
    return $html;
}

function campaign_build_send_at($date, $time, $timezone = null)
{
    $date = trim((string)$date);
    if ($date === '') {
        return null;
    }
    $time = trim((string)$time);
    if ($time === '') {
        $time = '09:00:00';
    } elseif (substr_count($time, ':') === 0) {
        $time = sprintf('%02d:00:00', (int)$time);
    } elseif (substr_count($time, ':') === 1) {
        $time .= ':00';
    }

    $timezone = campaign_normalize_timezone($timezone);
    try {
        $local = new DateTime($date . ' ' . $time, new DateTimeZone($timezone));
        $local->setTimezone(new DateTimeZone(date_default_timezone_get()));
        return $local->format('Y-m-d H:i:s');
    } catch (Exception $e) {
        return $date . ' ' . $time;
    }
}

function campaign_local_time_value($mapping)
{
    $timezone = campaign_normalize_timezone($mapping['TIMEZONE'] ?? '');
    if (!empty($mapping['SEND_AT'])) {
        try {
            $dt = new DateTime($mapping['SEND_AT'], new DateTimeZone(date_default_timezone_get()));
            $dt->setTimezone(new DateTimeZone($timezone));
            return $dt->format('H:i');
        } catch (Exception $e) {
            // Fall through to the stored hour.
        }
    }
    $stored = (string)($mapping['TEMPLATE_START_TIME'] ?? '');
    if ($stored === '') {
        return '';
    }
    if (strpos($stored, ':') !== false) {
        return substr($stored, 0, 5);
    }
    return sprintf('%02d:00', (int)$stored);
}

function campaign_send_equivalents($send_at)
{
    if (empty($send_at)) {
        return '';
    }
    try {
        $dt = new DateTime($send_at, new DateTimeZone(date_default_timezone_get()));
    } catch (Exception $e) {
        return '';
    }
    $parts = [];
    foreach (campaign_timezone_clocks() as $id => $meta) {
        $copy = clone $dt;
        $copy->setTimezone(new DateTimeZone($id));
        $parts[] = $meta['label'] . ': ' . $copy->format('h:i:s A') . ' (' . $meta['abbr'] . ')';
    }
    return implode(' · ', $parts);
}

function campaign_format_start_time($mapping)
{
    $timezone = campaign_normalize_timezone($mapping['TIMEZONE'] ?? '');
    $meta = campaign_timezones()[$timezone];
    $time = campaign_local_time_value($mapping);
    if ($time === '') {
        return '';
    }
    $label = date('h:i A', strtotime('1970-01-01 ' . $time . ':00'));
    return $label . ' ' . $meta['abbr'] . ' (' . $meta['label'] . ')';
}

function campaign_audience_label($module_name)
{
    switch ($module_name) {
        case 'Lead':
            return 'Leads';
        case 'Client':
            return 'Clients';
        case 'User':
            return 'CRM users';
        default:
            return $module_name ?: '';
    }
}

function campaign_legacy_status($workflow)
{
    switch ($workflow) {
        case 'sent':
            return '0';
        case 'in_progress':
            return 'C';
        case 'draft':
            return 'D';
        case 'paused':
            return 'D';
        case 'setup':
        case 'scheduled':
        default:
            return '1';
    }
}
