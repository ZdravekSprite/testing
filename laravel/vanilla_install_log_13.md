# kad se clonira repo
```bash
git pull
npm install && npm run dev
composer update
```
### .env.example -> .env
```
APP_NAME="Vanilla Laravel"
DB_DATABASE=laravel_vanilla

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=eu
```
```bash
php artisan key:generate
```
## MySql
> - create laravel_vanilla db
```bash
php artisan migrate:fresh --seed
php artisan serve
npm run watch
```
### lotto model (+ factory + migration + seeder + controller)
```bash
php artisan make:model Settings -a
```

```bash
git add .
git commit -am "role view"
git push
```
