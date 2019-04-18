App Demo 
==================================================

This sample is based on *Zend 3* framework. 

## Installation

You need to have Apache 2.4 HTTP server, PHP v.5.6 or later with `gd` and `intl` extensions, and MySQL 5.6 or later.

Download the sample to some directory (it can be your home dir or `/var/www/html`) and run Composer as follows:

```
composer install
```

The command above will install the dependencies (Zend Framework and Doctrine).

Enable development mode:

```
composer development-enable
```

Create `config/autoload/local.php` config file by copying its distrib version:

```
cp config/autoload/local.php.dist config/autoload/local.php
```

Edit `config/autoload/local.php` and set database password parameter.

Login to MySQL client:

```
mysql -u root -p
```

Create database:

```
CREATE DATABASE sample;
```

Run database migrations to intialize database schema:

```
./vendor/bin/doctrine-module migrations:migrate
```

Now you should be able to see the Role Demo website by visiting the link "http://localhost/". 
 
## License

This code is provided under the [BSD-like license](https://en.wikipedia.org/wiki/BSD_licenses). 

## Contributing

If you found a mistake or a bug, please report it using the [Mail](grtumanyan@gmail.com). 
Your feedback is highly appreciated.
