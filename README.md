# Laravel Latch 

*Laravel package for Latch SDK.*

More info about Latch: https://latch.elevenpaths.com/

Official Latch php SDK: https://github.com/ElevenPaths/latch-sdk-php

## Install

This package requires Laravel 4.1.*+

- Add repository to composer.json:

    ```json
    "xinax/laravel-latch": "dev-master"
    ```

- Update composer:

    ```bash
    composer update
    ```

- Register the service provider in app.php

    ```php
    'Xinax\LaravelLatch\LaravelLatchServiceProvider',
    ```

## Configuration

- Publish configuration file:

    ```bash
    php artisan config:publish xinax/laravel-latch
    ```
    
- Set application ID and secret in:

    ```php
    app/config/packages/xinax/laravel-latch/config.php
    ```
    
## Usage

All original method names were kept. Refer to original readme for updated documentation.

#### Pair register
```php
    $accountID = LaravelLatch::pair($pairCode);
```

#### Latch status check
```php
    try{
        LaravelLatch::status($accountID);
    } catch (Xinax\LaravelLatch\Exceptions\ClosedLatchException $e){
        // ... latch protection logic ...
    } catch (Xinax\LaravelLatch\Exceptions\LatchErrorException $e){ 
        // ... crisis logic (depends of your security policy) ...
    }
```

#### Unpair
```php
    LaravelLatch::unpair($accountID);
```

