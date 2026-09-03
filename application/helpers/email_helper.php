<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('send_email')) {
    function send_email($to, $subject, $message, $from = null, $from_name = null) {
        $CI =& get_instance();
        $CI->load->library('email');

        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'smtp.hostinger.com',
            'smtp_port' => 587,
            'smtp_user' => 'ankitsharma@letslearneasy.com',
            'smtp_pass' => 'Doordochar@123',
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'wordwrap'  => TRUE,
            'smtp_crypto' => 'tls',
        );

        $CI->email->initialize($config);
        $CI->email->from($from ? $from : 'ankitsharma@letslearneasy.com', $from_name ? $from_name : 'Your Name');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);

        if (!$CI->email->send()) {
            $error_message = $CI->email->print_debugger();
            log_message('error', 'Email failed to send: ' . $error_message);
            return false;
        }
        
        return true;
    }
}
