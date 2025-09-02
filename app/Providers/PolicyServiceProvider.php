<?php

namespace App\Providers;

use App\Models\TicketMovement;
use App\Policies\GroupingSkillPolicy;
use App\Policies\TicketMovementPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PolicyServiceProvider extends ServiceProvider
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
        Gate::policy('grouping-skill', GroupingSkillPolicy::class);
        Gate::policy(TicketMovement::class, TicketMovementPolicy::class);
    }
}
