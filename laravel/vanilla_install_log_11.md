# Binance
```bash
php artisan make:model Symbol -a
```
### database\migrations\2021_04_16_221728_create_symbols_table.php
```php
  public function up()
  {
    Schema::create('symbols', function (Blueprint $table) {
      $table->id();
      $table->string('symbol');
      $table->string('status');
      $table->string('baseAsset');
      $table->tinyInteger('baseAssetPrecision');
      $table->string('quoteAsset');
      $table->tinyInteger('quotePrecision');
      $table->tinyInteger('quoteAssetPrecision');
      $table->boolean('icebergAllowed');
      $table->boolean('ocoAllowed');
      $table->boolean('isSpotTradingAllowed');
      $table->boolean('isMarginTradingAllowed');
      $table->timestamps();
    });
  }
```
```bash
php artisan migrate
```
### database\seeders\SymbolSeeder.php
```php
  public function run()
  {
    Symbol::truncate();
    $exchangeInfo = json_decode(Http::get('https://api.binance.com/api/v3/exchangeInfo'));
    $symbols = $exchangeInfo->symbols;
    foreach ($symbols as $key => $value) {
      Symbol::create([
        'symbol' => $value->symbol,
        'status' => $value->status,
        'baseAsset' => $value->baseAsset,
        'baseAssetPrecision' => $value->baseAssetPrecision,
        'quoteAsset' => $value->quoteAsset,
        'quotePrecision' => $value->quotePrecision,
        'quoteAssetPrecision' => $value->quoteAssetPrecision,
        'icebergAllowed' => $value->icebergAllowed,
        'ocoAllowed' => $value->ocoAllowed,
        'isSpotTradingAllowed' => $value->isSpotTradingAllowed,
        'isMarginTradingAllowed' => $value->isMarginTradingAllowed
      ]);
    }
  }
```
### database\seeders\DatabaseSeeder.php
```php
    $this->call(SymbolSeeder::class);
```
```bash
php artisan db:seed --class=SymbolSeeder
```
### app\Http\Controllers\SymbolController.php
```php
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function exchangeInfo()
  {
    $exchangeInfo = json_decode(Http::get('https://api.binance.com/api/v3/exchangeInfo'));
    $symbols = $exchangeInfo->symbols;
    foreach ($symbols as $key => $value) {
      if (!Symbol::where('symbol', '=', $value->symbol)) {
        Symbol::create([
          'symbol' => $value->symbol,
          'status' => $value->status,
          'baseAsset' => $value->baseAsset,
          'baseAssetPrecision' => $value->baseAssetPrecision,
          'quoteAsset' => $value->quoteAsset,
          'quotePrecision' => $value->quotePrecision,
          'quoteAssetPrecision' => $value->quoteAssetPrecision,
          'icebergAllowed' => $value->icebergAllowed,
          'ocoAllowed' => $value->ocoAllowed,
          'isSpotTradingAllowed' => $value->isSpotTradingAllowed,
          'isMarginTradingAllowed' => $value->isMarginTradingAllowed
        ]);
      }
    }
    return Symbol::where('status', '=', 'TRADING')->get();
  }
```
### routes\web.php
```php
Route::get('/binance/test', [SymbolController::class, 'exchangeInfo']);
```
### app\Models\Symbol.php
```php
  protected $hidden = [
    'id',
    'created_at',
    'updated_at',
  ];
```
```bash
php artisan make:migration add_binance_to_users_table --table=users
```
```php
public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->string('BINANCE_API_KEY')->nullable();
      $table->string('BINANCE_API_SECRET')->nullable();
    });
  }
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('BINANCE_API_KEY', 'BINANCE_API_SECRET');
    });
  }
```
```bash
php artisan migrate
php artisan make:model Trade -a
```
### database\migrations\2021_04_18_070805_create_trades_table.php
```php
  public function up()
  {
    Schema::create('trades', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');
      $table->string('symbol');
      $table->bigInteger('binanceId');
      $table->bigInteger('orderId');
      $table->tinyInteger('orderListId');
      $table->string('price');
      $table->string('qty');
      $table->string('quoteQty');
      $table->string('commission');
      $table->string('commissionAsset');
      $table->bigInteger('time');
      $table->boolean('isBuyer');
      $table->boolean('isMaker');
      $table->boolean('isBestMatch');
      $table->timestamps();
      $table->foreign('user_id')->references('id')->on('users');
    });
  }
```
### app\Models\Trade.php
```php
  protected $hidden = [
    'id',
    'created_at',
    'updated_at',
  ];
  public function user()
  {
    return $this->belongsTo(User::class);
  }
```
### app\Models\User.php
```php
  public function trades()
  {
    return $this->hasMany(Trade::class);
  }
```
```bash
php artisan migrate
```
### routes\web.php
```php
Route::resource('trades', TradeController::class);
Route::resource('symbols', SymbolsController::class);
Route::get('/binance/test', [TradeController::class, 'allMyTrades']);
```
# HNB
```bash
php artisan make:model Hnb -a
```
### database\migrations\2021_04_19_213143_create_hnbs_table.php
```php
  public function up()
  {
    Schema::create('hnbs', function (Blueprint $table) {
      $table->id();
      $table->string('broj_tecajnice');
      $table->date('datum_primjene');
      $table->string('drzava');
      $table->string('drzava_iso');
      $table->string('sifra_valute');
      $table->string('valuta');
      $table->tinyInteger('jedinica');
      $table->string('kupovni_tecaj');
      $table->string('srednji_tecaj');
      $table->string('prodajni_tecaj');
      $table->unique(['datum_primjene', 'valuta']);
      $table->timestamps();
    });
  }
```
```bash
php artisan migrate
```
### app\Models\Hnb.php
```php
  protected $hidden = [
    'id',
    'created_at',
    'updated_at',
  ];
```

```bash
php artisan serve
npm run watch
git add .
git commit -am "hnb [binance]"
```
