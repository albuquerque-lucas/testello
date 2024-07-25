<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;

class MigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customers_table_has_expected_columns()
    {
        $columns = Schema::getColumnListing('customers');
        $this->assertEqualsCanonicalizing([
            'id',
            'name',
            'created_at',
            'updated_at',
        ], $columns);
    }

    public function test_freight_tables_table_has_expected_columns()
    {
        $columns = Schema::getColumnListing('freight_tables');
        $this->assertEqualsCanonicalizing([
            'id',
            'customer_id',
            'branch_id',
            'from_postcode',
            'to_postcode',
            'from_weight',
            'to_weight',
            'cost',
            'created_at',
            'updated_at',
        ], $columns);
    }

    public function test_branches_table_has_expected_columns()
    {
        $columns = Schema::getColumnListing('branches');
        $this->assertEqualsCanonicalizing([
            'id',
            'name',
            'location',
            'created_at',
            'updated_at',
        ], $columns);
    }
}
