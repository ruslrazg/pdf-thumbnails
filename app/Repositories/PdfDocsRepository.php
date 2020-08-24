<?php

namespace App\Repositories;

use App\PdfDoc;

class PdfDocsRepository extends Repository
{
    public function __construct(PdfDoc $pdfDocs)
    {
        $this->model = $pdfDocs;
    }
}
