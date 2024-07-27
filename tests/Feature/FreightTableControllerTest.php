<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\FreightTable;

class FreightTableControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_fetch_all_freight_tables()
    {
        FreightTable::factory()->count(5)->create();

        $response = $this->getJson('/api/freight-tables');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }

    public function test_it_can_fetch_a_single_freight_table_by_id()
    {
        $freightTable = FreightTable::factory()->create();

        $response = $this->getJson("/api/freight-tables/{$freightTable->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $freightTable->id,
            'from_postcode' => $freightTable->from_postcode,
            'to_postcode' => $freightTable->to_postcode,
            'from_weight' => $freightTable->from_weight,
            'to_weight' => $freightTable->to_weight,
            'cost' => $freightTable->cost,
            'branch_id' => $freightTable->branch_id,
        ]);
    }
}
