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

### routes\web.php

```php
use App\Http\Controllers\ArticleController;
Route::resource('articles', ArticleController::class);
```

### resources\views\articles

- resources\views\articles\create.blade.php
- resources\views\articles\edit.blade.php
- resources\views\articles\form.blade.php
- resources\views\articles\index.blade.php
- resources\views\articles\show.blade.php

### app\Http\Requests

- app\Http\Requests\StoreArticleRequest.php
- app\Http\Requests\UpdateArticleRequest.php

```php
  public function authorize()
  {
    return true;
  }
  public function rules()
  {
    return [
      'name' => 'required|string|min:3|max:255|unique:articles'
    ];
  }
```

### app\Http\Controllers

- app\Http\Controllers\ArticleController.php