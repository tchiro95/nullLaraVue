<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //
    DB::table('primary_categories')->insert([
      ['name' => 'TOEIC', 'sort_order' => 1],
      ['name' => '英検', 'sort_order' => 2],
      ['name' => 'TOEFL', 'sort_order' => 3],
      ['name' => 'IELTS', 'sort_order' => 4],
      ['name' => 'そのほか', 'sort_order' => 5],
    ]);
    DB::table('secondary_categories')->insert([
      [
        'name' => '語彙', 'primary_category_id' => '1',
        'sort_order' => 1
      ],
      [
        'name' => '文法', 'primary_category_id' => '1',
        'sort_order' => 2
      ],
      [
        'name' => '長文読解', 'primary_category_id' => '1',
        'sort_order' => 3
      ],
      [
        'name' => 'リスニング', 'primary_category_id' => '1',
        'sort_order' => 4
      ],
      [
        'name' => 'ライティング', 'primary_category_id' => '1',
        'sort_order' => 5
      ],
      [
        'name' => 'スピーキング', 'primary_category_id' => '1',
        'sort_order' => 6
      ],

      [
        'name' => '語彙', 'primary_category_id' => '2',
        'sort_order' => 1
      ],
      [
        'name' => '文法', 'primary_category_id' => '2',
        'sort_order' => 2
      ],
      [
        'name' => '長文読解', 'primary_category_id' => '2',
        'sort_order' => 3
      ],
      [
        'name' => 'リスニング', 'primary_category_id' => '2',
        'sort_order' => 4
      ],
      [
        'name' => 'ライティング', 'primary_category_id' => '2',
        'sort_order' => 5
      ],
      [
        'name' => 'スピーキング', 'primary_category_id' => '2',
        'sort_order' => 6
      ],

      [
        'name' => '語彙', 'primary_category_id' => '3',
        'sort_order' => 1
      ],
      [
        'name' => '文法', 'primary_category_id' => '3',
        'sort_order' => 2
      ],
      [
        'name' => '長文読解', 'primary_category_id' => '3',
        'sort_order' => 3
      ],
      [
        'name' => 'リスニング', 'primary_category_id' => '3',
        'sort_order' => 4
      ],
      [
        'name' => 'ライティング', 'primary_category_id' => '3',
        'sort_order' => 5
      ],
      [
        'name' => 'スピーキング', 'primary_category_id' => '3',
        'sort_order' => 6
      ],

      [
        'name' => '語彙', 'primary_category_id' => '4',
        'sort_order' => 1
      ],
      [
        'name' => '文法', 'primary_category_id' => '4',
        'sort_order' => 2
      ],
      [
        'name' => '長文読解', 'primary_category_id' => '4',
        'sort_order' => 3
      ],
      [
        'name' => 'リスニング', 'primary_category_id' => '4',
        'sort_order' => 4
      ],
      [
        'name' => 'ライティング', 'primary_category_id' => '4',
        'sort_order' => 5
      ],
      [
        'name' => 'スピーキング', 'primary_category_id' => '4',
        'sort_order' => 6
      ],

      [
        'name' => '語彙', 'primary_category_id' => '5',
        'sort_order' => 1
      ],
      [
        'name' => '文法', 'primary_category_id' => '5',
        'sort_order' => 2
      ],
      [
        'name' => '長文読解', 'primary_category_id' => '5',
        'sort_order' => 3
      ],
      [
        'name' => 'リスニング', 'primary_category_id' => '5',
        'sort_order' => 4
      ],
      [
        'name' => 'ライティング', 'primary_category_id' => '5',
        'sort_order' => 5
      ],
      [
        'name' => 'スピーキング', 'primary_category_id' => '5',
        'sort_order' => 6
      ],


    ]);
  }
}
