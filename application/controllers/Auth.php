<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url','form','security']);
        $this->load->library(['session','form_validation','email']);
        $this->load->model(['User_Model','PasswordReset_model']);
        $this->load->library('email');
    }

    /* ---------- Forgot Password (form) ---------- */
    public function forgot_password()
    {
        // prevent bfcache/stale messages on reload/back
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');

    //    echo "here is code";die;
        $this->load->view('auth/forgot_password');
    }

    /* ---------- Forgot Password (handle POST) ---------- */
    public function forgot_password_submit()
    {

        error_reporting(E_ALL);
ini_set('display_errors', 1);

        
        if (strtolower($this->input->method()) !== 'post') {
            return redirect('forgot-password');
        }

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', trim(strip_tags(validation_errors())));
            return redirect('forgot-password');
        }

        $email = strtolower(trim($this->input->post('email', true)));

        $user  = $this->User_Model->get_user_by_email($email);

        // echo "<pre>";
        // print_r(  $user );die;
        if (!$user) {
            $this->session->set_flashdata('error', 'Email not found.');
            return redirect('forgot-password');
        }

        // if ((int)$user['status'] !== 1) {
        //     $this->session->set_flashdata('error', 'Account not available.');
        //     return redirect('forgot-password');
        // }

        // Throttle: block resends within 5 minutes (treat as NOT sent)
        if ($this->PasswordReset_model->recent_request_exists($email, 5)) {
            $this->session->set_flashdata('error', 'A reset email was already sent recently. Please check your inbox or try again in a few minutes.');
            return redirect('forgot-password');
        }

        // Create token & store
        $token      = bin2hex(random_bytes(32));
        $token_hash = password_hash($token, PASSWORD_DEFAULT);
        $expires_at = date('Y-m-d H:i:s', time() + 3600);

        $this->PasswordReset_model->create_token([
            'email'       => $email,
            'token_hash'  => $token_hash,
            'expires_at'  => $expires_at,
            'ip_address'  => $this->input->ip_address(),
            'user_agent'  => substr((string)$this->input->user_agent(), 0, 255),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        // Build email
        $reset_url    = base_url('reset-password/' . $token);
        $subject      = 'Password Reset Instructions';
        $message_html = '<p>Hello,</p>
                         <p>We received a request to reset your password. Click the link below to set a new password:</p>
                         <p><a href="'.$reset_url.'">'.$reset_url.'</a></p>
                         <p>This link will expire in 60 minutes. If you did not request this, you can ignore this email.</p>';
        $message_text = "Hello,\n\nUse the link below to reset your password (valid for 60 minutes):\n{$reset_url}\n\nIf you didn’t request this, ignore this email.";

        // Send
        if ($this->_send_email($email, $subject, $message_html, $message_text)) {
            $this->session->set_flashdata('success', 'Reset instructions have been sent to your email.');
        } else {
            $this->session->set_flashdata('error', 'We couldn’t send the reset email right now. Please try again later.');
        }

        return redirect('forgot-password');
    }

    /* ---------- Reset Password (form by token) ---------- */
    public function reset_password($token = null)
    {
        // prevent bfcache/stale messages on reload/back
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');

        if (!$token || strlen($token) < 20) {
            $this->session->set_flashdata('error', 'Invalid or expired link.');
            return redirect('forgot-password');
        }

        $reset = $this->PasswordReset_model->get_valid_by_token($token);
        // echo "<pre>";
        // print_r( $reset );die;
        if (!$reset) {
            $this->session->set_flashdata('error', 'Invalid or expired link.');
            return redirect('forgot-password');
        }

        $data['token'] = $token;
        // var_dump($data);
        $this->load->view('auth/reset_password', $data);
    }

    /* ---------- Reset Password (handle POST) ---------- */
    public function reset_password_submit()
    {
        $this->form_validation->set_rules('token', 'Token', 'required|min_length[20]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[128]');
        $this->form_validation->set_rules('password_confirm', 'Confirm Password', 'required|matches[password]');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', trim(strip_tags(validation_errors())));
            $token = $this->input->post('token');
            return redirect('reset-password/' . $token);
        }
        // echo "here we are die;";die;

      $token    = $this->input->post('token');
        $password = (string)$this->input->post('password');

        $reset = $this->PasswordReset_model->get_valid_by_token($token);
        // echo "<pre>";
        // print_r($reset );die;
        if (!$reset) {
            $this->session->set_flashdata('error', 'Invalid or expired link.');
            return redirect('forgot-password');
        }

        $user = $this->User_Model->get_user_by_email($reset['email']);

    

        if (!$user ) {
            $this->session->set_flashdata('error', 'Account not available.');
            return redirect('forgot-password');
        }

       // Update password (bcrypt)
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $updated = $this->User_Model->update_password((int)$user['USER_ID'], $password);

        // Invalidate token only if update was successful
        if ($updated) {
            $this->PasswordReset_model->mark_used((int)$reset['id']);
            $this->session->set_flashdata('success', 'Your password has been updated. Please log in.');
            return redirect('login');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong. Password was not updated. Please try again.');
            return redirect('reset-password/' . $token);
        }

    }

    /* ---------- Helper: send email (Hostinger SMTP) ---------- */
    private function _send_email($to, $subject, $html, $text = '')
    {

        // echo "kya yhan hai hum";die;

        // $config['protocol'] = 'smtp';
        // $config['smtp_host'] = 'smtp.hostinger.com';
        // $config['smtp_user'] = 'nlp@empoweryourdestiny.com.au';
        // $config['smtp_pass'] = 'Franh1ve@2024';
        // $config['smtp_port'] = 587;
        // $config['mailtype'] = 'html';
        // $config['charset'] = 'utf-8';
        // $config['newline'] = "\r\n";
    
        // $this->email->initialize($config);
    
        // $this->email->set_newline("\r\n");
        // $this->email->from("info@franhive.com", "TEst");
        // $this->email->to("yesiamankitsharma@gmail.com"); // Recipient's email address
        // $this->email->subject("TEst");
        // $this->email->message("This is Test email");
    
        // if ($this->email->send()) {
            
        //         echo nl2br($this->email->print_debugger(['headers','subject','body']));
        //         exit; // remove after debugging
      
        // } else {
        //     show_error($this->email->print_debugger());
        // }



        $config = [
            'protocol'     => 'smtp',
            'smtp_host'    => 'smtp.hostinger.com',
            'smtp_user'    => 'nlp@empoweryourdestiny.com.au',
            'smtp_pass'    => 'Franh1ve@2024',
            'smtp_port'    => 587,
            'smtp_crypto'  => 'tls',   // use 'ssl' with port 465 if your host requires it
            'mailtype'     => 'html',
            'charset'      => 'utf-8',
            'newline'      => "\r\n",
            'crlf'         => "\r\n",
            'wordwrap'     => true,
        ];

        $this->load->library('email');
        $this->email->clear(true);
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->set_crlf("\r\n");

        $fromEmail = 'nlp@empoweryourdestiny.com.au';
        $this->email->from($fromEmail, 'EYD Training');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($html);
        if (!empty($text)) {
            $this->email->set_alt_message($text);
        }
        // $this->email->reply_to('support@empoweryourdestiny.com.au', 'Support');

        $sent = $this->email->send(false);

        if (!$sent) {
            log_message('error', 'Email send failed: ' . $this->email->print_debugger(['headers','subject','body']));
            return false;
        }
        return true;
    }
}
