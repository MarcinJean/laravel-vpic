# Laravel VPic

A Laravel package for decoding Vehicle Identification Numbers (VINs) and World Manufacturer Identifiers (WMIs) using the NHTSA vPIC API, with built‑in caching via a database table.

## Features

* Decode VINs (`decodevinextended`) and cache results as Eloquent models
* Decode WMIs (`decodewmi`) on demand
* Automatic error handling for invalid or malformed VINs
* Clean separation of API logic and persistence
* Artisan migration stub for setting up the `vpic_vehicles` table

## Installation

1. Require the package via Composer:

   ```bash
   composer require marcinjean/laravel-vpic
   ```

2. Publish the migration and migrate:

   ```bash
   php artisan vendor:publish --tag=migrations
   php artisan migrate
   ```

## Usage

Use the `VPic` facade or inject `MarcinJean\LaravelVPic\VPicService`:

```php
use MarcinJean\LaravelVPic\Facades\VPic;
use MarcinJean\LaravelVPic\Exceptions\VPicException;

try {
    $vehicle = VPic::decode('1HGCM82633A004352');
    echo $vehicle->year;
} catch (VPicException $e) {
    // handle invalid VIN
}
```

Decode a WMI:

```php
$wmiData = VPic::decodeWmi('1HG');
```

## Supported Methods

* `decode(string $vin): Vehicle`
* `decodeWmi(string $wmi): array`

## Database Schema

The package publishes a migration stub for the `vpic_vehicles` table with columns:

* `vin` (PK)
* `year`, `make`, `model`
* `extra` (JSON for all other decoded fields)
* Timestamps


## License

MIT
