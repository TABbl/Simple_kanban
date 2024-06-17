<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_id',
        'role',
    ];

    protected $casts = [
        'role' => UserRole::class
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function ticket(): BelongsTo{
        return $this->belongsTo(Ticket::class);
    }
}
