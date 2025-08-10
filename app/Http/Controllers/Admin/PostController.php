<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $posts = Post::with('category','user')->latest()->paginate(15);
    return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    $categories = Category::orderBy('name')->get();
    $tags = Tag::orderBy('name')->get();
    return view('admin.posts.create', compact('categories','tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'excerpt' => ['nullable','string','max:500'],
            'body' => ['required','string'],
            'category_id' => ['nullable','exists:categories,id'],
            'tags' => ['array'],
            'tags.*' => ['exists:tags,id'],
            'featured_image' => ['nullable','image','mimes:jpeg,png,webp','max:2048'],
            'meta_title' => ['nullable','string','max:255'],
            'meta_description' => ['nullable','string','max:255'],
            'publish_now' => ['nullable','boolean'],
        ]);

        $slugBase = Str::slug($validated['title']);
        $slug = $slugBase;
        $i = 1;
        while (Post::where('slug',$slug)->exists()) { $slug = $slugBase.'-'.$i++; }

        $path = null;
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('posts','public');
        }

        $meta = [
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
        ];

        $post = Post::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $slug,
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => Purifier::clean($validated['body'], 'default'),
            'featured_image' => $path,
            'meta' => $meta,
            'is_published' => (bool)($validated['publish_now'] ?? false),
            'published_at' => ($validated['publish_now'] ?? false) ? now() : null,
        ]);

        $post->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('admin.posts.edit',$post)->with('status','Post created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
    return redirect()->route('admin.posts.edit',$post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
    $categories = Category::orderBy('name')->get();
    $tags = Tag::orderBy('name')->get();
    $post->load('tags');
    return view('admin.posts.edit', compact('post','categories','tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'excerpt' => ['nullable','string','max:500'],
            'body' => ['required','string'],
            'category_id' => ['nullable','exists:categories,id'],
            'tags' => ['array'],
            'tags.*' => ['exists:tags,id'],
            'featured_image' => ['nullable','image','mimes:jpeg,png,webp','max:2048'],
            'meta_title' => ['nullable','string','max:255'],
            'meta_description' => ['nullable','string','max:255'],
            'publish_now' => ['nullable','boolean'],
            'is_published' => ['nullable','boolean'],
        ]);

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) { Storage::disk('public')->delete($post->featured_image); }
            $post->featured_image = $request->file('featured_image')->store('posts','public');
        }

        $post->fill([
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => Purifier::clean($validated['body'], 'default'),
            'meta' => [
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
            ],
        ]);

        if (!empty($validated['publish_now'])) {
            $post->is_published = true;
            $post->published_at = now();
        } elseif (array_key_exists('is_published', $validated)) {
            $post->is_published = (bool)$validated['is_published'];
        }

        $post->save();
        $post->tags()->sync($validated['tags'] ?? []);

        return back()->with('status','Post updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
    if ($post->featured_image) { Storage::disk('public')->delete($post->featured_image); }
    $post->delete();
    return redirect()->route('admin.posts.index')->with('status','Post deleted');
    }
}
