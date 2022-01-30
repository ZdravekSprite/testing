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

```bash
php artisan route:cache
```

## laravel -> main
```bash
git add .
git commit -am "laravel - help - 2022 1 20"
git push
git checkout main
git pull
git merge laravel
git push
```
## main -> laravel
```bash
git checkout laravel
git pull
git merge main
git push
```
## binance -> main
```bash
git add .
git commit -am "binance 2022 01 30"
git push
git checkout main
git pull
git merge binance
git push
```
## main -> binance
```bash
git checkout binance
git pull
git merge main
git push
```
## lotto -> main
```bash
git add .
git commit -am "lotto 2021 12 25"
git push
git checkout main
git pull
git merge lotto
git push
```
## main -> lotto
```bash
git checkout lotto
git pull
git merge main
git push
```

## main -> new
```bash
git branch new
git checkout new
git add .
git commit -am "new"
git push --set-upstream origin new
```

If you know you want to use git reset, it still depends what you mean by "uncommit". If all you want to do is undo the act of committing, leaving everything else intact, use:

git reset --soft HEAD^
If you want to undo the act of committing and everything you'd staged, but leave the work tree (your files) intact:

git reset HEAD^
And if you actually want to completely undo it, throwing away all uncommitted changes, resetting everything to the previous commit (as the original question asked):

git reset --hard HEAD^