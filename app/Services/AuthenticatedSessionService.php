<?php
namespace App\Services;

use App\Models\Account;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionService
{
    function authPersonalAccess($credentials): string
    {
        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();

            $user->tokens()
                ->where('client_id', env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'))
                ->where('name', 'web')->delete();

            return $user->createToken('web')->accessToken;
        } else {
            abort(401, 'Unauthorized');
        }
    }

    function removePersonalAccess()
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $user->tokens()
                ->where('client_id', env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'))
                ->where('name', 'web')->delete();
            Cookie::queue(Cookie::forget('web'));
        }
    }
}
