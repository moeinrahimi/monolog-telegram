

monolog-telegram
=============

Telegram Handler for php monolog which allows you to log messages into telegram channels using bots .

[![Latest Stable Version](https://poser.pugx.org/rahimi/monolog-telegram/v/stable)](https://packagist.org/packages/rahimi/monolog-telegram)
[![Total Downloads](https://poser.pugx.org/rahimi/monolog-telegram/downloads)](https://packagist.org/packages/rahimi/monolog-telegram)
[![License](https://poser.pugx.org/rahimi/monolog-telegram/license)](https://packagist.org/packages/rahimi/monolog-telegram)

# Screenshot
-------------
![telegram handler demo screenshot](https://i.imgsafe.org/e4/e46f38474b.png)





# Installation
-----------
Install using composer:

```bash
composer require rahimi/monolog-telegram  
```



# Usage
it is just like other monolog handlers, you need to pass below paramaters to telegramhandler object:
- **$token** your bot token provided by BotFather
- **$channel** your telegram channel userName
- **$date_default_timezone_set** is the [timezone identifier](http://php.net/manual/en/timezones.php), like `'UTC'` or '`Europe/Lisbon`', that will be used as the [default timezone](http://php.net/manual/en/function.date-default-timezone-set.php) by all date/time functions (optional, default value `'UTC'`)
- **$dateFormat** pass date format (optional, default value `'Y-m-d H:i:s'`)
- **$timeOut** timeout value in seconds for connection to Telegram servers when sending the log message (optional, default value `100`). Use `0` to wait indefinitely.

# Examples
Now Simply use it like this :

```php
require 'vendor/autoload.php';
use Monolog\Logger;
use rahimi\TelegramHandler\TelegramHandler;
$log = new Logger('TelegramHandler');
$log->pushHandler(new TelegramHandler($token,$channel,'UTC','F j, Y, g:i a',60));


$log->info('hello world !');
/**
* There is 8 level of logging
*/
$log->notice('hello world !');
$log->info('hello world !');
$log->debug('hello world !');
$log->warning('hello world !');
$log->critical('hello world !');
$log->alert('hello world !');
$log->emergency('hello world !');
$log->error('hello world !');


/**
* Optionally you can pass second paramater such as a object
**/
$log->info('user just logged in !',['user'=>$user]);

```

# License
This tool in Licensed under MIT, so feel free to fork it and make it better that it is !
