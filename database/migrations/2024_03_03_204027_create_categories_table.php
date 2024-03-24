<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    //一つのmigrationファイルで２つのスキーマが書ける。
    Schema::create('primary_categories', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->integer('sort_order');
      $table->timestamps();
    });

    Schema::create('secondary_categories', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->foreignId('primary_category_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
      $table->integer('sort_order');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    // 外部キーの都合で、secondaryから消さないといけない。
    Schema::dropIfExists('secondary_categories');
    Schema::dropIfExists('primary_categories');
  }
};
