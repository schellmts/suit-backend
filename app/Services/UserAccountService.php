<?php
namespace App\Services;

use App\Models\Account;
use App\Models\UserAccount;

class UserAccountService
{
    public function validateQuantityLicense(int $accountId): bool
    {
        $account = Account::findOrFail($accountId);
        $license = $account->subscriptions()
            ->with('items')
            ->get()
            ->flatMap->items
            ->firstWhere('stripe_product', env('LICENSE_STRIPE_ID'));

        $usersCount = UserAccount::where('account_id', $accountId)
            ->where('user_id', '!=', $accountId) // Ignora o dono da conta
            ->whereHas('user.networks', function ($query) use ($account) {
                $query->where('network_id', $account->network_id)
                    ->where('type', 'contributor');
            })
            ->count();

        return $usersCount < $license->quantity;
    }

}
