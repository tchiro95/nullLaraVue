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
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->foreignId('shop_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
      //constrainedはmodelとidを紐づけてくれる
      $table->string('name');
      $table->text('information');
      $table->integer('price')->nullable();
      $table->boolean('is_selling')->nullable();
      $table->integer('sort_order')->nullable();
      $table->foreignId('secondary_category_id')->constrained();
      //foreignIDがテーブル名と異なるときは、constrainedで指定してあげる
      $table->foreignId('image1')->nullable()->constrained('images');
      $table->foreignId('image2')->nullable()->constrained('images');
      $table->foreignId('image3')->nullable()->constrained('images');
      $table->foreignId('image4')->nullable()->constrained('images');
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
    Schema::dropIfExists('products');
  }
};
