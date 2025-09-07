<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Galleries;
use App\Models\Reviews;

class HomeController extends Controller
{
    public function index()
    {
        $testimonials = Reviews::latest()->take(15)->get();
        $mediaItems = Galleries::latest()->get();

        return view('pages.home.index', compact('testimonials', 'mediaItems'));
    }
}
