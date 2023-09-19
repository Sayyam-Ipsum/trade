<?php

namespace App\Console\Commands;

use App\Models\Signal;
use Database\Seeders\GenerateSignalSeeder;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\DB;

class GenerateSignalData extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:signal-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command generates signal data for a given month';

    /**
     * Execute the console command.
     */
    public function handle(GenerateSignalSeeder $seeder)
    {
        $month = $this->ask('Enter month number (e.g jan = 1) for which you want to generate data for ?');
        $year = $this->ask('Enter year (e.g 2023) for which you want to generate data for ?');
        if ($this->confirm('Do you wish to continue?')) {
            if(isset($year) && isset($month)){
            try {
                $result = DB::select(
                    "
                        SELECT COUNT(*) AS record_count
                        FROM signals
                        WHERE YEAR(start_time) = ? AND MONTH(start_time) = ?
                    ",
                    [$year, $month]
                );

                $recordCount = $result[0]->record_count;
                if($recordCount === 0){
                    $response = $seeder->run($month, $year);
                    if($response['status']){
                        $this->info($response['message']);
                    }
                    else{
                        $this->error($response['message']);
                    }
                }
                else{
                    $this->info('record already exists');
                }
            }
            catch (Exception $e){
                $this->error($e->getMessage());
            }
        }
            else{
                $this->info('Please Provide Month and Year, Thanks :)');
            }
        }
    }
}
