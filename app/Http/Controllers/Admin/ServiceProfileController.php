<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Category\CategoryListDataTable;
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

    public function edit(LeaveType $leave_type_setting)
    {
        $data['leaveType'] = $leave_type_setting;
        return view('pages.setting.leave-type.edit', $data);
    }

    public function update(Request $request, LeaveType $leave_type_setting)
    {
        $request->validate([
            'name'   => ['required', 'max:191', Rule::unique('leave_types')->ignore($leave_type_setting->id)],
            'status' => 'required'
        ]);

        $leave_type_setting->update([
            'name'            => $request->name,
            'leave_status'    => $request->leave_status ?? null,
            'number_of_leave' => $request->number_of_leave ?? 0,
            'montly_limit'    => $request->montly_limit ?? 0,
            'status'          => $request->status
        ]);

        Toastr::success('Leave type updated successfully.');
        return redirect()->route('leave-type-settings.index');
    }

    public function delete(LeaveType $leave_type_setting)
    {
        $leave_type_setting->delete();
        Toastr::success('Leave Type deleted successfully.');
        return redirect()->route('leave-type-settings.index');
    }
}
