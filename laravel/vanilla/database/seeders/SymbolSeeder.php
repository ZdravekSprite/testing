<?php

namespace Database\Seeders;

use App\Http\Controllers\SymbolController;
use App\Models\Symbol;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class SymbolSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Symbol::truncate();
    SymbolController::exchangeInfo();
  }
}
