<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::published()->latest('published_at')->take(6)->get();
        return view('home', compact('posts'));
    }
}
