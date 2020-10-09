# Laravel 8 Test

```bash
echo "# Laravel 8 Test" >> laravel.md
git init
git add .
git commit -a -m "first commit"
git remote add origin https://github.com/ZdravekSprite/Laravel8Test.git
git push origin HEAD --force
git push -u origin master

git checkout -b laravel
git push origin HEAD --force
git push -u origin laravel

composer global require laravel/installer

cd ..
rm -r Laravel8Test
laravel new Laravel8Test
cd Laravel8Test
git init
git remote add origin https://github.com/ZdravekSprite/Laravel8Test.git
git pull origin laravel
git checkout laravel
git add .

npm install && npm run dev
git add .
```

## MySql
> - create laravel8test db

```bash
php artisan migrate:fresh

git checkout -b jetstream
git push origin HEAD --force
git push -u origin jetstream
composer require laravel/jetstream
php artisan jetstream:install livewire --teams
git add .
npm install && npm run dev
php artisan migrate:fresh

git checkout -b socialite
git push origin HEAD --force
git push -u origin socialite
composer require laravel/socialite
npm install && npm run dev
```