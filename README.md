# laravel.test
Laravel test blog

    $ composer create-project --prefer-dist laravel/laravel blog
> - add laravel.test to hosts
> - add laravel.test to httpd-vhosts.conf
> - create laravel db

	$ cd blog
> - git

	$ git init
	$ git add .
	$ git commit -m "Initial Commit - Laravel Framework Installed"
	$ git remote add origin https://github.com/ZdravekSprite/laravel.test.git
	$ git push -u origin master
	$ git branch test0
	$ git checkout test0
	$ git push --set-upstream origin test0
	$ code .
> .editorconfig

	¸¸
	indent_size = 2
	¸¸
> .env

	¸¸
	APP_NAME="Laravel test"
	¸¸
	APP_URL=http://laravel.test
	¸¸
	DB_PORT=3307
	DB_DATABASE=laravel
	DB_USERNAME=root
	DB_PASSWORD=
	¸¸
#
	$ composer update
	$ npm install && npm run dev
> - to avoid errors with migration we need to change engine in database congi file from null to InnoDB

> config\database.php

	¸¸
	'mysql' => [
	¸¸
	'engine' => 'InnoDB',
	¸¸
>

	$ php artisan migrate:fresh
> - git

	$ git add .
	$ git commit -m "start"
	$ composer require laravel/ui --dev
	$ php artisan ui vue --auth
	$ npm install && npm run dev
> - [Less secure app access](https://myaccount.google.com/lesssecureapps?utm_source=google-account&utm_medium=web)
> 
> .env

	¸¸
	MAIL_DRIVER=smtp
	MAIL_HOST=smtp.gmail.com
	MAIL_PORT=465
	MAIL_USERNAME=YOUR_GMAIL@gmail.com
	MAIL_PASSWORD=YOUR_GMAIL_PASSWORD
	MAIL_ENCRYPTION=ssl
	¸¸
> - or [Sign in using App Passwords](https://support.google.com/mail/answer/185833)
> 
> .env

	¸¸
	MAIL_DRIVER=sendmail
	MAIL_FROM_ADDRESS=noreply@domain.com
	MAIL_FROM_NAME=DomainName
	MAIL_HOST=smtp.gmail.com
	MAIL_PORT=587
	MAIL_USERNAME=YOUR_GMAIL@gmail.com
	MAIL_PASSWORD=YOUR_GMAIL_CREATED_APP_PASSWORD
	MAIL_ENCRYPTION=tls
	¸¸
> Clear cache with:

	$ php artisan config:cache
	$ php artisan migrate:fresh
> - git

	$ git add .
	$ git commit -m "auth"
	$ git push
	$ git add .
	$ git commit -m "README.md"
	$ git push
