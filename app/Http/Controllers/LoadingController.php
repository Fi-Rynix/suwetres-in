<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoadingController extends Controller
{
    public function loading() {
        return view('pages.loading.loading');
    }
}
