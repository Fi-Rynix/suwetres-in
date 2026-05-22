<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function scan() {
        if (!session('data_kuisioner')) return redirect()->route('kuisioner');
        return view('pages.scan.scan');
    }
}
