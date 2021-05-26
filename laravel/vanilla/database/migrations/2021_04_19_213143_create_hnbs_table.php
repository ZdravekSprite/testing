<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHnbsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('hnbs', function (Blueprint $table) {
      $table->id();
      $table->string('broj_tecajnice');
      $table->date('datum_primjene');
      $table->string('drzava');
      $table->string('drzava_iso');
      $table->string('sifra_valute');
      $table->string('valuta');
      $table->tinyInteger('jedinica');
      $table->string('kupovni_tecaj');
      $table->string('srednji_tecaj');
      $table->string('prodajni_tecaj');
      $table->unique(['datum_primjene', 'valuta']);
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
    Schema::dropIfExists('hnbs');
  }
}
