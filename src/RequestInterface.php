<?php

declare(strict_types=1);

namespace WPPConnectTeam\Wppconnect;

use Psr\Http\Message\ResponseInterface;

interface RequestInterface
{
    /**
     * @param string $base_uri
     * @return RequestInterface
     */
    public function make(string $base_uri): RequestInterface;

    /**
     * @param string $uri
     * @return RequestInterface
     */
    public function to(string $uri): RequestInterface;

    /**
     * @param mixed $body
     * @param array $headers
     * @param array $options
     * @return RequestInterface
     */
    public function with($body = [], array $headers = [], array $options = []): RequestInterface;

    /**
     * @param mixed $body
     * @return RequestInterface
     */
    public function withBody($body = []): RequestInterface;

    /**
     * @param mixed $body
     * @return RequestInterface
     */
    public function addBody($body = []): RequestInterface;

    /**
     * @return mixed
     */
    public function getBody();

    /**
     * @param array $headers
     * @return RequestInterface
     */
    public function withHeaders(array $headers = []): RequestInterface;

    /**
     * @param array $headers
     * @return RequestInterface
     */
    public function addHeaders(array $headers = []): RequestInterface;

    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @param array $options
     * @return RequestInterface
     */
    public function withOptions(array $options = []): RequestInterface;

    /**
     * @param array $options
     * @return RequestInterface
     */
    public function addOptions(array $options = []): RequestInterface;

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * @return RequestInterface
     */
    public function asFormParams(): RequestInterface;

    /**
     * @return RequestInterface
     */
    public function asJson(): RequestInterface;

    /**
     * @return RequestInterface
     */
    public function asString(): RequestInterface;

    /**
     * @param resource|bool $debug
     * @return RequestInterface
     */
    public function debug($debug = false): RequestInterface;

    /**
     * @return ResponseInterface
     */
    public function get(): ResponseInterface;

    /**
     * @return ResponseInterface
     */
    public function post(): ResponseInterface;

    /**
     * @return ResponseInterface
     */
    public function put(): ResponseInterface;

    /**
     * @return ResponseInterface
     */
    public function patch(): ResponseInterface;

    /**
     * @return ResponseInterface
     */
    public function delete(): ResponseInterface;

    /**
     * @param string $method
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function request(string $method): ResponseInterface;
}
