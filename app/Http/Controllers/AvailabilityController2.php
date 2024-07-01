<?php

namespace App\Http\Controllers;

use App\Models\WeeklyAvailability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function setWeeklyAvailability(Request $request)
    {
        dd($request->all());
        $request->validate([
            'profile_id'     => 'required|exists:profiles,id',
            'availabilities' => 'required|array',
            'availabilities.*.day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'availabilities.*.start_time' => 'required|date_format:H:i',
            'availabilities.*.end_time' => 'required|date_format:H:i|after:availabilities.*.start_time',
        ]);

        foreach ($request->availabilities as $availability) {
            WeeklyAvailability::updateOrCreate(
                ['profile_id' => $request->profile_id, 'day_of_week' => $availability['day_of_week']],
                ['start_time' => $availability['start_time'], 'end_time' => $availability['end_time']]
            );
        }

        return response()->json(['message' => 'Weekly availability set'], 200);
    }
}
