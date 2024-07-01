<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WeeklyAvailabilityResource;
use App\Models\WeeklyAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class EndPoinController extends Controller
{
    public function setWeeklyAvailability(Request $request){

        $validator = Validator::make($request->all(),[ // we can also user here Laravel Request file to check validation separatly
           'profile_id'    => [ 'required', Rule::exists('profiles', 'id')->where(function ($query) {
                return $query->where('user_id', auth()->id());
            })
        ],
            'day_of_week'   => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'    => ['required', 'regex:/^([01]?[0-9]|2[0-3]):([0-5]?[0-9])(:[0-5]?[0-9])?$/'],
            'end_time'      => ['required', 'regex:/^([01]?[0-9]|2[0-3]):([0-5]?[0-9])(:[0-5]?[0-9])?$/'],
        ]);

        if ($validator->fails()) { 
            $errors = $validator->errors(); 
            return response()->json(['error' => $errors], 422);    
        } 

        $startTime = Carbon::createFromFormat('H:i', $request->input('start_time'))->format('H:i:s');
        $endTime   = Carbon::createFromFormat('H:i', $request->input('end_time'))->format('H:i:s');

        $weeklyAvailability = WeeklyAvailability::updateOrCreate(
            [
                'profile_id' => $request->input('profile_id'),
                'day_of_week' => $request->input('day_of_week')
            ],
            [
                'start_time' => $startTime,
                'end_time'   => $endTime
            ]
        );

        return response(['data' => new WeeklyAvailabilityResource($weeklyAvailability)], Response::HTTP_OK);
    }
}
