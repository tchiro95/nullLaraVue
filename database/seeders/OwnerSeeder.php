<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('owners')->insert([
      [
        'name' => 'owner1',
        'email' => 'owner1@test.com',
        'password' => Hash::make('password123'),
        'created_at' => '2022-01-01'
      ],
      [
        'name' => 'owner2',
        'email' => 'owner2@test.com',
        'password' => Hash::make('password123'),
        'created_at' => '2023-11-01'
      ],
      [
        'name' => 'owner3',
        'email' => 'owner3@test.com',
        'password' => Hash::make('password123'),
        'created_at' => '2024-01-01'
      ],
      [
        'name' => 'owner4',
        'email' => 'owner4@test.com',
        'password' => Hash::make('password123'),
        'created_at' => '2024-01-01'
      ],
      [
        'name' => 'owner5',
        'email' => 'owner5@test.com',
        'password' => Hash::make('password123'),
        'created_at' => '2024-01-01'
      ],
      [
        'name' => 'owner6',
        'email' => 'owner6@test.com',
        'password' => Hash::make('password123'),
        'created_at' => '2024-01-01'
      ],
      [
        'name' => 'owner7',
        'email' => 'owner7@test.com',
        'password' => Hash::make('password123'),
        'created_at' => '2024-01-01'
      ],
    ]);
  }
}
