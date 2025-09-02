<?php

namespace App\Services;

use Carbon\Carbon;

class SlaService
{
    public function calculateDueDate(int $priority): ?Carbon
    {
        $hours = config("sla.priorities.{$priority}.finish");
        return $hours ? Carbon::now()->addHours($hours) : null;
    }

    public function calculateFirstInteractionDate(int $priority): ?Carbon
    {
        $hours = config("sla.priorities.{$priority}.first_interaction");
        return $hours ? Carbon::now()->addHours($hours) : null;
    }
}
