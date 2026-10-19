<?php

namespace App\Http\Controllers\Psb;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('modules.coming-soon', ['module' => 'PSB']);
    }
}
