<?php

use Speelpenning\PostcodeNl\Address;

class AddressTest extends TestCase
{
    public function testAttributesCanBeAccessed()
    {
        $address = new Address([
            'postcode' => '1000AA',
            'houseNumber' => 1
        ]);

        $this->assertEquals('1000AA', $address->postcode);
        $this->assertEquals(1, $address->houseNumber);
    }

    public function testAddressCanBeConvertedToArray()
    {
        $attributes = [
            'postcode' => '1000AA',
            'houseNumber' => 1
        ];

        $address = new Address($attributes);
        $this->assertArraySubset($attributes, $address->toArray());
    }
    public function testAddressCanBeConvertedToJson()
    {
        $attributes = [
            'postcode' => '1000AA',
            'houseNumber' => 1
        ];

        $address = new Address($attributes);
        $this->assertJsonStringEqualsJsonString(json_encode($attributes), $address->toJson());
    }
}
