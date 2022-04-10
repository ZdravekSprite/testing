# Article

## article model (+ factory + migration + seeder + controller)

```bash
php artisan make:model Article -a
```

### database\migrations\2021_02_21_143135_create_days_table.php

```php
  public function up()
  {
    Schema::create('articles', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->integer('price');
      $table->json('data');
      $table->timestamps();
    });
  }
```

```bash
php artisan migrate
```
