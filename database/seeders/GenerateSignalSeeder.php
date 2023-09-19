<?php

namespace Database\Seeders;

use App\Models\Signal;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Exception;

class GenerateSignalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run($month, $year)
    {
        $response['status'] = false;
        if (isset($month) && isset($year)) {
                DB::beginTransaction();
                try {
                    ini_set('max_execution_time', 0);
                    $firstDayOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
                    $lastDayOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
                    $firstDayTimestamp = $firstDayOfMonth->timestamp;
                    $lastDayTimestamp = $lastDayOfMonth->timestamp;

                    $intervalSeconds = 300; // 5 minutes in seconds

                    $currentTimestamp = $firstDayTimestamp;
                    $data = [];

                    while ($currentTimestamp <= $lastDayTimestamp) {
                        $tempTimestamp = $currentTimestamp + $intervalSeconds;
                        $data[] = [
                            'start_time' => date('Y-m-d H:i:s', $currentTimestamp),
                            'end_time' => date('Y-m-d H:i:s', $tempTimestamp)
                        ];
                        $currentTimestamp += $intervalSeconds;
                    }

                    // The last row should have time at 23:59:59
                    $data[count($data) - 1]['end_time'] = date('Y-m-d H:i:s', $lastDayOfMonth->timestamp);

                    Signal::insert($data);
                    DB::commit();
                    $response['status'] = true;
                    $response['message'] = 'Data Generated Successfully !';
                } catch (Exception $e) {
                    DB::rollBack();
                    $response['status'] = false;
                    $response['message'] = $e->getMessage();
                }
        }
        else{
            $response['status'] = false;
            $response['message'] = 'Please Provide Month and Year, Thanks :)';
        }

        return $response;
    }
}
