<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
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
      'name' => env('SUPER_ADMIN_NAME', 'Super Admin'),
      'email' =>  env('SUPER_ADMIN_EMAIL', 'super@admin.com'),
      'password' => Hash::make(env('SUPER_ADMIN_PASS', 'password')),
    ]);
    $super_admin->roles()->attach($superadminRole);
    $super_admin->roles()->attach($adminRole);
  }
}
