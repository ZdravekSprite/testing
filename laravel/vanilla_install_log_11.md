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
```bash
php artisan serve
npm run watch
git add .
git commit -am "symbol [binance]"
```
