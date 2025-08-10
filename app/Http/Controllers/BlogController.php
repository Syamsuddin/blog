<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $category = $request->string('category')->toString();
        $tag = $request->string('tag')->toString();
        $sort = $request->string('sort', 'latest')->toString();
        
        $query = Post::with(['user','category','tags'])->published();

        // Search filter
        if ($q) {
            $query->where(function($sub) use ($q){
                $sub->where('title','like',"%$q%")
                    ->orWhere('excerpt','like',"%$q%")
                    ->orWhere('body','like',"%$q%");
            });
        }

        // Category filter
        if ($category) {
            $query->whereHas('category', function($sub) use ($category) {
                $sub->where('slug', $category);
            });
        }

        // Tag filter
        if ($tag) {
            $query->whereHas('tags', function($sub) use ($tag) {
                $sub->where('slug', $tag);
            });
        }

        // Sorting
        switch ($sort) {
            case 'oldest':
                $query->oldest('published_at');
                break;
            case 'popular':
                $query->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 'title':
                $query->orderBy('title');
                break;
            default:
                $query->latest('published_at');
        }

        $posts = $query->paginate(10)->withQueryString();

        $categories = Category::withCount('posts')->orderBy('name')->get();
        $tags = Tag::withCount('posts')->orderBy('name')->get();

        return view('blog.index', compact('posts','categories','tags','q','category','tag','sort'));
    }

    public function show($slug)
    {
        $post = Post::with(['user','category','tags','comments' => function($q){
            $q->where('is_approved', true)->latest();
        }])->where('slug',$slug)->published()->firstOrFail();

        return view('blog.show', compact('post'));
    }

    public function category(Category $category, Request $request)
    {
        $search = $request->string('search')->toString();
        $sort = $request->string('sort', 'latest')->toString();
        
        $query = Post::with(['user','category','tags'])
            ->where('category_id', $category->id)
            ->published();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('excerpt', 'like', "%$search%")
                  ->orWhere('body', 'like', "%$search%");
            });
        }

        switch ($sort) {
            case 'oldest':
                $query->oldest('published_at');
                break;
            case 'popular':
                $query->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 'title':
                $query->orderBy('title');
                break;
            default:
                $query->latest('published_at');
        }

        $posts = $query->paginate(12)->withQueryString();

        return view('blog.category', compact('category', 'posts'));
    }

    public function tag(Tag $tag, Request $request)
    {
        $search = $request->string('search')->toString();
        $sort = $request->string('sort', 'latest')->toString();
        
        $query = Post::with(['user','category','tags'])
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            })
            ->published();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('excerpt', 'like', "%$search%")
                  ->orWhere('body', 'like', "%$search%");
            });
        }

        switch ($sort) {
            case 'oldest':
                $query->oldest('published_at');
                break;
            case 'popular':
                $query->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 'title':
                $query->orderBy('title');
                break;
            default:
                $query->latest('published_at');
        }

        $posts = $query->paginate(12)->withQueryString();

        return view('blog.tag', compact('tag', 'posts'));
    }
}
