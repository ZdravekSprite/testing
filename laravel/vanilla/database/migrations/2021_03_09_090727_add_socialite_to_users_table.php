<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialiteToUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->string('name')->nullable()->change();
      $table->string('password')->nullable()->change();
      $table->string('facebook_id')->nullable();
      $table->string('twitter_id')->nullable();
      $table->string('linkedin_id')->nullable();
      $table->string('google_id')->nullable();
      $table->string('github_id')->nullable();
      $table->string('avatar')->nullable();
      $table->string('facebook_avatar')->nullable();
      $table->string('twitter_avatar')->nullable();
      $table->string('linkedin_avatar')->nullable();
      $table->string('google_avatar')->nullable();
      $table->string('github_avatar')->nullable();
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
      $table->string('name')->change();
      $table->string('password')->change();
      $table->dropColumn('facebook_id', 'twitter_id', 'linkedin_id', 'google_id', 'github_id');
      $table->dropColumn('avatar', 'facebook_avatar', 'twitter_avatar', 'linkedin_avatar', 'google_avatar', 'github_avatar');
    });
  }
}
