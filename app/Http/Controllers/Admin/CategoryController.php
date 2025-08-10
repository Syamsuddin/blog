<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('posts')->orderBy('name')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => ['required','string','max:100','unique:categories,name'],
        ]);
        $slugBase = Str::slug($validated['name']);
        $slug = $slugBase; $i=1;
        while (Category::where('slug',$slug)->exists()) { $slug = $slugBase.'-'.$i++; }
        Category::create(['name'=>$validated['name'],'slug'=>$slug]);
        return redirect()->route('admin.categories.index')->with('status','Category created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
        $validated = $request->validate([
            'name' => ['required','string','max:100','unique:categories,name,'.$category->id],
        ]);
        if ($validated['name'] !== $category->name) {
            $slugBase = Str::slug($validated['name']);
            $slug = $slugBase; $i=1;
            while (Category::where('slug',$slug)->where('id','!=',$category->id)->exists()) { $slug = $slugBase.'-'.$i++; }
            $category->slug = $slug;
        }
        $category->name = $validated['name'];
        $category->save();
        return redirect()->route('admin.categories.index')->with('status','Category updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    $category->delete();
    return back()->with('status','Category deleted');
    }
}
