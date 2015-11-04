<?php

namespace Speelpenning\PostcodeNl\Services;

use Speelpenning\PostcodeNl\Address;
use Speelpenning\PostcodeNl\Http\PostcodeNlClient;
use Speelpenning\PostcodeNl\Validators\AddressLookupValidator;

class AddressLookup
{
    /**
     * @var AddressLookupValidator
     */
    protected $validator;

    /**
     * @var PostcodeNlClient
     */
    protected $client;

    /**
     * AddressLookup constructor.
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
     */
    public function lookup($postcode, $houseNumber, $houseNumberAddition = null)
    {
        $this->validator->validate(compact(['postcode', 'houseNumber', 'houseNumberAddition']));

        $uri = $this->getUri($postcode, $houseNumber, $houseNumberAddition);
        $response = $this->client->get($uri);
        $data = json_decode($response->getBody()->getContents(), true);
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
    public function getUri($postcode, $houseNumber, $houseNumberAddition = null)
    {
        return "https://api.postcode.nl/rest/addresses/$postcode/$houseNumber/$houseNumberAddition";
    }
}
