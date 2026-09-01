<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Replace campaign-style placeholders in email template text.
 *
 * Supported placeholders:
 *   $Name$          Recipient name
 *   $Link$          Document / form URL
 *   $BusinessName$  Form subject title (business_name field)
 *   $SenderName$    Sending organisation (default: Empower Your Destiny)
 */
function apply_email_template_placeholders($text, array $vars)
{
    if ($text === null || $text === '') {
        return '';
    }

    $name         = isset($vars['name']) ? (string)$vars['name'] : '';
    $link         = isset($vars['link']) ? (string)$vars['link'] : '';
    $businessName = isset($vars['business_name']) ? (string)$vars['business_name'] : '';
    $senderName   = isset($vars['sender_name']) ? (string)$vars['sender_name'] : 'Empower Your Destiny';

    $search = array('$Name$', '$Link$', '$BusinessName$', '$SenderName$');
    $replace = array($name, $link, $businessName, $senderName);

    return str_replace($search, $replace, $text);
}

/**
 * Build HTML email body from a TEMPLATES row (body + signature).
 */
function build_template_email_body(array $template, array $vars)
{
    $body = isset($template['TEMPLATE_BODY']) ? $template['TEMPLATE_BODY'] : '';
    $sign = isset($template['TEMPLATE_SIGN']) ? $template['TEMPLATE_SIGN'] : '';

    $body = apply_email_template_placeholders($body, $vars);
    $sign = apply_email_template_placeholders($sign, $vars);

    $html = '<div style="font-family:Arial, sans-serif; font-size:14px;">';
    $html .= $body;
    if (trim(strip_tags($sign)) !== '') {
        $html .= '<br><br>' . $sign;
    }
    $html .= '</div>';

    return $html;
}
