<?php

namespace Speelpenning\PostcodeNl\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Config\Repository;
use Psr\Http\Message\ResponseInterface;
use Speelpenning\PostcodeNl\Exceptions\AccountSuspended;
use Speelpenning\PostcodeNl\Exceptions\AddressNotFound;
use Speelpenning\PostcodeNl\Exceptions\Unauthorized;

class PostcodeNlClient
{
    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var Client
     */
    protected $client;

    /**
     * Client constructor.
     *
     * @param Repository $config
     * @param Client $client
     */
    public function __construct(Repository $config, Client $client)
    {
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * Performs a GET request compatible with Postcode.nl.
     *
     * @param string $uri
     * @return ResponseInterface
     * @throws AccountSuspended
     * @throws AddressNotFound
     * @throws Unauthorized
     */
    public function get(string $uri): ResponseInterface
    {
        try {
            return $this->client->get($uri, $this->getRequestOptions());
        } catch (ClientException $e) {
            $this->handleClientException($e);
        }
    }

    /**
     * Returns the configured request options.
     *
     * @return array
     */
    protected function getRequestOptions(): array
    {
        return $this->config->get('postcode-nl.requestOptions');
    }

    /**
     * Handles the Guzzle client exception.
     *
     * @param ClientException $e
     * @throws Unauthorized
     * @throws AccountSuspended
     * @throws AddressNotFound
     */
    protected function handleClientException(ClientException $e): void
    {
        switch ($e->getCode()) {
            case 401:
                throw new Unauthorized();
            case 403:
                throw new AccountSuspended();
            case 404:
                throw new AddressNotFound();
            default:
                throw $e;
        }
    }
}
