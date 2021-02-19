<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Day;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    DB::table('users')->insert([
      'name' => 'Zdravko Šplajt',
      'email' => 'zdravek.sprite@gmail.com',
      'password' => Hash::make(env('ADMIN_PASS', 'password')),
    ]);
    User::factory()
      ->count(10)
      ->create()->each(function ($user) {
        $days = Day::factory()->count(5)->make(['user_id' => $user->id]);
        foreach ($days as $day) {
          repeat:
          try {
            $day->save();
          } catch (\Illuminate\Database\QueryException $e) {
            $subject = Day::factory()->make(['user_id' => $user->id]);
            goto repeat;
          }
        }
      });
    /*$this->call([
      DaySeeder::class,
    ]);*/
  }
}
