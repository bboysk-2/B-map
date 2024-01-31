<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class MyPageController extends Controller
{
    public function index()
    {
        return Inertia::render('MyPage');
    }
}