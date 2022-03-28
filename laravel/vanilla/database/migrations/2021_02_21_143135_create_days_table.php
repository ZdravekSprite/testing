<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaysTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('days', function (Blueprint $table) {
      $table->id();
      $table->date('date');
      $table->foreignId('user_id')->constrained();
      $table->boolean('state')->default(0);
      $table->time('night')->nullable();
      $table->time('start')->nullable();
      $table->time('end')->nullable();
      $table->timestamps();
      $table->unique(['user_id', 'date']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('days');
  }
}
