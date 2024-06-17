<?php

namespace App\Enums;

enum TicketStatus: string{
    case ToBeDone = 'to be done';
    case AtWork = 'at work';
    case Done = 'done';
}