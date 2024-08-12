<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SourceFilesController extends Controller
{
    public function index()
    {
        return view('sources');
    }
}
