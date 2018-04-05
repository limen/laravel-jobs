# Jobs helps to organize jobs easily. For Laravel user.

[![Build Status](https://travis-ci.org/limen/laravel-jobs.svg?branch=master)](https://travis-ci.org/limen/laravel-jobs)
[![Packagist](https://img.shields.io/packagist/l/limen/laravel-jobs.svg?maxAge=2592000)](https://packagist.org/packages/limen/laravel-jobs)

This package is based on [php-jobs](https://github.com/limen/php-jobs)

## Installation

Recommend to install via [composer](https://getcomposer.org/ "").

```bash
composer require "limen/laravel-jobs"
```

Publish the service provider. 
The migrations and config file would be copied to your app directory. 
Check 'database/migrations' and 'config/jobs.php'.

```bash
php artisan vendor:publish --provider="Limen\Laravel\Jobs\JobsServiceProvider"
```

Run migrations.

```bash
php artisan jobs:install
```

Run tests. Come to the package directory and run

```bash
phpunit --bootstrap tests/bootstrap.php tests/
```

## Usage

see 
+ [examples](https://github.com/limen/laravel-jobs/tree/master/src/Examples)
+ [tests](https://github.com/limen/laravel-jobs/tree/master/tests)

Do your own business and Have a happy trip.
