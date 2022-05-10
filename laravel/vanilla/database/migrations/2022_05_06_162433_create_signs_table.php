<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if (Schema::hasTable('signs')) { return; }
    Schema::create('signs', function (Blueprint $table) {
      $table->id();
      $table->string('name')->unique();
      $table->string('description')->nullable();
      $table->string('a')->nullable();
      $table->string('b1')->nullable();
      $table->string('b2', 500)->nullable();
      $table->string('c', 500)->nullable();
      $table->string('svg_type')->nullable();
      $table->string('svg_start_transform')->nullable();
      $table->string('svg_start')->nullable();
      $table->string('svg', 5000)->nullable();
      $table->string('svg_end_transform')->nullable();
      $table->string('svg_end')->nullable();
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
    Schema::dropIfExists('signs');
  }
}
