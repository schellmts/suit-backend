<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\CostCenter;
use App\Models\Customer;
use App\Models\Grouping;
use App\Models\JuridicalPerson;
use App\Models\Network;
use App\Models\PhysicalPerson;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Supplier;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // ** ROUTE BINDS
        Route::bind('network', function ($value) {
            // dd("asdasd");
            return Network::where('id', $value)->firstOrFail();
        });
        Route::bind('account', function ($value, $request) {
            return Account::where('id', $value)->where('network_id', $request->network->id)->firstOrFail();
        });
        Route::bind('customer', function ($value, $request) {
            return Customer::where('id', $value)->where('account_id', $request->account->id)->firstOrFail();
        });
        Route::bind('supplier', function ($value, $request) {
            return Supplier::where('id', $value)->where('account_id', $request->account->id)->firstOrFail();
        });
        Route::bind('grouping', function ($value, $request) {
            return Grouping::where('id', $value)->where('account_id', $request->account->id)->firstOrFail();
        });
        Route::bind('skill', function ($value, $request) {
            return Skill::where('id', $value)->where('account_id', $request->account->id)->firstOrFail();
        });
        Route::bind('physicalPerson', function ($value, $request) {
            return PhysicalPerson::where('id', $value)->where('account_id', $request->account->id)->firstOrFail();
        });
        Route::bind('juridicalPerson', function ($value, $request) {
            return JuridicalPerson::where('id', $value)->where('account_id', $request->account->id)->firstOrFail();
        });
        Route::bind('cost_center', function ($value, $request) {
            return CostCenter::where('id', $value)->where('network_id', $request->network->id)->firstOrFail();
        });
        Route::bind('project', function ($value, $request) {
            return Project::where('id', $value)->where('account_id', $request->account->id)->firstOrFail();
        });
    }
}
