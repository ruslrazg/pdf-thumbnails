<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

use App\PdfDoc;
use App\Repositories\PdfRepository;


use Spatie\PdfToImage\Pdf;
use Org_Heigl\Ghostscript\Ghostscript;

/**
 * Class PdfController
 * @package App\Http\Controllers
 */
class PdfController extends SiteController
{
    //

    /**
     * @var PdfRepository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $model;

    /**
     * PdfController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->model = app(PdfRepository::class);

        $this->template = config('settings.theme').'.index';
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
        //$pdfItems = $this->model->getAll();
        $pdfItems = $this->model->getWithPagination(20);
        //dd($pdfItems);
        $content = view(config('settings.theme').'.pdf.content')->with([
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
        $content = view(config('settings.theme').'.pdf._form')->render();
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

        $pdf = new PdfDoc();

        $pdf->filename = $file->getClientOriginalName();
        $pdf->description = $request->input('description');
        $pdf->hash = $file->hashName();
        $pdf->size = $file->getSize();
        if (!$pdf->save()) {
            return redirect()->back()->with(['error' => 'PDF Upload is Failed.']);
        }

        //new instance for Ghostscript
        $gs = new Ghostscript();
        $gs->setGsPath(config('settings.gs_path'));

        //create image from pdf
        $pdf = new Pdf($path);
        $pdf->setPage(1)->setOutputFormat(config('settings.gs_format'))->saveImage($filename);

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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->model->getEdit($id);
        if (empty($item)) {
            abort(404);
        }

        return $item;
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
        $item = $this->model->getEdit($id);
        $item->delete();
        
        return redirect()->route('home')->with(['success' => 'PDF Deleted Successfully']);
    }
}
