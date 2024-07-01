<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DateOverrideResource;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WeeklyAvailabilityResource;
use App\Models\DateOverride;
use App\Models\Profile;
use App\Models\User;
use App\Models\WeeklyAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpFoundation\Response;

class EndPoinController extends Controller
{
    public function setWeeklyAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [ // we can also user here Laravel Request file to check validation separatly
            'profile_id'    => [
                'required', Rule::exists('profiles', 'id')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })
            ],
            'day_of_week'   => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'    => ['required', 'regex:/^([01]?[0-9]|2[0-3]):([0-5]?[0-9])(:[0-5]?[0-9])?$/'],
            'end_time'      => ['required', 'regex:/^([01]?[0-9]|2[0-3]):([0-5]?[0-9])(:[0-5]?[0-9])?$/'],
        ], [], [
            'profile_id' => 'profile'
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

    public function setOverrideAvailability(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'profile_id' => [
                'required', Rule::exists('profiles', 'id')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })
            ],
            'date'   => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ], [], [
            'profile_id' => 'profile'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['error' => $errors], 422);
        }


        $dateOverride = DateOverride::updateOrCreate(
            [
                'profile_id' => $request->input('profile_id'),
                'date'       => $request->input('date')
            ]
        );

        return response(['data' => new DateOverrideResource($dateOverride)], Response::HTTP_OK);
    }

    public function getAvailability($userId)
    {
        $buyerTimezone = Location::get('https://'.request()->ip())->timezone;
        $user          = User::where('id', $userId)->first();
        $userProfiles  = Profile::where(['user_id' => $userId, 'timezone' => $buyerTimezone])->get();

        if(empty($user)){
            return response()->json(['error' => 'Resource not found'], 404);
        }

        return response()->json([
            'user'     => new UserResource($user), 
            'profiles' => UserProfileResource::collection($userProfiles)
        ], 200);
    }
}
