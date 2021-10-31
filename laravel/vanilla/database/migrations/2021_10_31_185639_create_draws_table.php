<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrawsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('draws', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->tinyInteger('no01');
      $table->tinyInteger('no02');
      $table->tinyInteger('no03');
      $table->tinyInteger('no04');
      $table->tinyInteger('no05');
      $table->tinyInteger('bo01')->nullable();
      $table->tinyInteger('bo02')->nullable();
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
    Schema::dropIfExists('draws');
  }
}
