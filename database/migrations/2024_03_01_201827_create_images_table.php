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
    Schema::create('images', function (Blueprint $table) {
      $table->id();
      $table->foreignId('owner_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
      $table->string('filename');
      $table->string('title')->nullable();
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
    Schema::dropIfExists('images');
  }
};