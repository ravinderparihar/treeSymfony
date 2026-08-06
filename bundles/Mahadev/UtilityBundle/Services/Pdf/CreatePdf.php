<?php

namespace Mahadev\UtilityBundle\Services\Pdf;

use Nesk\Puphpeteer\Puppeteer;

class CreatePdf
{

    public function __construct()
    {
    }

    public function createPdfFromHtml(string $content, string $file, $width = '148mm', $height = '90mm'){
        $puppeteer = new Puppeteer(['read_timeout' => 120]);
        $browser = $puppeteer->launch([
            'args' => ['--no-sandbox',
                '--disable-setuid-sandbox',
//                '--proxy-server=3.95.70.115:3128',
            ],
        ]);

        $page = $browser->newPage();

        $page->setContent($content);
//        $page->waitFor(1000);
//        $page->waitForSelector('span[id=recaptcha-anchor]');
//        $page->screenshot(['path' => 'example.png']);
        if(!$width) $pdf = $page->pdf(['path' => $file, 'format' => 'A4' ]);
        else $pdf = $page->pdf(['path' => $file, 'width' => $width, 'height' => $height ]);
//        $page->waitFor(1000);

//        file_put_contents('test.pdf', $pdf);
        $browser->close();
    }

}