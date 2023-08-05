# reinstall vanilla

- .env

```edit
APP_NAME="Blade Laravel"
DB_CONNECTION=sqlite
SUPER_ADMIN_NAME="Super Admin"
SUPER_ADMIN_EMAIL=super@admin.com
SUPER_ADMIN_PASS=password
```

```bash
touch database/database.sqlite
composer require laravel/breeze --dev
php artisan breeze:install
php artisan make:model Settings -a
php artisan make:model Role -a
php artisan make:provider BladeServiceProvider
php artisan make:middleware AccessAdmin
php artisan make:controller Admin\\UserController -mUser
php artisan make:controller Admin\\ImpersonateController
php artisan make:middleware Impersonate

php artisan migrate:fresh --seed
npm install && npm run dev
php artisan serve
```

```bash
git add . && git commit -am "blade v004"
git push
```
