## Months

### month model (+ factory + migration + seeder + controller)
```
php artisan make:model Month -a
```
### database\migrations\2021_08_17_084756_create_months_table.php
```
  public function up()
  {
    Schema::create('months', function (Blueprint $table) {
      $table->id();
      $table->smallInteger('month');
      $table->unsignedBigInteger('user_id');
      $table->mediumInteger('bruto')->nullable();
      $table->smallInteger('prijevoz')->nullable();
      $table->mediumInteger('odbitak')->nullable();
      $table->smallInteger('prirez')->nullable();
      $table->tinyInteger('prekovremeni')->nullable();
      $table->mediumInteger('stimulacija')->nullable();
      $table->mediumInteger('regres')->nullable();
      $table->timestamps();
      $table->unique(['user_id', 'month']);
      $table->foreign('user_id')->references('id')->on('users');
    });
  }
```
### app\Models\Month.php
```
  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'id',
    'user_id',
    'created_at',
    'updated_at',
  ];
```
## Eloquent: Relationships

### app\Models\User.php
```
  /**
   * Get the users months.
   */
  public function months()
  {
    return $this->hasMany(Months::class);
  }
```
```
php artisan make:migration add_month_to_users_table --table=users
```
### database\migrations\2021_03_02_111443_add_data_to_users_table.php
```
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->smallInteger('month')
        ->before('bruto')
        ->nullable();
    });
  }
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('month');
    });
  }
```
```
php artisan migrate
```

```
php artisan migrate
git add .
git commit -am "months [laravel]"
git commit -am "save 2019 09 15"
git push
```