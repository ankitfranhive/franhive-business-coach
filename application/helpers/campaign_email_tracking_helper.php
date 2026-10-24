<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function campaign_unsubscribe_url($token)
{
    return site_url('email/unsubscribe/' . rawurlencode($token));
}

function campaign_open_pixel_url($token)
{
    return site_url('email/open/' . rawurlencode($token));
}

function campaign_click_track_url($token, $destination_url)
{
    $u = base64_encode($destination_url);
    return site_url('email/click/' . rawurlencode($token) . '?u=' . rawurlencode($u));
}

function campaign_email_append_unsubscribe($html, $token)
{
    $html = (string)$html;
    $link = campaign_unsubscribe_url($token);
    if (stripos($html, 'unsubscribe') !== false) {
        return $html;
    }
    $footer = '<p style="margin-top:24px;font-size:12px;color:#666;text-align:center;">'
        . '<a href="' . htmlspecialchars($link, ENT_QUOTES, 'UTF-8') . '" style="color:#666;">Unsubscribe</a>'
        . ' from these emails.</p>';
    return $html . $footer;
}

function campaign_email_inject_open_pixel($html, $token)
{
    $pixel = '<img src="' . htmlspecialchars(campaign_open_pixel_url($token), ENT_QUOTES, 'UTF-8')
        . '" width="1" height="1" alt="" style="display:block;border:0;outline:none;" />';
    if (stripos($html, '</body>') !== false) {
        return preg_replace('/<\/body>/i', $pixel . '</body>', $html, 1);
    }
    return $html . $pixel;
}

function campaign_email_wrap_links($html, $token)
{
    return preg_replace_callback(
        '/\shref=(["\'])(https?:\/\/[^"\']+)\1/i',
        function ($m) use ($token) {
            $dest = $m[2];
            if (stripos($dest, 'email/unsubscribe/') !== false
                || stripos($dest, 'email/open/') !== false
                || stripos($dest, 'email/click/') !== false) {
                return $m[0];
            }
            $track = campaign_click_track_url($token, $dest);
            return ' href="' . htmlspecialchars($track, ENT_QUOTES, 'UTF-8') . '"';
        },
        (string)$html
    );
}

function campaign_email_prepare_outgoing_html($html, $token)
{
    $html = campaign_email_append_unsubscribe($html, $token);
    $html = campaign_email_wrap_links($html, $token);
    $html = campaign_email_inject_open_pixel($html, $token);
    return $html;
}

function campaign_generate_tracking_token()
{
    return bin2hex(random_bytes(16));
}
