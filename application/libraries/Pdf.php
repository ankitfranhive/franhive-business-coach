<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf {

    public function create($html, $paper = 'A4', $orientation = 'portrait') {

        $options = new Options();
        // Keep remote images off so Dompdf never HTTP-fetches localhost
        // (php -S is single-threaded and would deadlock until timeout).
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isFontSubsettingEnabled', true);
        if (defined('FCPATH')) {
            $options->setChroot(FCPATH);
        }

        $html = $this->embed_remote_images((string)$html);

        $dompdf = new Dompdf($options);
        if (defined('FCPATH')) {
            $dompdf->setBasePath(FCPATH);
        }
        $dompdf->setPaper($paper, $orientation);
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Convert img src URLs to data URIs so Dompdf can render them without
     * making HTTP requests (which deadlock php -S on localhost).
     */
    private function embed_remote_images($html)
    {
        if ($html === '' || stripos($html, '<img') === false) {
            return $html;
        }

        return preg_replace_callback(
            '/<img\b([^>]*?)src\s*=\s*(["\'])(.*?)\2([^>]*)>/i',
            function ($m) {
                $src = html_entity_decode(trim($m[3]), ENT_QUOTES, 'UTF-8');
                if ($src === '' || stripos($src, 'data:') === 0) {
                    return $m[0];
                }
                $data = $this->url_to_data_uri($src);
                if ($data === null) {
                    return '';
                }
                return '<img' . $m[1] . 'src="' . $data . '"' . $m[4] . '>';
            },
            $html
        );
    }

    private function url_to_data_uri($src)
    {
        if (preg_match('#^(https?://)(127\.0\.0\.1|localhost|0\.0\.0\.0)(:|/|$)#i', $src)) {
            return null;
        }

        $bytes = null;
        $mime = 'image/png';

        if (preg_match('#^https?://#i', $src)) {
            $ctx = stream_context_create([
                'http' => ['timeout' => 4, 'follow_location' => 1],
                'https' => ['timeout' => 4, 'follow_location' => 1],
            ]);
            $bytes = @file_get_contents($src, false, $ctx);
        } else {
            $path = $src;
            if (defined('FCPATH') && !is_file($path)) {
                $rel = ltrim(str_replace('\\', '/', $src), '/');
                $path = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
            }
            if (is_file($path) && is_readable($path)) {
                $bytes = @file_get_contents($path);
            }
        }

        if ($bytes === false || $bytes === null || $bytes === '') {
            return null;
        }

        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $detected = finfo_buffer($finfo, $bytes);
                finfo_close($finfo);
                if (!empty($detected) && strpos($detected, 'image/') === 0) {
                    $mime = $detected;
                }
            }
        }

        return 'data:' . $mime . ';base64,' . base64_encode($bytes);
    }
}
