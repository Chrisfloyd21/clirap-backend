<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'subject' => $this->faker->sentence(3),
            'message' => $this->faker->paragraph(),
            'is_read' => $this->faker->boolean(10), // 10% de chance d'être déjà lu
        ];
    }
}