<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return Profile::with('user', 'category')->get();
    }

    public function show($id)
    {
        return Profile::with('user', 'category')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
        ]);
        return Profile::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $profile = Profile::findOrFail($id);
        $profile->update($request->all());
        return $profile;
    }

    public function destroy($id)
    {
        Profile::findOrFail($id)->delete();
        return response()->noContent();
    }
}
