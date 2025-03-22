<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Proprieties',
            'Vehicles',
            'Job_Opportunities',
            'Fashion_and_Clothing',
            'Furniture_and_Decor',
            'Electronics',
            'Entertainment',
            'Commercial_Ads',
            'Animals_and_Agriculture',
            'Miscellaneous',
            'Services',
            'Construction_and_Industry',
            'Germany',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category], ['name' => $category]);
        }
    }
}
