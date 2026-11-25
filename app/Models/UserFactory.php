<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'role' => $this->faker->randomElement(['admin', 'gudang', 'produksi']),
            'division_id' => \App\Models\Division::factory(),
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'admin',
                'email' => 'admin@determinedstudio.com',
            ];
        });
    }

    public function gudang()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'gudang',
                'email' => 'gudang@determinedstudio.com',
            ];
        });
    }

    public function produksi()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'produksi',
                'email' => 'produksi@determinedstudio.com',
            ];
        });
    }
}