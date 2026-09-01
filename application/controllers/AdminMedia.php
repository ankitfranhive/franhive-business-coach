<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminMedia extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Keep your permission checks here if you use them
    }

    public function images() {
        // 1) Read flash messages ONCE
        $data['success'] = $this->session->flashdata('success');
        $data['error']   = $this->session->flashdata('error');

        // 2) Safety: if someone used set_userdata earlier, clear it so refresh won't show again
        $this->session->unset_userdata('success');
        $this->session->unset_userdata('error');

        $baseDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR;

        // Use a separate key so we don't clash with flash error
        $data['dir_error'] = null;

        if (!is_dir($baseDir)) {
            $data['dir_error'] = "Uploads directory not found: " . $baseDir;
            $data['images'] = [];
            $data['total'] = 0;
            $data['page'] = 1;
            $data['per_page'] = 48;
            $data['q'] = '';
            $data['year'] = '';
            $data['month'] = '';
            $this->load->view('media/images_list', $data);
            return;
        }

        // Filters
        $q     = trim((string)$this->input->get('q', true));
        $year  = trim((string)$this->input->get('year', true));
        $month = trim((string)$this->input->get('month', true));

        $exts = ['jpg','jpeg','png','gif','webp'];
        $paths = [];

        $isYear  = ($year !== '' && preg_match('/^\d{4}$/', $year));
        $isMonth = ($month !== '' && preg_match('/^\d{1,2}$/', $month));
        if ($isMonth) $month = str_pad($month, 2, '0', STR_PAD_LEFT);

        if ($isYear && $isMonth) {
            foreach ($exts as $e) {
                $paths = array_merge($paths, glob($baseDir.$year.DIRECTORY_SEPARATOR.$month.DIRECTORY_SEPARATOR.'*.'.$e));
            }
        } elseif ($isYear) {
            foreach ($exts as $e) {
                $paths = array_merge($paths, glob($baseDir.$year.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.'*.'.$e));
            }
        } else {
            $paths = $this->_scan_images_recursive($baseDir, $exts);
        }

        $images = [];
        foreach ($paths as $abs) {
            if (!is_file($abs)) continue;

            $file = basename($abs);
            if ($q !== '' && stripos($file, $q) === false) continue;

            $rel = 'uploads/' . str_replace('\\', '/', ltrim(str_replace($baseDir, '', $abs), DIRECTORY_SEPARATOR));
            $url = base_url($rel);

            $images[] = [
                'file' => $file,
                'rel'  => $rel,
                'url'  => $url,
                'size' => @filesize($abs) ?: 0,
                'mtime'=> @filemtime($abs) ?: 0,
            ];
        }

        usort($images, function($a,$b){ return ($b['mtime'] ?? 0) <=> ($a['mtime'] ?? 0); });

        $per_page = 48;
        $page = (int)$this->input->get('page');
        if ($page < 1) $page = 1;

        $total = count($images);
        $offset = ($page - 1) * $per_page;
        $paged = array_slice($images, $offset, $per_page);

        $data['images']   = $paged;
        $data['total']    = $total;
        $data['page']     = $page;
        $data['per_page'] = $per_page;
        $data['q']        = $q;
        $data['year']     = $year;
        $data['month']    = $month;

        $this->load->view('media/images_list', $data);
    }

    public function upload_image() {
        if ($this->input->method() !== 'post') {
            redirect('admin_media/images');
        }

        if (empty($_FILES['image_file']['name'])) {
            $this->session->set_flashdata('error', 'Please select an image to upload.');
            redirect('admin_media/images');
        }

        $year  = date('Y');
        $month = date('m');

        $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . $month . DIRECTORY_SEPARATOR;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $config = [];
        $config['upload_path']   = $uploadDir;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size']      = 5120; // 5MB
        $config['encrypt_name']  = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image_file')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('admin_media/images');
        }

        $up = $this->upload->data();
        $rel = 'uploads/'.$year.'/'.$month.'/'.$up['file_name'];
        $this->session->set_flashdata('success', 'Uploaded successfully: '.$rel);

        redirect('admin_media/images');
    }

    public function delete_image() {
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $rel = (string)$this->input->post('rel_path', true);
        $rel = str_replace('\\', '/', $rel);
        $rel = ltrim($rel, '/');

        if (strpos($rel, 'uploads/') !== 0) {
            $this->session->set_flashdata('error', 'Invalid path.');
            redirect('admin_media/images');
        }

        if (strpos($rel, '..') !== false) {
            $this->session->set_flashdata('error', 'Invalid path (traversal blocked).');
            redirect('admin_media/images');
        }

        $abs = FCPATH . $rel;

        if (!file_exists($abs) || !is_file($abs)) {
            $this->session->set_flashdata('error', 'File not found.');
            redirect('admin_media/images');
        }

        $ext = strtolower(pathinfo($abs, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];
        if (!in_array($ext, $allowed, true)) {
            $this->session->set_flashdata('error', 'Only image files can be deleted from here.');
            redirect('admin_media/images');
        }

        if (@unlink($abs)) {
            $this->session->set_flashdata('success', 'Deleted: '.$rel);
        } else {
            $this->session->set_flashdata('error', 'Unable to delete file (permission issue).');
        }

        $back = $this->input->post('back_url', true);
        if ($back) redirect($back);

        redirect('admin_media/images');
    }

    private function _scan_images_recursive($dir, $exts) {
        $out = [];
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
        );
        foreach ($it as $fileInfo) {
            if (!$fileInfo->isFile()) continue;
            $ext = strtolower($fileInfo->getExtension());
            if (in_array($ext, $exts, true)) {
                $out[] = $fileInfo->getPathname();
            }
        }
        return $out;
    }
}
