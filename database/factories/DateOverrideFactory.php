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

        return [
            'profile_id' => Profile::factory(),
            'date'       => $date
        ];
    }
}
