<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Atarim Test URL Shortener

This is a test application to show how you can shorten a URL using a base62 algorithm based on a database ID.

### Installation

Please ensure you have Docker Desktop installed and running before spinning up this project

1. Build the vendor dir using the command ``composer install``.
2. Start the Laravel Sail environment using the command ``./vendor/bin/sail up`` from the applications root dir.
3. Run the database migration using the command ``./vendor/bin/sail artisan migrate`` from the applications root dir.
4. If you wish to use the seeded test user rather than create your own, run the command ``./vendor/bin/sail artisan db:seed``.
5. To build the node modules, run the ``./vendor/bin/sail npm install`` command.

### Running

A Postman collection has been included to enable you to easily run the various API calls. This can be found in the projects root dir and is named ``atarim_test_postman_collection.json``.

The API has been secured using basic token authentication, so you will need to obtain a token before running any authenticated API calls. If you run the seeder it will create a user for you to use. Additionally, if you run the login call via Postman it will save the token for you as a variable, so it doesn't need to be added to subsequent API calls.

### Testing

Tests have been written using Pest PHP and can be run using the command ``./vendor/bin/sail test``.
