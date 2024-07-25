<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customers_table_has_expected_columns()
    {
        $this->assertTrue(
            \Schema::hasColumns('customers', [
                'id', 'name', 'created_at', 'updated_at'
            ]),
            1
        );
    }

    public function test_freight_tables_table_has_expected_columns()
    {
        $this->assertTrue(
            \Schema::hasColumns('freight_tables', [
                'id', 'customer_id', 'from_postcode', 'to_postcode', 'from_weight', 'to_weight', 'cost', 'branch_id', 'created_at', 'updated_at'
            ]),
            1
        );
    }
}
