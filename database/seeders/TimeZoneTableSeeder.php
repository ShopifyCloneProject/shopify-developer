<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TimeZone;


class TimeZoneTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TimeZone::truncate();
        $data = json_decode(file_get_contents(__DIR__ . '/../../resources/timezones.json'), true);
        $now  = date('Y-m-d H:i:s');

        foreach ($data as $tzs) {
            foreach ($tzs as $offset => $timezones) {
                foreach ($timezones as $timezone) {
                    TimeZone::insert(array(
                        'title' => $timezone['label'],
                        'timezone_value' => $timezone['value'],
                        'created_at' => $now,
                        'updated_at' => $now
                    ));
                }
            }
        }
    }
}
