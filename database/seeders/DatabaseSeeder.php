<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Network;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAccount;
use App\Models\UserNetwork;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!DB::table('oauth_clients')->where('name', 'Personal Access Client')->exists()) {
            $this->call(OauthClientSeeder::class);
        }

        $this->call(PermissionSeeder::class);

        $network = Network::factory()->create([
            'name' => 'Test Network'
        ]);

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@test.com',
        ]);

        UserNetwork::create([
            'user_id' => $user->id,
            'network_id' => $network->id,
            'type' => UserNetwork::OWNER,
        ]);

        $account = Account::factory()->create([
            'name' => 'Test Account',
            'network_id' => $network->id,
        ]);

        UserAccount::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'role_id' => $account->roles()->first('name', '=', 'host')->id,
            'add_by_user_id' => $user->id,
            'removed_by_user_id' => null
        ]);
    }
}
