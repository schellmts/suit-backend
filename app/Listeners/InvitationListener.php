<?php

namespace App\Listeners;

use App\Events\InvitationEvent;
use App\Mail\InvitationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class InvitationListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InvitationEvent $event): void
    {
        Mail::to($event->invitation->email)->send(new InvitationMail($event->invitation));
    }

    /**
    * Handle a job failure.
    */
    public function failed(InvitationEvent $event, Throwable $exception): void
    {
        Log::warning('InvitationListener: ' . now() . ' - Failed: ' . $event . ' exception: ' . $exception->getMessage());
    }
}
