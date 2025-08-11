# Postcode.eu client for Laravel 11 & 12

![Packagist Version](https://img.shields.io/packagist/v/speelpenning/laravel-postcode-nl)
![Packagist License](https://img.shields.io/packagist/l/speelpenning/laravel-postcode-nl)

A client using the Postcode.eu REST API for Dutch address verification.

## Installation 

Pull the package in through Composer:

```bash
composer require speelpenning/laravel-postcode-nl
```

Next, register an account with Postcode.eu to obtain a key and secret, required to authenticate with the API. See 
https://account.postcode.eu/register/api/en_GB for more information. Once you have a key and secret, store them 
in your .env file.

Add the following service provider to your application config:

```php
Speelpenning\PostcodeNl\PostcodeNlServiceProvider::class,
```

Walk through the configuration section to make things work.
 
## Usage

There are two ways to use the address lookup: by injecting the address lookup service in your code or using the 
AddressController that is shipped with the package.

### Dependency injection

Example:

```php
<?php

use Exception;
use Speelpenning\PostcodeNl\Services\AddressLookup;

class AddressDumper {

    /**
     * @var AddressLookup
     */
    protected $lookup;
    
    /**
     * Create an address dumper instance.
     *
     * @param AddressLookup $lookup
     */
    public function __construct(AddressLookup $lookup)
    {
        $this->lookup = $lookup;
    }
    
    /**
     * Dumps the address details on screen.
     *
     * @param string $postcode
     * @param int $houseNumber
     * @param null|string $houseNumberAddition
     */
    public function dump($postcode, $houseNumber, $houseNumberAddition = null)
    {
        try {
            $address = $this->lookup->lookup($postcode, $houseNumber, $houseNumberAddition);
            dd($address);
        }
        catch (Exception $e) {
            exit('Ow, that went wrong...');
        }
    }

}
```

### Using the JSON API

In order to use the API, enabled it in the configuration. When enabled, the following route is available:
                                                                         
```php
route('postcode-nl::address', [$postcode, $houseNumber, $houseNumberAddition = null]);
```

or use the following URL (e.g. for AJAX calls):

```
/postcode-nl/address/{postcode}/{houseNumber}/{houseNumberAddition?}
```

## Configuration

### Credentials (required)

The key and secret are used for authentication. Without them, you cannot use the service. 

```dotenv
POSTCODENL_KEY=<your-api-key>
POSTCODENL_SECRET=<your-secret>
```

### Enable routes (optional)

This package comes with a ready to use JSON API, which is disabled by default. You can enable it like so:  

```dotenv
POSTCODENL_ENABLE_ROUTES=true
```

### Timeout (in seconds, optional)

By default, the client waits 10 seconds for a response. You may set a different timeout.

```dotenv
POSTCODENL_TIMEOUT=<timeout-in-seconds>
```
