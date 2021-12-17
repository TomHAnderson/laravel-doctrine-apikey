# Laravel Doctrine ApiKey

[![Build Status](https://github.com/API-Skeletons/laravel-doctrine-apikey/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/API-Skeletons/laravel-doctrine-apikey/actions/workflows/continuous-integration.yml?query=branch%3Amain)
[![Code Coverage](https://codecov.io/gh/API-Skeletons/laravel-doctrine-apikey/branch/main/graphs/badge.svg)](https://codecov.io/gh/API-Skeletons/laravel-doctrine-apikey/branch/main)
[![PHP Version](https://img.shields.io/badge/PHP-8.0-blue)](https://img.shields.io/badge/PHP-8.0-blue)
[![Total Downloads](https://poser.pugx.org/api-skeletons/laravel-doctrine-apikey/downloads)](//packagist.org/packages/api-skeletons/laravel-doctrine-apikey)
[![License](https://poser.pugx.org/api-skeletons/laravel-doctrine-apikey/license)](//packagist.org/packages/api-skeletons/laravel-doctrine-apikey)

This repository provides a driver for Doctrine which can be added to an existing entity manager.  
The driver provies a set of entities which enable ApiKey authorization through HTTP middleware.
Scopes are supported!  This was the missing piece of other repositories which catalyzed the creation of this library.

## Installation

Run the following to install this library using [Composer](https://getcomposer.org/):

```bash
composer require api-skeletons/laravel-doctrine-apikey
```

## Quick Start

Add Service Provider to app.php
```php
    'providers' => [
        ...
        ApiSkeletons\Laravel\Doctrine\ApiKey\ServiceProvider::class,
    ],
```

Add the route middleware to Http Kernel
```php
use ApiSkeletons\Laravel\Doctrine\ApiKey\Http\Middleware\AuthorizeApiKey;

$routeMiddleware = [
    ...
    'auth.apiKey' => AuthorizeApiKey:class
];
```


Initialize the ApiKey service for your entity manager in `App\Providers\AppServiceProvider`
```php
use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;

public function boot()
{
    app(ApiKeyService::class)->init(app('em'));
}
```

Add an API key through the console
```shell
$ php artisan apikey:generate yourapikeyname
```

Add the middleware to a protected route
```php
Route::name('api.resource::fetch')
    ->get('resource', 'ResourceController::fetch')
    ->middleware('auth.apikey');
```

Begin making requests to your ApiKey protected resource using you key as a Bearer token in the Authorization header
```sh
Authorization: Bearer {key}
```

