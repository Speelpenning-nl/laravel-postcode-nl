<?php

namespace Speelpenning\PostcodeNl\Services;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Validation\ValidationException;
use JsonException;
use Speelpenning\PostcodeNl\Address;
use Speelpenning\PostcodeNl\Exceptions\AccountSuspended;
use Speelpenning\PostcodeNl\Exceptions\AddressNotFound;
use Speelpenning\PostcodeNl\Exceptions\Unauthorized;
use Speelpenning\PostcodeNl\Http\PostcodeNlClient;
use Speelpenning\PostcodeNl\Validators\AddressLookupValidator;
use function array_filter;
use function compact;
use function json_decode;
use function sprintf;

class AddressLookup
{
    private const BASE_URI = 'https://api.postcode.eu/nl/v1/addresses';

    /**
     * @var AddressLookupValidator
     */
    protected $validator;

    /**
     * @var PostcodeNlClient
     */
    protected $client;

    /**
     * Create a new service instance.
     *
     * @param AddressLookupValidator $validator
     * @param PostcodeNlClient $client
     */
    public function __construct(AddressLookupValidator $validator, PostcodeNlClient $client)
    {
        $this->validator = $validator;
        $this->client = $client;
    }

    /**
     * Performs an address lookup.
     *
     * @param string $postcode
     * @param int $houseNumber
     * @param null|string $houseNumberAddition
     * @return Address
     * @throws ValidationException
     * @throws AccountSuspended
     * @throws AddressNotFound
     * @throws GuzzleException
     * @throws JsonException
     * @throws Unauthorized
     */
    public function lookup(string $postcode, int $houseNumber, string $houseNumberAddition = null): Address
    {
        $this->validator->validate(array_filter(compact('postcode', 'houseNumber', 'houseNumberAddition')));

        $uri = $this->getUri($postcode, $houseNumber, $houseNumberAddition);
        $response = $this->client->get($uri);
        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        return new Address($data);
    }

    /**
     * Returns the URI for the API request.
     *
     * @param string $postcode
     * @param int $houseNumber
     * @param null|string $houseNumberAddition
     * @return string
     */
    public function getUri(string $postcode, int $houseNumber, string $houseNumberAddition = null): string
    {
        return sprintf('%s/%s/%d/%s', self::BASE_URI, $postcode, $houseNumber, $houseNumberAddition);
    }
}
