<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\FreightTable;

class ProcessFreightTableCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePaths;

    public function __construct($filePaths)
    {
        $this->filePaths = $filePaths;
    }

    public function handle()
    {

        try {
            foreach ($this->filePaths as $filePath) {

                $csv = Reader::createFromPath($filePath, 'r');
                $csv->setHeaderOffset(0);
                $csv->setDelimiter(',');

                $records = $csv->getRecords();
                $chunks = array_chunk(iterator_to_array($records), 1000);

                foreach ($chunks as $chunk) {
                    $processedChunk = array_map([$this, 'convertDecimalValues'], $chunk);
                    FreightTable::insert($processedChunk);
                }
            }
        } catch (Exception $e) {
            Log::error('Error processing CSV: ' . $e->getMessage());
        }
    }

    protected function convertDecimalValues($record)
    {
        $record['from_weight'] = $this->convertToDecimal($record['from_weight']);
        $record['to_weight'] = $this->convertToDecimal($record['to_weight']);
        $record['cost'] = $this->convertToDecimal($record['cost']);
        
        return $record;
    }

    protected function convertToDecimal($value)
    {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        
        return $value;
    }
}
