<?php

namespace Database\Seeders;

use App\Models\CV;
use App\Models\CVDocument;
use App\Models\CVLink;
use App\Models\User;
use App\Models\City;
use App\Models\Category;
use App\Models\Advertisement;
use App\Models\AdvertisementAttribute;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\View;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        
        //! User::factory(10)->create();  // do not use it because the CV factory will create user

        // CV::factory(10)->create(); 
        // CVDocument::factory(10)->create();
        // CVLink::factory(20)->create();
        // City::factory(7)->create();
        // Category::factory(7)->create();
        // Advertisement::factory(20)->create();
        // AdvertisementAttribute::factory(150)->create();
        // Image::factory(80)->create();
        // View::factory(100)->create();


    }
}
