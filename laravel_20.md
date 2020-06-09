# Laravel Role

```bash
git checkout master
git pull origin master
git checkout -b role
php artisan make:model Role -m
git add .
git commit -a -m "first commit [role]"
```

> - app\Role.php

```php
9-    //
9-11+
    public function users(){
        return $this->belongsToMany('App\User');
    }
```

> - app\User.php

```php
40-51+

    public function roles() {
        return $this->belongsToMany('App\Role');
    }

    public function hasAnyRoles($roles) {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    public function hasAnyRole($role) {
        return null !== $this->roles()->where('name', $role)->first();
    }
```

> - database\migrations\2020_06_06_212023_create_roles_table.php

```php
18+            $table->string('name');
21-26+
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });
```

```bash
php artisan migrate:fresh
php artisan make:seed RolesTableSeeder
git add .
```

> - database\seeds\RolesTableSeeder.php

```php
4+use App\Role;
15-        //
15-19+
        Role::truncate();
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        Role::create(['name' => 'socialuser']);
```

```bash
php artisan make:seed UsersTableSeeder
git add .
```

> - database\seeds\UsersTableSeeder.php

```php
4-6+
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;
17-        //
17-28+
        User::truncate();
        DB::table('role_user')->truncate();

        $superadminRole = Role::where('name', 'superadmin')->first();

        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password')
        ]);

        $admin->roles()->attach($superadminRole);
```

> - database\seeds\DatabaseSeeder.php

```php
14-        // $this->call(UserSeeder::class);
14-15+
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
```

```bash
git merge origin/master
```

> - app\Http\Controllers\Auth\LoginController.php

```php
10+use App\Role;
82-84+
        $socialUserRole = Role::where('name', 'socialuser')->first();
        $user->roles()->attach($socialUserRole);

```

> - app\Http\Controllers\Auth\RegisterController.php

```php
8+use App\Role;
68-        return User::create([
68+        $user = User::create([
73-78+

        $role = Role::select('id')->where('name', 'user')->first();

        $user->roles()->attch($role);
    
        return $user;
```

```bash
php artisan make:provider BladeServiceProvider
git add .
```

> - app\Providers\BladeServiceProvider.php

```php
6-7+
use Blade;
use Auth;
28-        //
28-37+
        Blade::if('hasrole', function($expression){

            if(Auth::user()){
                if(Auth::user()->hasAnyRole($expression)){
                    return true;
                }
            }

            return false;
        });
```

> - config\app.php

```php
177+        App\Providers\BladeServiceProvider::class,
```

```bash
php artisan migrate:fresh
php artisan db:seed
```
