<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [
            'create ads',
            'update ads',
            'delete ads',
            'active ads',
            'de-active ads',
            'block user',
            'unblock user',
            'subscribe user',
            'unsubscribe user',
            'change role',
            'send notification',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['guard_name' => 'api']
            );
        }
    }
}
