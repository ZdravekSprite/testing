<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->mediumInteger('bruto')
        ->after('password')
        ->nullable();
      $table->smallInteger('prijevoz')
        ->after('bruto')
        ->nullable();
      $table->mediumInteger('odbitak')
        ->after('prijevoz')
        ->nullable();
      $table->smallInteger('prirez')
        ->after('odbitak')
        ->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('bruto', 'prijevoz', 'odbitak', 'prirez');
    });
  }
}
