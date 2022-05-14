# Laravel

```bash
composer create-project --prefer-dist laravel/laravel laravel
```

## .gitignore

```txt
composer.lock
package-lock.json
```

## -> .editorconfig

```ts
end_of_line = crlf
indent_size = 2
```

## MySql

> - create laravel_api db

## .env

```ts
APP_NAME="Laravel Backend"
DB_DATABASE=laravel_api
```

```bash
cd laravel
npm install && npm run dev
php artisan migrate:fresh
php artisan serve
git add .
git commit -am "Initial Commit - Laravel Framework [api]"
```
