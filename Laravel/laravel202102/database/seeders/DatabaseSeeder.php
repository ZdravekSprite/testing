<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    // \App\Models\User::factory(10)->create();
    DB::table('users')->delete();
    DB::table('users')->insert([
      'name' => 'Zdravko Šplajt',
      'email' => 'zdravek.sprite@gmail.com',
      'password' => Hash::make('nivesonka'),
      'work_days' => '[{"d":"2.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"3.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"4.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"5.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"6.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"7.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"8.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"10.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"11.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"12.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"13.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"14.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"15.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"18.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"19.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"20.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"21.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"22.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"25.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"26.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"27.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"28.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"29.1.2021", "s":false, "h":[{"s":"14:00","d":"8:00"}]},
{"d":"30.1.2021", "s":false, "h":[{"s":"14:00","d":"7:00"}]}]',
    ]);
    $this->command->info('User Zdravko seeded!');
  }
}
