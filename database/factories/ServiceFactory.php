<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'duration_minutes' => $this->faker->numberBetween(15, 180),
            'price' => $this->faker->randomFloat(2, 0, 500),
            'description' => $this->faker->optional()->paragraph(),
            // provider_id se suele pasar desde el seeder; no lo forzamos aquí
        ];
    }
}
