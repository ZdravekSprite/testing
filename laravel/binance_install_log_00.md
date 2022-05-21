# Binance Laravel Project

## install

```bash
# git checkout -b laravel
composer create-project --prefer-dist laravel/laravel binance
cd binance
```

### .gitignore

```text
/composer.lock
/package-lock.json
```

### .editorconfig

```ts
end_of_line = crlf
indent_size = 2
```

### MySql

> - create laravel_binance db

### .env

```ts
APP_NAME="Binance Laravel"
DB_DATABASE=laravel_binance
```

## Laravel Breeze

```bash
composer require laravel/breeze --dev
php artisan breeze:install
```

## Binance

### Binance model (+ factory + migration + seeder + controller)

```bash
php artisan make:model Binance -a
```

### Binance api controller

```bash
php artisan make:controller BApi
```

### Binance http controller

```bash
php artisan make:controller BHttp
```

```bash
npm install && npm run dev
php artisan migrate:fresh
php artisan route:cache
php artisan serve
git add .
git commit -am "make:controller BApi + BHttp [laravel]"
```
