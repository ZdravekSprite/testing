<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDopustToDaysTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('days', function (Blueprint $table) {
      $table->boolean('dopust')
        ->after('go')
        ->default(false);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('days', function (Blueprint $table) {
      $table->dropColumn('dopust');
    });
  }
}
