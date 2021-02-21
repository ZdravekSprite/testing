```
git checkout -b laravel
composer create-project --prefer-dist laravel/laravel vanilla
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
## MySql
> - create laravel_vanilla db
### .env
```
APP_NAME="Vanilla Laravel"
DB_DATABASE=laravel_vanilla
```

```
npm install && npm run dev
php artisan migrate:fresh
php artisan serve
git add .
git commit -am "Initial Commit - Laravel Framework Installed [laravel]"
```