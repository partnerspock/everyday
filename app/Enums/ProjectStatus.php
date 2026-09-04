<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case NOT_STARTED = 'not_started';
    case IN_PROGRESS = 'in_progress';
    case HOLD = 'hold';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match($this) {
            self::NOT_STARTED => 'Not Started',
            self::IN_PROGRESS => 'In Progress',
            self::HOLD => 'Hold',
            self::COMPLETED => 'Completed',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::NOT_STARTED => 'warning',  
            self::IN_PROGRESS => 'primary',     
            self::HOLD => 'danger',  
            self::COMPLETED => 'success',  
        };
    }
}