<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $avatarPath = $request->user()->avatar ?: asset('/images/no_avatar.png');

        return Inertia::render('MyPage', [
            'avatarPath' => $avatarPath,
        ]);
    }
}