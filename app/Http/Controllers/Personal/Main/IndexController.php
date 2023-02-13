<?php

namespace App\Http\Controllers\Personal\Main;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke()
    {
        $countLikedPosts = auth()->user()->likedPosts->count();
        $countComments = auth()->user()->comments->count();
        return view('personal.main.index', compact('countLikedPosts', 'countComments'));
    }
}
