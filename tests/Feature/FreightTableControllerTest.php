<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\FreightTable;
use App\Models\Branch;
use App\Jobs\ProcessFreightTableCsv;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;

class FreightTableControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_fetch_all_freight_tables()
    {
        Branch::factory()->count(10)->create();
        FreightTable::factory()->count(5)->create();

        $response = $this->getJson('/api/freight-tables');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }

    public function test_it_can_fetch_a_single_freight_table_by_id()
    {
        Branch::factory()->count(10)->create();
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

    public function test_it_can_create_a_freight_table()
    {
        $branch = Branch::factory()->create();
        $data = [
            'branch_id' => $branch->id,
            'from_postcode' => '12345',
            'to_postcode' => '54321',
            'from_weight' => '0.00',
            'to_weight' => '10.00',
            'cost' => '50.00',
        ];

        $response = $this->postJson('/api/freight-tables', $data);

        $response->assertStatus(201);
        $response->assertJson($data);
        $this->assertDatabaseHas('freight_tables', $data);
    }

    public function test_it_can_update_a_freight_table()
    {
        $branch = Branch::factory()->create();
        $freightTable = FreightTable::factory()->create();
        $data = [
            'branch_id' => $branch->id,
            'from_postcode' => '11111',
            'to_postcode' => '99999',
            'from_weight' => '1.00',
            'to_weight' => '5.00',
            'cost' => '20.00',
        ];

        $response = $this->putJson("/api/freight-tables/{$freightTable->id}", $data);

        $response->assertStatus(200);
        $response->assertJson($data);
        $this->assertDatabaseHas('freight_tables', $data);
    }

    public function test_it_can_delete_freight_tables()
    {
        $branch = Branch::factory()->create();
        $freightTables = FreightTable::factory()->count(3)->create();
    
        $ids = $freightTables->pluck('id')->toArray();
    
        $response = $this->postJson('/api/freight-tables/delete', ['ids' => $ids]);
    
        $response->assertStatus(200);
        foreach ($ids as $id) {
            $this->assertDatabaseMissing('freight_tables', ['id' => $id]);
        }
    }

    public function test_it_can_upload_freight_csv()
    {
        Storage::fake('local');

        Bus::fake();

        $csvFile = UploadedFile::fake()->createWithContent('freight.csv', implode("\n", [
            'from_postcode,to_postcode,from_weight,to_weight,cost,branch_id',
            '12345,67890,0.5,1.0,50.00,1',
            '23456,78901,1.5,2.0,75.00,2'
        ]));

        $response = $this->postJson('/api/upload-freight-csv', [
            'csv_file' => [$csvFile]
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Os arquivos estão sendo processados']);

        Storage::disk('local')->assertExists('temp/' . $csvFile->hashName());

        Bus::assertDispatched(ProcessFreightTableCsv::class, function ($job) use ($csvFile) {
            return in_array(storage_path('app/temp/' . $csvFile->hashName()), $job->getFilePaths());
        });
    }
}
