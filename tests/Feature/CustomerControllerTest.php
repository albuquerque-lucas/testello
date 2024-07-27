<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_fetch_all_customers()
    {
        Customer::factory()->count(5)->create();

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    public function test_it_can_fetch_a_single_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/customers/{$customer->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $customer->id,
            'name' => $customer->name,
        ]);
    }

    public function test_it_can_create_a_customer()
    {
        $data = ['name' => 'Novo Cliente'];

        $response = $this->postJson('/api/customers', $data);

        $response->assertStatus(201);
        $response->assertJson($data);
        $this->assertDatabaseHas('customers', $data);
    }

    public function test_it_can_update_a_customer()
    {
        $customer = Customer::factory()->create();
        $data = ['name' => 'Cliente Atualizado'];

        $response = $this->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(200);
        $response->assertJson($data);
        $this->assertDatabaseHas('customers', $data);
    }

    public function test_it_can_delete_a_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/customers/{$customer->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}
