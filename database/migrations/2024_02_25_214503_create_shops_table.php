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
    Schema::create('shops', function (Blueprint $table) {
      $table->id();
      //onUpdateはowner_idが変わったときなどに連動。削除はdeleteに連動。onUpdate('cascade')も同じ。下は新しい書き方
      $table->foreignId('owner_id')->constrained()
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->string('name');
      $table->text('information');
      $table->string('filename');
      $table->boolean('is_selling');
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
    Schema::dropIfExists('shops');
  }
};
