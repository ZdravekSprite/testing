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
      $table->unsignedBigInteger('user_id');
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
    Schema::dropIfExists('settings');
  }
}
