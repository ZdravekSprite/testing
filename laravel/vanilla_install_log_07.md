# Laravel Role

```bash
php artisan make:model Role -a
```
### app\Models\Role.php
```php
  public function users()
  {
    return $this->belongsToMany(User::class);
  }
```
### app\Models\User.php
```php
  public function roles()
  {
    return $this->belongsToMany(Role::class);
  }

  public function hasAnyRoles($roles)
  {
    return null !== $this->roles()->whereIn('name', $roles)->first();
  }

  public function hasAnyRole($role)
  {
    return null !== $this->roles()->where('name', $role)->first();
  }
```
### database\migrations\2021_03_12_101429_create_roles_table.php
```php
  public function up()
  {
    Schema::create('roles', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->timestamps();
    });
    Schema::create('role_user', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('role_id');
      $table->unsignedBigInteger('user_id');
      $table->timestamps();
      $table->foreign('role_id')->references('id')->on('roles');
      $table->foreign('user_id')->references('id')->on('users');
    });
  }
  public function down()
  {
    Schema::dropIfExists('roles');
    Schema::dropIfExists('roles_user');
  }
```
```bash
php artisan migrate
```
### database\seeders\RoleSeeder.php
```php
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
  public function run()
  {
    Role::truncate();
    Role::create(['name' => 'superadmin']);
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'user']);
    Role::create(['name' => 'socialuser']);
    Role::create(['name' => 'blockeduser']);
    DB::table('role_user')->truncate();
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
```
### database\seeders\DatabaseSeeder.php
```php
    $this->call(RoleSeeder::class);
```
```bash
php artisan db:seed --class=RoleSeeder
```
### routes\web.php
```php
use App\Models\Role;
  if (!$user->roles->pluck('name')->contains('socialuser')) {
    $socialUserRole = Role::where('name', 'socialuser')->first();
    $user->roles()->attach($socialUserRole);
  }
```
### app\Http\Controllers\Auth\RegisteredUserController.php
```php
use App\Models\Role;
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|confirmed|min:8',
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);
    $role = Role::select('id')->where('name', 'user')->first();
    $user->roles()->attach($role);

    Auth::login($user);

    event(new Registered($user));

    return redirect(RouteServiceProvider::HOME);
  }
```
```bash
php artisan make:provider BladeServiceProvider
```
### app\Providers\BladeServiceProvider.php
```php
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
  public function boot()
  {
    Blade::if('hasrole', function ($expression) {

      if (Auth::user()) {
        if (Auth::user()->hasAnyRole($expression)) {
          return true;
        }
      }

      return false;
    });
  }
```
## config\app.php
177+
```php
        App\Providers\BladeServiceProvider::class,
```
