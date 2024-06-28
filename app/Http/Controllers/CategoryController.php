<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

    public function show($id)
    {
        return Category::findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'status' => 'required']);
        return Category::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return $category;
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return response()->noContent();
    }
}
