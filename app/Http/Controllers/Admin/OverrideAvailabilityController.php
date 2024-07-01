<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Profile\ProfileUnavailabilityDataTable;
use App\Http\Controllers\Controller;
use App\Models\DateOverride;
use App\Models\Profile;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OverrideAvailabilityController extends Controller
{
    public function index(){
        $user     = auth()->user();
        $profiles = Profile::where('status', 1);
        if (!$user->is_admin) {
            $profiles->where('user_id', $user->id);
        }

        $data['profiles'] =  $profiles->pluck('title', 'id');

        return view('admin.override-availability.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'profile_id'    => 'required|exists:profiles,id',
            'date.*'        => 'required|date_format:Y-m-d|after_or_equal:today'
        ]);

        $data = $request->all();
        foreach ($data['date'] as $index => $date) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');

            DateOverride::updateOrCreate(
                [
                    'profile_id' => $data['profile_id'],
                    'date'       => $formattedDate
                ]
            );
        }

        Toastr::success('Seller weekly over-rided availability set successfully.');
        return redirect()->route('override-availabilites.show', $data['profile_id']);
    }

    public function show(ProfileUnavailabilityDataTable $profileUnavailabilityDataTable, $profileId){
        $data['profile']      = Profile::find($profileId);
        $params['profile_id'] = $profileId;
        return $profileUnavailabilityDataTable->with($params)->render('admin.override-availability.detail', $data);
    }
}
