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
