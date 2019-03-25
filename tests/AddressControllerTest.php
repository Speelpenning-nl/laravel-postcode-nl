<?php

class AddressControllerTest extends TestCase
{
    public function setUp(): void
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
            ->assertStatus(401);
    }

    public function testSuspendedCredentialsThrowAccountSuspended()
    {
        config([
            'postcode-nl.requestOptions.auth' => [
                'H2E4y1m7elD6gt73vTCjw9tWwayV8eUHrpBv1XpOfTw', 'LNW0bPOWn0qHc2iSDDv8NifUQlucgfehFcSHyix0kyt'
            ]
        ]);

        $this->get(route('postcode-nl::address', ['1000AA', 1]))
            ->assertStatus(403);
    }

    public function testExistingAddressReturnsAnAddress()
    {
        $this->get(route('postcode-nl::address', ['1000AA', 1]))
            ->assertStatus(200)
            ->assertJson([
                'postcode' => '1000AA',
                'houseNumber' => 1,
            ]);
    }

    public function testNonExistingAddressThrowsAddressNotFound()
    {
        $this->get(route('postcode-nl::address', ['9999ZZ', 99999]))
            ->assertStatus(404);
    }

    public function testInvalidLookupThrowsValidationException()
    {
        $this->get(route('postcode-nl::address', ['invalid', 'address']))
            ->assertStatus(400);
    }
}
