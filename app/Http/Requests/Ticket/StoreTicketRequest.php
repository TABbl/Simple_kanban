<?php

namespace App\Http\Requests\Ticket;

use App\Enums\TicketStatus;
use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreTicketRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'body' => ['nullable', 'string'],
            'status' => ['required', new Enum(TicketStatus::class)],
            'finish_at' => ['nullable', 'date', 'after_or_equal:now'],
        ];
    }
}
