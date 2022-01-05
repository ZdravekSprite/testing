# mail

## Step 1 − We will now send an email from Gmail account and for that you need to configure your Gmail account in Laravel environment file - .env file. Enable 2-step verification in your Gmail account and create an application specific password followed by changing the .env parameters as shown below.

### .env
```
MAIL_DRIVER = smtp
MAIL_HOST = smtp.gmail.com
MAIL_PORT = 587
MAIL_USERNAME = your-gmail-username
MAIL_PASSWORD = your-application-specific-password
MAIL_ENCRYPTION = tls
```

## Step 2 − After changing the .env file execute the below two commands to clear the cache and restart the Laravel server.
```bash
php artisan config:cache
```

## Step 3 − Create a controller called MailController by executing the following command.
```bash
php artisan make:controller MailController
```

## Step 4 − After successful execution, you will receive the following output −
```bash
Controller created successfully
```

## Step 5 − Copy the following code in app/Http/Controllers/MailController.php file.

### app/Http/Controllers/MailController.php
```php
<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {
   public function basic_email() {
      $data = array('name'=>"Virat Gandhi");
   
      Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
         $message->from('xyz@gmail.com','Virat Gandhi');
      });
      echo "Basic Email Sent. Check your inbox.";
   }
   public function html_email() {
      $data = array('name'=>"Virat Gandhi");
      Mail::send('mail', $data, function($message) {
         $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel HTML Testing Mail');
         $message->from('xyz@gmail.com','Virat Gandhi');
      });
      echo "HTML Email Sent. Check your inbox.";
   }
   public function attachment_email() {
      $data = array('name'=>"Virat Gandhi");
      Mail::send('mail', $data, function($message) {
         $message->to('abc@gmail.com', 'Tutorials Point')->subject
            ('Laravel Testing Mail with Attachment');
         $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
         $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('xyz@gmail.com','Virat Gandhi');
      });
      echo "Email Sent with attachment. Check your inbox.";
   }
}
```

## Step 6 − Copy the following code in resources/views/mail.blade.php file.

### resources/views/mail.blade.php
```php
<h1>Hi, {{ $name }}</h1>
l<p>Sending Mail from Laravel.</p>
```

## Step 7 − Add the following lines in app/Http/routes.php.

### routes\web.php
```php
Route::get('sendbasicemail','MailController@basic_email');
Route::get('sendhtmlemail','MailController@html_email');
Route::get('sendattachmentemail','MailController@attachment_email');
```

## Step 8 − Visit the following URL to test basic email.
### http://localhost:8000/sendbasicemail

## Step 9 − The output screen will look something like this. Check your inbox to see the basic email output.
```
Basic Email Sent. Check your inbox.
```

## Step 10 − Visit the following URL to test the HTML email.
### http://localhost:8000/sendhtmlemail

## Step 11 − The output screen will look something like this. Check your inbox to see the html email output.
```
HTML Email Sent. Check your inbox.
```

## Step 12 − Visit the following URL to test the HTML email with attachment.
### http://localhost:8000/sendattachmentemail

## Step 13 − You can see the following output
```
Email Sent with attachment. Check your inbox.
```

## Note − In the MailController.php file the email address in the from method should be the email address from which you can send email address. Generally, it should be the email address configured on your server.