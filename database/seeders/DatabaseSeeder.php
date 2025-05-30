<?php

namespace Database\Seeders;

use App\Models\Cv;
use App\Models\CvDocument;
use App\Models\CvExperience;
use App\Models\CvFile;
use App\Models\CvLanguage;
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


        /*  $this->call(RoleSeeder::class);
          $this->call(PermissionSeeder::class);
          $this->call(CategorySeeder::class);
          $this->call(PlanSeeder::class);*/
        User::factory(3)->create();

        User::factory()
            ->has(Cv::factory()
                ->has(CvFile::factory(), 'file')
                ->has(CvDocument::factory(), 'document')
                ->has(CvLink::factory(3), 'link')
                ->has(CvExperience::factory(3), 'experience')
                ->has(CvQualification::factory(3), 'qualification')
                //->has(CvLanguage::factory(3)) لا يوجد
                ->has(CvSkill::factory(3), 'skill'))
            ->has(Advertisement::factory(2)
                ->has(AdvertisementAttribute::factory(10), 'attributes')
                ->has(Image::factory(3), 'images')
                ->has(View::factory(20), 'views'), 'ads')
            ->has(
                Rate::factory()
                    ->count(5)
                    ->state(function (array $attributes, User $user) {
                        $otherUsers = User::where('id', '!=', $user->id)->pluck('id')->toArray();
                        return [
                            'rated_user_id' => fake()->randomElement($otherUsers),
                        ];
                    }),
                'rated'
            )
            ->has(Report::factory(3))
            ->count(1)
            ->create();

        /* User::factory()->isHaidar()
             ->has(Cv::factory()
                 ->has(CvFile::factory(), 'file')
                 ->has(CvDocument::factory(), 'document')
                 ->has(CvLink::factory(3), 'link')
                 ->has(CvExperience::factory(3), 'experience')
                 ->has(CvQualification::factory(3), 'qualification')
                 ->has(CvSkill::factory(3), 'skill'))
             ->has(Advertisement::factory(2)
                 ->has(AdvertisementAttribute::factory(10), 'attributes')
                 ->has(Image::factory(3), 'images')
                 ->has(View::factory(20), 'views'), 'ads')
             ->has(Rate::factory()
                 ->count(5)
                 ->state(function (array $attributes, User $user) {
                     $otherUsers = User::where('id', '!=', $user->id)->pluck('id')->toArray();
                     return [
                         'rated_user_id' => fake()->randomElement($otherUsers),
                     ];
                 }))
             ->create();*/


        /*               
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
        ]);*/

    }
}
