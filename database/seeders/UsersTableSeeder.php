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
                    'name' => 'Đỗ Minh Cảnh',
                    'email' => 'dominhcanh@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'is_disable' => 0,
                    'role_id' => 1,
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            2 =>
                array (
                    'id' => 3,
                    'name' => 'Đỗ Minh Đông',
                    'email' => 'dominhdong@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'is_disable' => 0,
                    'role_id' => 2,
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'name' => 'Ngụy Văn Duyên',
                    'email' => 'nguyvanduyen@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'is_disable' => 0,
                    'role_id' => 2,
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'name' => 'Lưu Văn Tuấn',
                    'email' => 'luuvantuan@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'is_disable' => 0,
                    'role_id' => 3,
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));

        User::factory(99)->create();
    }
}
