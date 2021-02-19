### u mysql CREATE DATABASE laravel_ers;
```
composer create-project --prefer-dist laravel/laravel ers
```
### .gitignore
```
composer.lock
package-lock.json
```
### -> .editorconfig
```
indent_size = 2
```
### .env
```
APP_NAME="Laravel ERS"
DB_DATABASE=laravel_ers
```

## Laravel Breeze

```
composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate:fresh
git add .
git commit -am "new Laravel Breeze [ers_auth]"
```