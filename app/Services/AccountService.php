<?php
namespace App\Services;

use App\Models\Account;
use App\Models\Network;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AccountService
{
    function create($values, Network $network) {

        DB::beginTransaction();

        try {
            $account = new Account();
            $account->fill($values);
            $account->network_id = $network->id;
            $account->status = Account::AUTHORIZED;
            $account->active = Account::ACTIVE;
            $account->save();

            $role = Role::where('name' , '=', 'host')->firstOrFail();

            foreach($account->owners()->get() as $user){
                $user->accounts()->attach($account->id, [
                    'role_id' => $role->id,
                    'add_by_user_id' => $user->id,
                    'removed_by_user_id' => null
                ]);
            }

            DB::commit();

            return $account;
        }catch(\Exception $e){
            DB::rollBack();
            logger()->error('Create account error: ' . $e->getMessage());
            return response()->json(['message' => 'Create account error: ' . $e->getMessage()], 500);
        }
    }

    function update(Account $account, $values){
        $account->name = $values->name;
        $account->name = $values->description;
        $account->update();
        return $account;
    }
}
