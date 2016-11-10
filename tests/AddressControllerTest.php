<?php

class AddressControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        config([
            'postcode-nl.enableRoutes' => true,
        ]);
    }

    public function testInvalidCredentialsThrowUnauthorized()
    {
        config([
            'postcode-nl.requestOptions.auth' => [
                'invalid', 'credentials'
            ]
        ]);

        $this->get(route('postcode-nl::address', ['1000AA', 1]))
            ->assertResponseStatus(401);
    }

    public function testExistingAddressReturnsAnAddress()
    {
        $this->get(route('postcode-nl::address', ['1000AA', 1]))
            ->assertResponseOk()
            ->seeJson([
                'postcode' => '1000AA',
                'houseNumber' => 1,
            ]);
    }

    public function testNonExistingAddressThrowsAddressNotFound()
    {
        $this->get(route('postcode-nl::address', ['9999ZZ', 99999]))
            ->assertResponseStatus(404);
    }

    public function testInvalidLookupThrowsValidationException()
    {
        $this->get(route('postcode-nl::address', ['invalid', 'address']))
            ->assertResponseStatus(400);
    }
}
