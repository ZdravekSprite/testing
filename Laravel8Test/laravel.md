# Laravel 8 Test

```bash
echo "# Laravel 8 Test" >> laravel.md
git init
git add .
git commit -a -m "first commit"
git remote add origin https://github.com/ZdravekSprite/Laravel8Test.git
git push -u origin master
git push origin HEAD --force
git commit -a -m "git push origin HEAD --force"

git push
git checkout -b laravel
git commit -a -m "first commit [laravel]"
git push -u origin laravel

cd ..
composer global require laravel/installer
laravel new Laravel8Test -f
cd Laravel8Test
git init
git remote add origin https://github.com/ZdravekSprite/Laravel8Test.git
git checkout -b laravel
git pull origin laravel
code .
git add .
git commit -a -m "install [laravel]"
git push -u origin laravel
npm install && npm run dev
git add .
git commit -a -m "npm install && npm run dev [laravel]"
```

## MySql
> - create laravel8test db

```bash
php artisan migrate
git add .
git commit -a -m "migrate [laravel]"

git push
git checkout -b jetstream
git commit -a -m "first commit [jetstream]"
git push -u origin jetstream

composer require laravel/jetstream
php artisan jetstream:install livewire --teams
git add .
git commit -a -m "php artisan jetstream:install livewire --teams [jetstream]"
npm install && npm run dev
git commit -a -m "npm install && npm run dev [jetstream]"
php artisan migrate
git commit -a -m "migrate [jetstream]"

git push
git checkout -b socialite
git commit -a -m "first commit [socialite]"
git push -u origin socialite

composer require laravel/socialite
git commit -a -m "composer require laravel/socialite [socialite]"
npm install && npm run dev
git commit -a -m "npm install && npm run dev [socialite]"
```