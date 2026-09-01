<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf {

    public function create($html, $paper = 'A4', $orientation = 'portrait') {

        $options = new Options();
        $options->set('isRemoteEnabled', true);   // allow logo/image urls
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isFontSubsettingEnabled', true);
        if (defined('FCPATH')) {
            $options->setChroot(FCPATH);
        }

        $dompdf = new Dompdf($options);
        if (defined('FCPATH')) {
            $dompdf->setBasePath(FCPATH);
        }
        $dompdf->setPaper($paper, $orientation);
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->output();
    }
}
