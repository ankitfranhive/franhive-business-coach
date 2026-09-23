<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('lesson_attachment_label')) {
    function lesson_attachment_label($file)
    {
        if (is_array($file)) {
            if (!empty($file['name'])) {
                return (string)$file['name'];
            }
            $file = $file['url'] ?? '';
        }
        $path = parse_url((string)$file, PHP_URL_PATH);
        $base = rawurldecode(basename($path ?: (string)$file));
        if (preg_match('/^\d+_(.+)$/', $base, $m)) {
            return $m[1];
        }
        return $base !== '' ? $base : 'Attachment';
    }
}

if (!function_exists('lesson_attachment_url')) {
    function lesson_attachment_url($file)
    {
        if (is_array($file)) {
            return (string)($file['url'] ?? '');
        }
        return (string)$file;
    }
}

if (!function_exists('lesson_attachment_items')) {
    function lesson_attachment_items($raw, $include_deleted = false)
    {
        $list = $raw;
        if (is_string($raw)) {
            $list = json_decode($raw, true);
        }
        if (!is_array($list)) {
            return array();
        }
        $out = array();
        foreach ($list as $item) {
            if (is_string($item)) {
                $row = array(
                    'url' => $item,
                    'name' => lesson_attachment_label($item),
                    'deleted' => 0,
                );
            } elseif (is_array($item)) {
                $url = (string)($item['url'] ?? '');
                if ($url === '') {
                    continue;
                }
                $row = array(
                    'url' => $url,
                    'name' => !empty($item['name']) ? (string)$item['name'] : lesson_attachment_label($url),
                    'deleted' => !empty($item['deleted']) ? 1 : 0,
                );
            } else {
                continue;
            }
            if (!$include_deleted && !empty($row['deleted'])) {
                continue;
            }
            $out[] = $row;
        }
        return $out;
    }
}
