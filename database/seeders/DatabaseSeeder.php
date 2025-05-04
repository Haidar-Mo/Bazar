<?php

namespace Database\Seeders;

use App\Models\Cv;
use App\Models\CvDocument;
use App\Models\CvExperience;
use App\Models\CvFile;
use App\Models\CvLink;
use App\Models\CvQualification;
use App\Models\CvSkill;
use App\Models\Image;
use App\Models\Rate;
use App\Models\Report;
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
        //Report::factory(20)->create();


        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(PlanSeeder::class);

        /*
                User::factory(10)->create();
                Cv::factory(10)->create();
                CvFile::factory(10);
                CvDocument::factory(20)->create();
                CvLink::factory(20)->create();
                CvExperience::factory(20)->create();
                CvQualification::factory(20)->create();
                CvSkill::factory(20)->create();
                City::factory(7)->create();
                Advertisement::factory(20)->create();
                AdvertisementAttribute::factory(150)->create();
                Image::factory(80)->create();
                View::factory(100)->create();
                Rate::factory(100)->create();
        User::create([
            'first_name' => 'mario',
            'last_name' => 'andrawos',
            'email' => 'example@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '0937723418',
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'job' => 'IT job',
            'company' => 'Androws Company',
            'description' => 'a junior Laravel developer',
            'address' => 'yabroud',
            'provider' => null,
            'provider_id' => null,
            'device_token' => 'k',
            'email_verified_at' => now(),
        ]);
        User::create([
            'first_name' => 'haider',
            'last_name' => 'haider',
            'email' => 'haider@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '0937723418',
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'job' => 'IT job',
            'company' => 'Androws Company',
            'description' => 'a junior Laravel developer',
            'address' => 'yabroud',
            'provider' => null,
            'provider_id' => null,
            'device_token' => 'k',
            'email_verified_at' => now(),
        ]);*/





    }
}
