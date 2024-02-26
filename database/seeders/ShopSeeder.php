<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ShopSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //
    DB::table('shops')->insert([
      [
        'owner_id' => 1,
        'name' => 'Shop_owner1',
        'information' => 'owner1のお店です。',
        'filename' => 'owner1_image',
        'is_selling' => true,
        'created_at' => Now(),
      ],
      [
        'owner_id' => 2,
        'name' => 'Shop_owner2',
        'information' => 'owner2のお店です。',
        'filename' => 'owner2_image',
        'is_selling' => true,
        'created_at' => Now(),
      ],
    ]);
  }
}
