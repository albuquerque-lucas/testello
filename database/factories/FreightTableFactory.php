<?php

namespace Database\Factories;

use App\Models\FreightTable;
use Illuminate\Database\Eloquent\Factories\Factory;

class FreightTableFactory extends Factory
{
    protected $model = FreightTable::class;

    public function definition()
    {
        return [
            'branch_id' => $this->faker->numberBetween(1, 10),
            'customer_id' => $this->faker->optional()->numberBetween(1, 10),
            'from_postcode' => $this->faker->postcode,
            'to_postcode' => $this->faker->postcode,
            'from_weight' => $this->faker->randomFloat(2, 0, 100),
            'to_weight' => $this->faker->randomFloat(2, 0, 100),
            'cost' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
