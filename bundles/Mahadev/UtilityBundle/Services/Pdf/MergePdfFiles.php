<?php


namespace Mahadev\UtilityBundle\Services\Pdf;

use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PageBoundaries;
use setasign\Fpdi\PdfReader\PdfReaderException;
use setasign\Fpdi\Tcpdf\Fpdi;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MergePdfFiles
{

    /** @var Fpdi  */
    private Fpdi $pdf;

    public function __construct()
    {
        $this->pdf = new Fpdi();
        $this->pdf->setPrintFooter(false);
        $this->pdf->setPrintHeader(false);
    }

    /**
     * @throws CrossReferenceException
     * @throws PdfReaderException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws FilterException
     */
    public function mergePdf($files): void
    {
        /** @var UploadedFile $file */
        foreach ($files AS $file) {
            $this->addPdf($file);
        }
    }

    /**
     * @throws CrossReferenceException
     * @throws PdfReaderException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws FilterException
     */
    public function addPdf(\SplFileInfo $file): void
    {
//        echo $file->getPathname().PHP_EOL;

        $pageCount = $this->pdf->setSourceFile($file->getPathname());
        // iterate through all pages
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            // import a page
            $templateId = $this->pdf->importPage($pageNo);
            // get the size of the imported page
            $size = $this->pdf->getTemplateSize($templateId);
            $this->pdf->AddPage($size['orientation'], array($size['width'], $size['height']));
            // use the imported page
            $this->pdf->useTemplate($templateId);
//                $pdf->SetFont('Helvetica');
//                $pdf->SetXY(5, 5);
//                $pdf->Write(8, 'A simple concatenation demo with FPDI');
        }

    }

    public function addImage(\SplFileInfo $file): void
    {
        $this->pdf->AddPage("P", "A4");
        $content =  file_get_contents($file->getPathname());
//        $barcode = '<img src="'.$file->getPathname().'" />';
//        $this->pdf->writeHTML($barcode, true, false, false, false, '');

        $this->pdf->Image("@".$content);
    }

    public function createFinalPdf($file): void
    {
        $this->pdf->Output($file, 'F');
    }

}