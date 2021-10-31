<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLottosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('lottos', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->tinyInteger('draws_choos');
      $table->tinyInteger('draws_from');
      $table->tinyInteger('bonus_choos')->nullable();
      $table->tinyInteger('bonus_from')->nullable();
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
    Schema::dropIfExists('lottos');
  }
}
