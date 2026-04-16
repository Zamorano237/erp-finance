<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->bothify('SUP-####')),
            'name' => fake()->company(),
            'category' => fake()->randomElement([
                'Prestations externes',
                'Informatique & digital',
                'Immobilier & exploitation',
                'Fournitures & fonctionnement',
                'Frais financiers & assurances',
                'Obligations réglementaires',
            ]),
        ];
    }
}