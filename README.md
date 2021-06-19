# Getting started
Run `cd src` and `composer install` to download the required
dependencies

Run `docker compose up` then open a CLI on the backend
and run `php artisan migrate --force --seed` to initialize
the DB and create some test data

# Tests
Run `php artisan test` to execute automated tests

# DB Schema
![db schema](db-schema.png)