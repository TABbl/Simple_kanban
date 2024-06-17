<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence,
            'body' => fake()->text,
            #todo не знаю правильно ли так делать
            'status' => fake()->randomElement([TicketStatus::ToBeDone, TicketStatus::AtWork, TicketStatus::Done]),
            'finish_at' => fake()->dateTime(),
        ];
    }
}
