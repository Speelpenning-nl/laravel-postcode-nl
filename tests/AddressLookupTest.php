<?php

use Illuminate\Contracts\Validation\ValidationException;
use Speelpenning\PostcodeNl\Exceptions\AddressNotFound;
use Speelpenning\PostcodeNl\Exceptions\Unauthorized;
use Speelpenning\PostcodeNl\Services\AddressLookup;

class AddressLookupTest extends TestCase {

    public function testCredentialsAreSet()
    {
        $auth = config('postcode-nl.requestOptions.auth');

        $this->assertNotEmpty(array_get($auth, 0));
        $this->assertNotEmpty(array_get($auth, 1));
    }

    public function testInvalidCredentialsThrowUnauthorized()
    {
        $this->setExpectedException(Unauthorized::class);

        config([
            'postcode-nl.requestOptions.auth' => [
                'invalid', 'credentials'
            ]
        ]);

        $lookup = app(AddressLookup::class);
        $lookup->lookup('1000AA', 1);
    }

    public function testExistingAddressReturnsAnAddress()
    {
        $lookup = app(AddressLookup::class);
        $address = $lookup->lookup('1000AA', 1);

        $this->assertInstanceOf(Speelpenning\PostcodeNl\Address::class, $address);
    }

    public function testNonExistingAddressThrowsAddressNotFound()
    {
        $this->setExpectedException(AddressNotFound::class);

        $lookup = app(AddressLookup::class);
        $lookup->lookup('9999ZZ', 99999);
    }

    public function testInvalidLookupThrowsValidationException()
    {
        $this->setExpectedException(ValidationException::class);

        $lookup = app(AddressLookup::class);
        $lookup->lookup('invalid', 'address');
    }

}
