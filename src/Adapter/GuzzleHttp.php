<?php

namespace Simplon\Http\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Client\Exception\HttpException;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Simplon\Http\HttpInterface;

class GuzzleHttp implements HttpInterface
{
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var array|null
     */
    private $config = [
        'verify'  => false,
        'timeout' => 2,
    ];

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_replace($this->config, $config);
    }

    /**
     * @param string $method
     * @param string $uri
     *
     * @return RequestInterface
     */
    public function buildRequest(string $method, string $uri): RequestInterface
    {
        return new Request($method, $uri);
    }

    /**
     * @param RequestInterface $request
     *
     * @return Promise
     * @throws \Exception
     */
    public function sendAsyncRequest(RequestInterface $request): Promise
    {
        return new \Http\Adapter\Guzzle6\Promise($this->getClient()->sendAsync($request), $request);
    }

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     * @throws \Exception
     * @throws HttpException
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        try
        {
            return $this->getClient()->send($request);
        }
        catch (GuzzleException $e)
        {
            throw new HttpException($e->getMessage(), $request, new Response());
        }
    }

    /**
     * @return ClientInterface
     */
    protected function getClient(): ClientInterface
    {
        if (!$this->client)
        {
            $this->client = new Client($this->config);
        }

        return $this->client;
    }
}