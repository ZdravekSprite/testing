<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Day;
use App\Models\User;
use DateTime;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->call(RoleSeeder::class);
    User::factory()
      ->create([
        'name' => env('ADMIN_NAME', 'admin'),
        'email' => env('ADMIN_EMAIL', 'admin@admin.com'),
        'password' => Hash::make(env('ADMIN_PASS', 'password')),
      ])->each(function ($user) {
        $date = new DateTime();
        for ($i = 0; $i < 110; $i++) {
          $day = Day::factory()->make(['date' => $date, 'user_id' => $user->id, 'start' => '14:00']);
          $day->save();
          date_add($date, date_interval_create_from_date_string('-1 day'));
        }
        /*
        $days = Day::factory()->count(10)->make(['user_id' => $user->id, 'start' => '14:00']);
        foreach ($days as $day) {
          repeat:
          try {
            $day->save();
          } catch (\Illuminate\Database\QueryException $e) {
            $subject = Day::factory()->make(['user_id' => $user->id]);
            goto repeat;
          }
        }*/
      });
    $this->call([
      HolidaySeeder::class,
    ]);
    $this->call(SymbolSeeder::class);
  }
}
