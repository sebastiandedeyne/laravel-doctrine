# Laravel Doctrine

Doctrine implementation in laravel 5. Work in progress.

## Installation

```
composer require sebdd/laravel-doctrine
```

## Usage

All you need to do is register the service provider in `config/app.php`

```
'providers' => [
    // ...
    'Sebdd\LaravelDoctrine\DoctrineServiceProvider',
];
```

You can also optionally publish the configuration

```
php artisan vendor:publish --provider="Sebdd\LaravelDoctrine\DoctrineServiceProvider"
```

If you're using the user provider, you'll also need to make sure `auth.driver` is set to "doctrine" and `auth.model` is correct.

## Configuration

### Options

#### user_provider

**enabled**  bool (true)  Registers a doctrine user provider

**columns.identifier**  string ('id')  The user identifier column

**columns.remember_token**  string ('id')  The user remember_token column

## Features

[x] Entity Manager
[x] Console commands
[x] User provider
[x] Basic configuration
[] Test coverage
[] Improve configuration and extensibility
