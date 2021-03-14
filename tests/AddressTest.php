<?php

use Speelpenning\PostcodeNl\Address;

class AddressTest extends TestCase
{
    public function testAttributesCanBeAccessed(): void
    {
        $address = new Address([
            'postcode' => '1000AA',
            'houseNumber' => 1
        ]);

        self::assertEquals('1000AA', $address->postcode);
        self::assertEquals(1, $address->houseNumber);
    }

    public function testAddressCanBeConvertedToArray(): void
    {
        $attributes = [
            'postcode' => '1000AA',
            'houseNumber' => 1
        ];

        $address = new Address($attributes);
        self::assertEquals($attributes, $address->toArray());
    }
    public function testAddressCanBeConvertedToJson(): void
    {
        $attributes = [
            'postcode' => '1000AA',
            'houseNumber' => 1
        ];

        $address = new Address($attributes);
        self::assertJsonStringEqualsJsonString(json_encode($attributes), $address->toJson());
    }
}
