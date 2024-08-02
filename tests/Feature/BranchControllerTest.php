<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Branch;

class BranchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_fetch_all_branches()
    {
        Branch::factory()->count(13)->create();

        $response = $this->getJson('/api/branches');

        $response->assertStatus(200);
        $response->assertJsonCount(13);
    }

    public function test_it_can_fetch_a_single_branch()
    {
        $branch = Branch::factory()->create();

        $response = $this->getJson("/api/branches/{$branch->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $branch->id,
            'name' => $branch->name,
            'location' => $branch->location,
        ]);
    }

    public function test_it_can_create_a_branch()
    {
        $data = ['name' => 'Nova Filial', 'location' => 'Localização X'];

        $response = $this->postJson('/api/branches', $data);

        $response->assertStatus(201);
        $response->assertJson($data);
        $this->assertDatabaseHas('branches', $data);
    }

    public function test_it_can_update_a_branch()
    {
        $branch = Branch::factory()->create();
        $data = ['name' => 'Filial Atualizada', 'location' => 'Localização Y'];

        $response = $this->putJson("/api/branches/{$branch->id}", $data);

        $response->assertStatus(200);
        $response->assertJson($data);
        $this->assertDatabaseHas('branches', $data);
    }

    public function test_it_can_delete_a_branch()
    {
        $branch = Branch::factory()->create();

        $response = $this->deleteJson("/api/branches/{$branch->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('branches', ['id' => $branch->id]);
    }
}
