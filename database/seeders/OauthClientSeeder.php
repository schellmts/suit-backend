<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OauthClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('oauth_clients')->insert([
            'id' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'),
            'user_id' => null,
            'name' => 'Personal Access Client',
            'secret' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET'), // * Alterar conforme necessário
            'redirect' => env('APP_URL'), // * Alterar conforme necessário
            'personal_access_client' => true,
            'password_client' => false,
            'revoked' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
