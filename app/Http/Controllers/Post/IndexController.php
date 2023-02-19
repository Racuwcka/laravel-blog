<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;

class IndexController extends Controller
{
    public function __invoke()
    {
        $posts = Post::paginate(6);
        $randomPosts = Post::get();
        if (empty($randomPosts)) {
            $randomPosts->random(4);
        }
        $likedPosts = Post::withCount('likedUsers');
        if (empty($likedPosts)) {
            $likedPosts->orderBy('liked_user_count', 'DESC')->get()->take(4);
        }
        return view('post.index', compact('posts', 'randomPosts', 'likedPosts'));
    }
}
