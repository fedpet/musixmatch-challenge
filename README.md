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

# Endpoints
`POST /api/logs/entrance` to log the event of a device entering through a given station

`POST /api/logs/exit` to log the event of a device leaving through a given station

Request format (valid for both endpoints):
```json
{
  "device": "<device-id>",
  "station": "<station-id>",
  "date": "YYYY-MM-DD HH:mm:ss",
}
```