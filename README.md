# Postcode.nl client for Laravel 5.1

[![Build Status](https://travis-ci.org/Speelpenning-nl/laravel-postcode-nl.svg)](https://travis-ci.org/Speelpenning-nl/laravel-postcode-nl)
[![Latest Stable Version](https://poser.pugx.org/speelpenning/laravel-postcode-nl/v/stable)](https://packagist.org/packages/speelpenning/laravel-postcode-nl)
[![Latest Unstable Version](https://poser.pugx.org/speelpenning/laravel-postcode-nl/v/unstable)](https://packagist.org/packages/speelpenning/laravel-postcode-nl)
[![License](https://poser.pugx.org/speelpenning/laravel-postcode-nl/license)](https://packagist.org/packages/speelpenning/laravel-postcode-nl)

A client using the Postcode.nl REST API for Dutch address verification.

## Installation 

Pull the package in through Composer:

```bash
composer require speelpenning/laravel-postcode-nl
```

Next, register an account with Postcode.nl to obtain a key and secret. See https://api.postcode.nl/#register for 
further information. Once you have a key and secret, store them in your .env file.

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

```ini
POSTCODENL_KEY=<your-api-key>
POSTCODENL_SECRET=<your-secret>
```

### Enable routes (optional)

This package comes with a ready to use JSON API, which is disabled by default. You can enable it like so:  

```ini
POSTCODENL_ENABLE_ROUTES=true
```

### Timeout (in seconds, optional)

By default, the client waits 10 seconds for a response. You may set a different timeout.

```ini
POSTCODENL_TIMEOUT=<timeout-in-seconds>
```
