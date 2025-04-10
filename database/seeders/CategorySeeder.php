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
            'عقارات',
            'مركبات',
            'فرص عمل',
            'ازياء و موضة',
            'اثاث و ديكور',
            'الكترونيات',
            'التسلية',
            'اعلانات تجارية و متفرقات',
            'الحيوانات و الزراعة',
            'خدمات',
            'الصناعة و البناء',
            'المانيا',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category], ['name' => $category]);
        }
    }
}
