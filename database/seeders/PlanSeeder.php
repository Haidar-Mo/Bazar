<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::updateOrCreate(
            ['name', 'starter'],
            [
                'name' => 'starter',
                'duration' => 30,
                'size' => 5,
                'price' => 0,
                'details' => "This is the starter Plan, User can use it for 30 days after creating his account, consuming only 5 ads",
                'is_special' => false

            ]
        );
    }
}
