<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlinesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('klines', function (Blueprint $table) {
      $table->id();
      $table->string('symbol');
      $table->string('interval');
      $table->bigInteger('start_time'); // Kline start time
      $table->bigInteger('close_time'); // Kline close time
      $table->string('o'); // Open price
      $table->string('c'); // Close price
      $table->string('h'); // High price
      $table->string('l'); // Low price
      $table->string('v'); // Base asset volume
      $table->bigInteger('n'); // Number of trades
      $table->string('q'); // Quote asset volume
      $table->string('base_volume'); // Taker buy base asset volume
      $table->string('quote_volume'); // Taker buy quote asset volume
      $table->unique(['symbol', 'interval', 'start_time']);
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
    Schema::dropIfExists('klines');
  }
}
