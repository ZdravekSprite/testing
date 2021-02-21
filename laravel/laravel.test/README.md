# laravel.test
Laravel test blog
## branch test0 - auth
## branch test1 - role
> - git

	$ git clone https://github.com/ZdravekSprite/laravel.test.git
	$ git branch test1
	$ git checkout test1
	$ git push --set-upstream origin test1
	$ composer update
	$ npm install && npm run dev
	$ php artisan make:model Role -m
> app\User.php

	¸¸
	  public function roles() {
	    return $this->belongsToMany('App\Role');
	  }

	  public function hasAnyRoles($roles) {
	    return null !== $this->roles()->whereIn('name', $roles)->first();
	  }

	  public function hasAnyRole($role) {
	    return null !== $this->roles()->where('name', $role)->first();
	  }
	¸¸
> app\Role.php

	¸¸
	  public function users(){
	    return $this->belongsToMany('App\User');
	  }
	¸¸
> database\migrations\2019_10_06_223231_create_roles_table.php

	¸¸
	    Schema::create('roles', function (Blueprint $table) {
	      $table->bigIncrements('id');
	      $table->string('name')->unique();
	      $table->timestamps();
	    });
	¸¸
#
		$ php artisan make:migration create_role_user_table
> database\migrations\2019_10_06_223629_create_role_user_table.php

	¸¸
	    Schema::create('role_user', function (Blueprint $table) {
	      $table->bigIncrements('id');
	      $table->bigInteger('role_id')->unsigned();
	      $table->bigInteger('user_id')->unsigned();
	      $table->timestamps();
	    });
	  }
	¸¸
#
	$ php artisan migrate:fresh
> - git

	$ git add .
	$ git commit -m "role model and migrations"
	$ git push
	$ php artisan make:seed RolesTableSeeder
> database\seeds\RolesTableSeeder.php

	¸¸
	use App\Role;
	¸¸
	  public function run()
	  {
	    Role::truncate();
	
	    Role::create(['name' => 'superadmin']);
	    Role::create(['name' => 'user']);
	  }
	¸¸
#
	$ php artisan make:seed UsersTableSeeder
> database\seeds\UsersTableSeeder.php

	¸¸
	use Illuminate\Support\Facades\Hash;
	use App\User;
	use App\Role;
	¸¸
	  public function run()
	  {
	    User::truncate();
	    DB::table('role_user')->truncate();
	
	    $superadminRole = Role::where('name', 'superadmin')->first();
	
	    $admin = User::create([
	      'name' => 'Super Admin',
	      'email' => 'admin@admin.com',
	      'password' => Hash::make('password')
	    ]);
	
	    $admin->roles()->attach($superadminRole);
	  }
	¸¸
> database\seeds\DatabaseSeeder.php

	¸¸
	  public function run()
	  {
	    $this->call(RolesTableSeeder::class);
	    $this->call(UsersTableSeeder::class);
	  }
	¸¸
#
	$ php artisan db:seed
> - git

	$ git add .
	$ git commit -m "roles and users seeder"
	$ git push
	$ php artisan make:provider BladeServiceProvider

> app\Providers\BladeServiceProvider.php

	¸¸
	use Blade;
	use Auth;
	¸¸
	  public function boot()
	  {
	    Blade::if('hasrole', function($expression){
	
	      if(Auth::user()){
	        if(Auth::user()->hasAnyRole($expression)){
	          return true;
	        }
	      }
	
	      return false;
	    });
	  }
	¸¸

- config\app.php
#
	¸¸
	  'providers' => [
	¸¸
	    App\Providers\BladeServiceProvider::class,
	  ],
	¸¸

- resources\views\_inc\navbar.blade.php
#
	¸¸
	        @hasrole('superadmin')
	        <li class="nav-item">
	          <a class="nav-link" href=#>{{ __('Menage Users') }}</a>
	        </li>
	        @endhasrole
	¸¸
> - git

	$ git add .
	$ git commit -m "BladeServiceProvider"
	$ git push
> app\Http\Controllers\Auth\RegisterController.php

	¸¸
	use App\Role;
	¸¸
	  protected function create(array $data)
	  {
	    $user = User::create([
	      'name' => $data['name'],
	      'email' => $data['email'],
	      'password' => Hash::make($data['password']),
	    ]);
	
	    $role = Role::select('id')->where('name', 'user')->first();
	
	    $user->roles()->attach($role);
	    
	    return $user;
	  }
	¸¸
> - git

	$ git add .
	$ git commit -m "user role for new users from registration"
	$ git push
	$ git add .
	$ git commit -m "README.md"
	$ git push
	$ git checkout master
	$ git merge test1
	$ git push --set-upstream origin master
