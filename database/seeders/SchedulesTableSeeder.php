<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('schedules')->delete();

        DB::table('schedules')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'title' => 'Đặt hàng Kỳ 1 tháng 10 năm 2023',
                    'period' => 'Kỳ 1',
                    'start_time' => Carbon::now(),
                    'end_time' => Carbon::now()->addDays(5),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'title' => 'Đặt hàng Kỳ 2 tháng 10 năm 2023',
                    'period' => 'Kỳ 2',
                    'start_time' => Carbon::now()->addDays(7),
                    'end_time' => Carbon::now()->addDays(12),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            2 =>
                array (
                    'id' => 3,
                    'title' => 'Đặt hàng Kỳ 3 tháng 10 năm 2023',
                    'period' => 'Kỳ 3',
                    'start_time' => Carbon::now()->addDays(14),
                    'end_time' => Carbon::now()->addDays(20),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

        ));
    }
}
