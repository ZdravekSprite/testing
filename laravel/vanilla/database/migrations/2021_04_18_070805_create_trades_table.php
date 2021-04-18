<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('trades', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');
      $table->string('symbol');
      $table->bigInteger('binanceId');
      $table->bigInteger('orderId');
      $table->tinyInteger('orderListId');
      $table->string('price');
      $table->string('qty');
      $table->string('quoteQty');
      $table->string('commission');
      $table->string('commissionAsset');
      $table->bigInteger('time');
      $table->boolean('isBuyer');
      $table->boolean('isMaker');
      $table->boolean('isBestMatch');
      $table->timestamps();
      $table->foreign('user_id')->references('id')->on('users');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('trades');
  }
}
