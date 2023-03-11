# Traffic

## Route

### route model (+ factory + migration + seeder + controller)

```bash
php artisan make:model Route -a
```

#### database\migrations\2021_02_21_143135_create_days_table.php

```php
  public function up()
  {
    Schema::create('routes', function (Blueprint $table) {
      $table->id();
      $table->json('data')->unique();
      $table->timestamps();
    });
  }
```

```bash
php artisan migrate
```

## Signs

### sign model (+ factory + migration + seeder + controller)

```bash
php artisan make:model Sign -a
```

### database\migrations\2022_05_06_162433_create_signs_table.php

```php
  public function up()
  {
    Schema::create('signs', function (Blueprint $table) {
      $table->id();
      $table->string('name')->unique();
      $table->string('description')->nullable();
      $table->string('a')->nullable();
      $table->string('b1')->nullable();
      $table->string('b2')->nullable();
      $table->string('c', 500)->nullable();
      $table->string('svg_type')->nullable();
      $table->string('svg_start_transform')->nullable();
      $table->string('svg_start')->nullable();
      $table->string('svg', 5000);
      $table->string('svg_end_transform')->nullable();
      $table->string('svg_end')->nullable();
      $table->timestamps();
    });
  }
```

```bash
php artisan migrate
```

### routes\web.php

```php
use App\Http\Controllers\SignController;
Route::resource('signs', SignController::class);
```

### resources\views\signs

- resources\views\signs\create.blade.php
- resources\views\signs\edit.blade.php
- resources\views\signs\form.blade.php
- resources\views\signs\index.blade.php
- resources\views\signs\show.blade.php

### app\Http\Requests

- app\Http\Requests\StoreSignRequest.php
- app\Http\Requests\UpdateSignRequest.php

```php
  public function authorize()
  {
    return true;
  }
```

### app\Http\Controllers

- app\Http\Controllers\SignController.php
