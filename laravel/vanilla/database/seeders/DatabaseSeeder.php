<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
    User::factory()
      ->create([
        'name' => env('ADMIN_NAME', 'admin'),
        'email' => env('ADMIN_EMAIL', 'admin@admin.com'),
        'password' => Hash::make(env('ADMIN_PASS', 'password')),
      ])->each(function ($user) {
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
  }
}
