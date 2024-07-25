<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\Branch;
use Database\Seeders\BranchSeeder;
use Database\Seeders\CustomerSeeder;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_branch_seeder()
    {
        $this->seed(BranchSeeder::class);

        $this->assertCount(2, Branch::all());
    }

    public function test_customer_seeder()
    {
        $this->seed(CustomerSeeder::class);

        $this->assertCount(15, Customer::all());
    }

    public function test_database_seeder()
    {
        $this->seed();

        $this->assertCount(2, Branch::all());
        $this->assertCount(15, Customer::all());
    }
}
