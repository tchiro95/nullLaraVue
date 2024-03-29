<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //
    DB::table('products')->insert([
      [
        'shop_id' => 1,
        'name' => '単語帳',
        'secondary_category_id' => 1,
        'image1' => 1,
      ],
      [
        'shop_id' => 1,
        'name' => '文法集',
        'secondary_category_id' => 2,
        'image1' => 2,
      ],
      [
        'shop_id' => 1,
        'name' => 'リスニング対策',
        'secondary_category_id' => 3,
        'image1' => 3,
      ]
    ]);
  }
}
