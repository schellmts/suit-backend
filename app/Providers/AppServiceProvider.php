<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\User;
use App\Models\UserNetwork;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Cashier\Cashier;
use App\Models\Ticket;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::useCustomerModel(Account::class);

        RedirectIfAuthenticated::redirectUsing(function() {
            return "auth";
        });

        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        Passport::tokensCan(UserNetwork::$TYPES);

        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return env('APP_URL_FRONT') . '/authentication/reset-password?token=' . $token . '&email=' . $user->email;
        });

        VerifyEmail::createUrlUsing(function (object $notifiable) {
            return env('APP_URL_FRONT') . '\/authentication/login/' . $notifiable->getKey() .'\/'. sha1($notifiable->getEmailForVerification());
        });

        Ticket::observe(\App\Observers\TicketObserver::class);
    }
}
