# Postcode.nl client for Laravel 5.1

A client using the Postcode.nl REST API for Dutch address verification.

## Usage

Pull the package in through Composer:

```bash
composer require speelpenning/laravel-postcode-nl
```

Next, register an account with Postcode.nl to obtain a key and secret. See https://api.postcode.nl/#register for 
further information. Once you have a key and secret, store them in your .env file.

Walk through the configuration section to make things work. 

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

Now you can use the following route:

```php
route('postcode-nl::address', [$postcode, $houseNumber, $houseNumberAddition = null]);
```

which gives you the following URL:

```
/postcode-nl/address/{postcode}/{houseNumber}/{houseNumberAddition?}
```

### Timeout (in seconds, optional)

By default, the client waits 10 seconds for a response. You may set a different timeout.

```ini
POSTCODENL_TIMEOUT=<timeout-in-seconds>
```
