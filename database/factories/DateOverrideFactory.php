<?php

namespace Database\Factories;

use App\Models\DateOverride;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DateOverride>
 */
class DateOverrideFactory extends Factory
{
    protected $model = DateOverride::class;

    public function definition()
    {
        $date = $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d');

        $startTime1 = $this->faker->time($format = 'H:i:s', $max = '12:00:00');
        $endTime1   = $this->faker->time($format = 'H:i:s', $max = '12:00:00');

        // Ensure valid time interval
        if (strtotime($startTime1) > strtotime($endTime1)) {
            $endTime1 = date('H:i:s', strtotime($startTime1) + 3600); // Add 1 hour to start time
        }

        return [
            'profile_id'    => Profile::factory(),
            'date'          => $date,
            'start_time'    => $this->faker->boolean(80) ? $startTime1 : null,
            'end_time'      => $this->faker->boolean(80) ? $endTime1 : null
        ];
    }
}
