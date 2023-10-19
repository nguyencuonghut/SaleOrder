<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();

        DB::table('users')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'Tony Nguyen',
                    'email' => 'nguyenvancuong@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'is_disable' => 0,
                    'role_id' => 1,
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'Nhân viên Kinh Doanh',
                    'email' => 'nvkd@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'is_disable' => 0,
                    'role_id' => 4,
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            2 =>
                array (
                    'id' => 3,
                    'name' => 'Trưởng vùng 1',
                    'email' => 'truongvung1@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'is_disable' => 0,
                    'role_id' => 3,
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'name' => 'Trưởng vùng 2',
                    'email' => 'truongvung2@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'is_disable' => 0,
                    'role_id' => 3,
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'name' => 'Trưởng vùng 3',
                    'email' => 'truongvung3@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'is_disable' => 0,
                    'role_id' => 3,
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            5 =>
                array (
                    'id' => 6,
                    'name' => 'Giám đốc 1',
                    'email' => 'gdv1@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'is_disable' => 0,
                    'role_id' => 2,
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            6 =>
                array (
                    'id' => 7,
                    'name' => 'Giám đốc 2',
                    'email' => 'gdv2@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'is_disable' => 0,
                    'role_id' => 2,
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));
    }
}
