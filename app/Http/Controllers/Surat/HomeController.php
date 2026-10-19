<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('modules.coming-soon', ['module' => 'Surat']);
    }
}
