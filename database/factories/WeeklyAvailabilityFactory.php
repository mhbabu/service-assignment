<?php

namespace Database\Factories;

use App\Models\WeeklyAvailability;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WeeklyAvailability>
 */
class WeeklyAvailabilityFactory extends Factory
{
    protected $model = WeeklyAvailability::class;

    public function definition()
    {
        return [
            'profile_id'  => \App\Models\Profile::factory(),
            'day_of_week' => $this->faker->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']),
            'start_time'  => $this->faker->time($format = 'H:i:s', $max = 'now'),
            'end_time'    => $this->faker->time($format = 'H:i:s', $max = 'now'),
        ];
    }
}
