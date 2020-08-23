<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

use Config;

use App\PdfDoc;
use App\Repositories\PdfDocsRepository;

use Spatie\PdfToImage\Pdf;
use Org_Heigl\Ghostscript\Ghostscript;

class PdfController extends SiteController
{
    //
    protected $pd_rep;

    public function __construct(PdfDocsRepository $pdfdocs)
    {
        parent::__construct();

        $this->pd_rep = $pdfdocs;

        $this->template = env('THEME').'.index';
    }

    protected function getPdfDocs()
    {
        $pdfDocs = $this->pd_rep->get(['id','filename','description','hash','size'], Config::get('settings.pdf_thumbnails_count'));
        if (!$pdfDocs->isEmpty()) {
            $pdfDocs->transform(
                function ($item, $key) {
                    $pdf_path = Config::get('settings.storage_path.pdf').$item->hash;
                    $item->hash = $pdf_path;
                    $nameWithoutExtension = pathinfo($pdf_path, PATHINFO_FILENAME);
                    $item->image = Config::get('settings.storage_path.image').$nameWithoutExtension.'.'.env('GS_FORMAT');
                    $item->size = round($item->size/1024/1024, 2);
                    return $item;
                }
            );
        }

        return $pdfDocs;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $text = "Some text here";
        $pdfItems = $this->getPdfDocs();

        $content = view(env('THEME').'.pdf.content')->with([
                                                    'pdfs' => $pdfItems,
                                                    'text'=> $text,
                                                    ])->render();
        $this->vars = Arr::add($this->vars, 'content', $content);

        return $this->renderOutput();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $content = view(env('THEME').'.pdf._form')->render();
        $this->vars = Arr::add($this->vars, 'content', $content);

        return $this->renderOutput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
            'description' => 'required'
        ]);

        $file = $request->file('file');
        $name = time().$file->getClientOriginalName();
        $putfile = Storage::putfile('public/uploads/pdf', $file);
        $path = Storage::disk('local')->path($putfile);
        $myFile = config('settings.storage_path.pdf').$file->hashName();
        $filename = pathinfo($myFile, PATHINFO_FILENAME);

        $record = new PdfDoc();
        $record->filename = $file->getClientOriginalName();
        $record->description = $request->input('description');
        $record->hash = $file->hashName();
        $record->size = $file->getSize();
        $record->save();

        //new instance for Ghostscript
        $gs = new Ghostscript();
        $gs->setGsPath(env('GS_PATH'));

        //create image from pdf
        $pdf = new Pdf($path);
        $pdf->setPage(1)->setOutputFormat(env('GS_FORMAT'))->saveImage($filename);

        return redirect()->route('home')->with(['success' => 'PDF Uploaded Successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $pdf = PdfDoc::find($id);
        $content = view(env('THEME').'.pdf.modal')->with([
                                                    'pdf' => $pdf,
                                                    ])->render();
        $this->vars = Arr::add($this->vars, 'content', $content);

        return $this->renderOutput();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $pdf = PdfDoc::find($id);
        if (!$pdf) {
            return redirect()->route('home')->with(['error' => 'Page not found !']);
        }
        $pdf->delete();

        return redirect()->route('home')->with(['success' => 'PDF Deleted Successfully']);
    }
}
