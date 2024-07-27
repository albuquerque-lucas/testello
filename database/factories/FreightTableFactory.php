<?php

namespace Database\Factories;

use App\Models\FreightTable;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class FreightTableFactory extends Factory
{
    protected $model = FreightTable::class;

    public function definition()
    {
        return [
            'branch_id' => Branch::factory(),
            'customer_id' => null,
            'from_postcode' => $this->faker->postcode,
            'to_postcode' => $this->faker->postcode,
            'from_weight' => $this->faker->randomFloat(2, 0, 100),
            'to_weight' => $this->faker->randomFloat(2, 0, 100),
            'cost' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
