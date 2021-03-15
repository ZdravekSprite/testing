<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Schema::disableForeignKeyConstraints();
    Role::truncate();
    Role::create(['name' => 'superadmin']);
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'user']);
    Role::create(['name' => 'socialuser']);
    Role::create(['name' => 'blockeduser']);
    DB::table('role_user')->truncate();
    Schema::enableForeignKeyConstraints();
    $superadminRole = Role::where('name', 'superadmin')->first();
    $adminRole = Role::where('name', 'admin')->first();
    $super_admin = User::create([
      'name' => env('SUPER_ADMIN_NAME', 'Super admin'),
      'email' =>  env('SUPER_ADMIN_EMAIL', 'super@admin.com'),
      'password' => Hash::make(env('SUPER_ADMIN_PASS', 'password')),
      'avatar' => 'https://upload.wikimedia.org/wikipedia/commons/5/55/User-admin-gear.svg'
    ]);
    $super_admin->roles()->attach($superadminRole);
    $super_admin->roles()->attach($adminRole);
  }
}
