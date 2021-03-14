<?php

use Illuminate\Validation\ValidationException;
use Speelpenning\PostcodeNl\Address;
use Speelpenning\PostcodeNl\Exceptions\AccountSuspended;
use Speelpenning\PostcodeNl\Exceptions\AddressNotFound;
use Speelpenning\PostcodeNl\Exceptions\Unauthorized;
use Speelpenning\PostcodeNl\Services\AddressLookup;
use Speelpenning\PostcodeNl\Validators\AddressLookupValidator;

class AddressControllerTest extends TestCase
{
    public function testHandleUnauthorized(): void
    {
        $service = $this->getMockBuilder(AddressLookup::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['lookup'])
            ->getMock();
        $service->expects(self::once())
            ->method('lookup')
            ->willThrowException(new Unauthorized());
        app()->instance(AddressLookup::class, $service);

        $this->get(route('postcode-nl::address', ['1000AA', 1]))
            ->assertStatus(401);
    }

    public function testHandleAccountSuspended(): void
    {
        $service = $this->getMockBuilder(AddressLookup::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['lookup'])
            ->getMock();
        $service->expects(self::once())
            ->method('lookup')
            ->willThrowException(new AccountSuspended());
        app()->instance(AddressLookup::class, $service);

        $this->get(route('postcode-nl::address', ['1000AA', 1]))
            ->assertStatus(403);
    }

    public function testExistingAddressReturnsAnAddress(): void
    {
        $service = $this->getMockBuilder(AddressLookup::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['lookup'])
            ->getMock();
        $service->expects(self::once())
            ->method('lookup')
            ->willReturn(new Address([
                'postcode' => '1000AA',
                'houseNumber' => 1,
            ]));
        app()->instance(AddressLookup::class, $service);

        $this->get(route('postcode-nl::address', ['1000AA', 1]))
            ->assertStatus(200)
            ->assertJson([
                'postcode' => '1000AA',
                'houseNumber' => 1,
            ]);
    }

    public function testNonExistingAddressThrowsAddressNotFound(): void
    {
        $service = $this->getMockBuilder(AddressLookup::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['lookup'])
            ->getMock();
        $service->expects(self::once())
            ->method('lookup')
            ->willThrowException(new AddressNotFound());
        app()->instance(AddressLookup::class, $service);

        $this->get(route('postcode-nl::address', ['9999ZZ', 99999]))
            ->assertStatus(404);
    }

    public function testInvalidLookupThrowsValidationException(): void
    {
        $service = $this->getMockBuilder(AddressLookup::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['lookup'])
            ->getMock();
        $service->expects(self::once())
            ->method('lookup')
            ->willThrowException(new ValidationException(app(AddressLookupValidator::class)));
        app()->instance(AddressLookup::class, $service);

        $this->get(route('postcode-nl::address', ['invalid', 'address']))
            ->assertStatus(400);
    }
}
