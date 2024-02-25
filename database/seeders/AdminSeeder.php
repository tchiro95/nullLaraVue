<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //
    DB::table('admins')->insert([
      [
        'name' => 'admin1',
        'email' => 'admin1@test.com',
        'password' => Hash::make('password123'),
      ],
      [
        'name' => 'admin2',
        'email' => 'admin2@test.com',
        'password' => Hash::make('password123'),
      ],
      [
        'name' => 'admin3',
        'email' => 'admin3@test.com',
        'password' => Hash::make('password123'),
      ],
    ]);
  }
}
