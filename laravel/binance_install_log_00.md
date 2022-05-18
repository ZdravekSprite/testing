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

```bash
npm install && npm run dev
php artisan migrate:fresh
php artisan serve
git add .
git commit -am "Initial Commit - Laravel Framework Installed [laravel]"
```
