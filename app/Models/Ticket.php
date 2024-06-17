<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'status',
        'finish_at',
    ];

    protected $casts = [
        'status' => TicketStatus::class
    ];

    public function userticket(): HasMany{
        return $this->hasMany(UserTicket::class);
    }
}
