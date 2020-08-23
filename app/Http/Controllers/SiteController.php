<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SiteController extends Controller
{
    protected $template;

    protected $vars = array();

    public function __construct()
    {
        //
        $this->template = env('THEME').'.layouts.app';
    }

    protected function renderOutput()
    {
        //
        return view($this->template)->with($this->vars);
    }
}
