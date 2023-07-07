<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

use Symfony\Component\HttpKernel\KernelInterface;

class PdfService
{
    private $domPdf;

    public function __construct()
    {
        $this->domPdf = new DomPdf();
        $this->domPdf = new DomPdf(['debug' => true]);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial'); // Spécifiez la police par défaut à utiliser

        $this->domPdf->setOptions($pdfOptions);
    }

    public function showPdfFile($html)
    {
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->stream("details.pdf", [
            'Attachement' => true
        ]);
    }

    public function generateBinaryPdf($html)
    {
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();

        $output = $this->domPdf->output();

        return $output;
    }
}
