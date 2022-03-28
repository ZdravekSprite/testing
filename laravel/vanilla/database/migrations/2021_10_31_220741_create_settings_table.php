<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('settings', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained();
      $table->time('start1')->nullable();
      $table->time('end1')->nullable();
      $table->time('start2')->nullable();
      $table->time('end2')->nullable();
      $table->time('start3')->nullable();
      $table->time('end3')->nullable();
      $table->date('zaposlen')->nullable();
      $table->string('BINANCE_API_KEY')->nullable();
      $table->string('BINANCE_API_SECRET')->nullable();
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
    Schema::dropIfExists('settings');
  }
}
