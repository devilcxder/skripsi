<?php

namespace App\Imports;

use App\Models\Train;
use App\Preprocessing\PreprocessingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TrainsImport implements ToModel, WithBatchInserts, WithHeadingRow, WithChunkReading, ShouldQueue, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsFailures, SkipsErrors;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {                       
        $prepro_train = PreprocessingService::index([$row['text']]);                 
        return new Train([            
            'train' => $row['text'],
            'prepro_train' => $prepro_train[0],
            'sentiment' => $row['sentiment'],
        ]);
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}