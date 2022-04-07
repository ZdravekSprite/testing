# Route

## route model (+ factory + migration + seeder + controller)

```bash
php artisan make:model Route -a
```

### database\migrations\2021_02_21_143135_create_days_table.php

```php
  public function up()
  {
    Schema::create('routes', function (Blueprint $table) {
      $table->id();
      $table->json('data');
      $table->timestamps();
    });
  }
```

```bash
php artisan migrate
```
