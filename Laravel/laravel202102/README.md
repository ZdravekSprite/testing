# Laravel 202102

```bash
git checkout -b laravel
composer create-project laravel/laravel laravel202102
cd laravel202102
npm install && npm run dev
composer update
git add .
git commit -am "create-project npm install && npm run dev 202102 [laravel]"
php artisan key:generate
php artisan serve
composer require laravel/jetstream
php artisan jetstream:install inertia
npm install && npm run dev
php artisan migrate
git add .
git commit -am "php artisan jetstream:install inertia 202102 [laravel]"
```