<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Speelpenning\PostcodeNl\Address;
use Speelpenning\PostcodeNl\Exceptions\AccountSuspended;
use Speelpenning\PostcodeNl\Exceptions\AddressNotFound;
use Speelpenning\PostcodeNl\Exceptions\Unauthorized;
use Speelpenning\PostcodeNl\Services\AddressLookup;

class AddressLookupTest extends TestCase
{
    public function testCredentialsAreSet(): void
    {
        $auth = config('postcode-nl.requestOptions.auth');

        self::assertNotEmpty(Arr::get($auth, 0));
        self::assertNotEmpty(Arr::get($auth, 1));
    }

    public function testInvalidCredentialsThrowUnauthorized(): void
    {
        $this->expectException(Unauthorized::class);

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $client->expects(self::once())
            ->method('get')
            ->willThrowException(new ClientException('Unauthorized', new Request('GET', '/'), new Response(401)));
        app()->instance(Client::class, $client);

        app(AddressLookup::class)->lookup('1000AA', 1);
    }

    public function testSuspendedCredentialsThrowAccountSuspended(): void
    {
        $this->expectException(AccountSuspended::class);

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $client->expects(self::once())
            ->method('get')
            ->willThrowException(new ClientException('Unauthorized', new Request('GET', '/'), new Response(403)));
        app()->instance(Client::class, $client);

        app(AddressLookup::class)->lookup('1000AA', 1);
    }

    public function testExistingAddressReturnsAnAddress(): void
    {
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $client->expects(self::once())
            ->method('get')
            ->willReturn(new Response(200, [], file_get_contents(__DIR__.'/nl-response.json')));
        app()->instance(Client::class, $client);

        $address = app(AddressLookup::class)->lookup('2012ES', 30);

        self::assertInstanceOf(Address::class, $address);
    }

    public function testNonExistingAddressThrowsAddressNotFound(): void
    {
        $this->expectException(AddressNotFound::class);

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $client->expects(self::once())
            ->method('get')
            ->willThrowException(new ClientException('Unauthorized', new Request('GET', '/'), new Response(404)));
        app()->instance(Client::class, $client);

        app(AddressLookup::class)->lookup('9999ZZ', 99999);
    }

    public function testInvalidLookupThrowsValidationException(): void
    {
        $this->expectException(ValidationException::class);

        app(AddressLookup::class)->lookup('invalid', 12345);
    }
}
