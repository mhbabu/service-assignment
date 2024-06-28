<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Category\CategoryListDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(CategoryListDataTable $categoryListDataTable)
    {
        return $categoryListDataTable->render('category.index');
    }

    public function create()
    {
        return view("pages.setting.leave-type.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|unique:categories,name|max:191',
            'status' => 'required'
        ]);

        Category::create($request->all());
        Toastr::success('Category created successfully.');
        return redirect()->route('leave-type-settings.index');
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
