<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSymbolsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('symbols', function (Blueprint $table) {
      $table->id();
      $table->string('symbol');
      $table->string('status');
      $table->string('baseAsset');
      $table->tinyInteger('baseAssetPrecision');
      $table->string('quoteAsset');
      $table->tinyInteger('quotePrecision');
      $table->tinyInteger('quoteAssetPrecision');
      $table->boolean('icebergAllowed');
      $table->boolean('ocoAllowed');
      $table->boolean('isSpotTradingAllowed');
      $table->boolean('isMarginTradingAllowed');
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
    Schema::dropIfExists('symbols');
  }
}
