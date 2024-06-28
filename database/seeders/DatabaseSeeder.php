<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DateOverride;
use App\Models\Profile;
use App\Models\User;
use App\Models\WeeklyAvailability;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create()->each(function ($user) {
            $profiles = Profile::factory(3)->create(['user_id' => $user->id]);

            $profiles->each(function ($profile) {
                WeeklyAvailability::factory(3)->create(['profile_id' => $profile->id]);
                DateOverride::factory(2)->create(['profile_id' => $profile->id]);
            });

        });

        Category::factory(5)->create();
    }
}
