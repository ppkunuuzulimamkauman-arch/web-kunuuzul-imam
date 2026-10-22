<?php

namespace App\Http\Controllers\Siakad;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function index(): View
    {
        return view('siakad.portal');
    }
}
