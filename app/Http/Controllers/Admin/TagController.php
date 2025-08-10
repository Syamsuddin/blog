<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::withCount('posts')->orderBy('name')->paginate(20);
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:100','unique:tags,name'],
        ]);
        $slugBase = Str::slug($validated['name']);
        $slug = $slugBase; $i=1;
        while (Tag::where('slug',$slug)->exists()) { $slug = $slugBase.'-'.$i++; }
        Tag::create(['name'=>$validated['name'],'slug'=>$slug]);
        return redirect()->route('admin.tags.index')->with('status','Tag created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
    abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
    return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:100','unique:tags,name,'.$tag->id],
        ]);
        if ($validated['name'] !== $tag->name) {
            $slugBase = Str::slug($validated['name']);
            $slug = $slugBase; $i=1;
            while (Tag::where('slug',$slug)->where('id','!=',$tag->id)->exists()) { $slug = $slugBase.'-'.$i++; }
            $tag->slug = $slug;
        }
        $tag->name = $validated['name'];
        $tag->save();
        return redirect()->route('admin.tags.index')->with('status','Tag updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
    $tag->delete();
    return back()->with('status','Tag deleted');
    }
}
