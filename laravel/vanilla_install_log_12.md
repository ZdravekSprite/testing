## Months

### month model (+ factory + migration + seeder + controller)
```bash
php artisan make:model Month -a
```
### database\migrations\2021_08_17_084756_create_months_table.php
```php
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
```php
php artisan migrate
git add .
git commit -am "norm 1 [laravel]"
git commit -am "save 2019 10 25"
git push
```
## Lotto

### lotto model (+ factory + migration + seeder + controller)
```bash
php artisan make:model Lotto -a
php artisan migrate
```

### draw model (+ factory + migration + seeder + controller)
```bash
php artisan make:model Draw -a
php artisan migrate
```
## laravel -> binance
```bash
git add .
git commit -am "save laravel 2021 11 2"
git push
git checkout main
git pull
git merge laravel
git push
git checkout binance
git pull
git merge main
git push
```

## binance -> laravel
```bash
git add .
git commit -am "save binance 2021 11 1"
git push
git checkout main
git pull
git merge binance
git push
git checkout laravel
git pull
git merge main
git push
```

## main -> lotto
```bash
git branch lotto
git checkout lotto
git add .
git commit -am "save lotto 2019 10 31"
git push --set-upstream origin lotto
```
