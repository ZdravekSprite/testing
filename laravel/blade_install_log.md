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
php artisan migrate:fresh --seed
npm install && npm run dev
php artisan serve
composer require laravel/breeze --dev
php artisan breeze:install
php artisan make:model Settings -a
```

```bash
git add . && git commit -am "blade v002"
git push
```
