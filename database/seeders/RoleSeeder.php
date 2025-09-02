<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'host',
                'description' => 'Host',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'maintainer',
                'description' => 'Maintainer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'common',
                'description' => 'Common',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        foreach ($roles as $role) {
            Role::insert(
                $role
            );
        }
    }
}
