<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\WeeklyAvailability;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index()
    {

        $user     = auth()->user();
        $profiles = Profile::where('status', 1);
        if (!$user->is_admin) {
            $profiles->where('user_id', $user->id);
        }

        $data['profiles'] =  $profiles->pluck('title', 'id');
        return view('admin.availability.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'profile_id'    => 'required|exists:profiles,id',
            'day_of_week.*' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'    => 'required|array',
            'start_time.*'  => ['required', 'regex:/^([01]?[0-9]|2[0-3]):([0-5]?[0-9])(:[0-5]?[0-9])?$/'],
            'end_time'      => 'required|array',
            'end_time.*'    => ['required', 'regex:/^([01]?[0-9]|2[0-3]):([0-5]?[0-9])(:[0-5]?[0-9])?$/'],
        ]);

        $data = $request->all();

        foreach ($data['day_of_week'] as $index => $weekDay) {
            $startTime = Carbon::createFromFormat('H:i', $data['start_time'][$index])->format('H:i:s');
            $endTime   = Carbon::createFromFormat('H:i', $data['end_time'][$index])->format('H:i:s');

            info($startTime);
            info($endTime);

            WeeklyAvailability::updateOrCreate(
                [
                    'profile_id' => $data['profile_id'],
                    'day_of_week' => $weekDay
                ],
                [
                    'start_time' => $startTime,
                    'end_time'   => $endTime
                ]
            );
        }


        Toastr::success('Seller weekly availability created successfully.');
        return back();
    }
}
