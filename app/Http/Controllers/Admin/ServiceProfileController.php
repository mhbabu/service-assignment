<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Profile\ProfileListDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Profile;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceProfileController extends Controller
{
    public function index(ProfileListDataTable $profileListDataTable)
    {
        return $profileListDataTable->render('admin.service-profile.index');
    }

    public function create()
    {
        $data['categories'] = Category::where('status', 1)->pluck('name', 'id')->map(function ($name) {
            return ucfirst($name);
        });
        return view("admin.service-profile.create", $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => [ 'required', Rule::unique('profiles')->where(function ($query) {
                return $query->where('user_id', auth()->id());
            }),
        ],
            'status' => 'required'
        ],[],[
            'category_id' => 'category profile'
        ]);

        Profile::create([
            'category_id' => $request->input('category_id'),
            'status'      => $request->input('status'),
            'user_id'     => auth()->id()
        ]);
        Toastr::success('Profile created successfully.');
        return redirect()->route('service-profiles.index');
    }

    public function edit(Profile $service_profile)
    {
        $data['profile']    = $service_profile;
        $data['categories'] = Category::where('status', 1)->pluck('name', 'id')->map(function ($name) {
            return ucfirst($name);
        });
        return view('admin.service-profile.edit', $data);
    }

    public function update(Request $request, Profile $service_profile)
    {
        $request->validate([
            'category_id' => ['required', Rule::unique('profiles')->where(function ($query) use ($service_profile) {
                    return $query->where('user_id', auth()->id())->where('id', '<>', $service_profile->id);
                }),
            ],
            'status' => 'required'
        ], [], [
            'category_id' => 'category profile'
        ]);

        $service_profile->update([
            'category_id' => $request->input('category_id'),
            'status'      => $request->input('status'),
            'user_id'     => auth()->id()
        ]);

        Toastr::success('Profile updated successfully.');
        return redirect()->route('service-profiles.index');
    }

    public function delete(Profile $service_profile)
    {
        $service_profile->delete();
        Toastr::success('Profile deleted successfully.');
        return back();
    }
}
