<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use Liquetsoft\Fias\Component\Exception\HttpTransportException;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\HttpTransport\HttpTransportSymfony;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\BaseCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * Тест для HTTP клиента на базе Symfony HTTP client.
 *
 * @internal
 */
final class HttpTransportSymfonyTest extends BaseCase
{
    private const REQUEST_URL = 'https://test.test/url';

    private const RESPONSE_STATUS_CODE = 201;

    private const RESPONSE_SYMFONY_HEADERS = [
        'Test-Header' => [
            'test_header_1',
            'test_header_2',
        ],
        'Test-Header-1' => [
            'test_header_1_1',
            'test_header_1_2',
        ],
    ];

    private const RESPONSE_LOCAL_HEADERS = [
        'test-header' => 'test_header_2',
        'test-header-1' => 'test_header_1_2',
    ];

    /**
     * Проверяет, что объект правильно отправит HEAD запрос.
     */
    public function testHead(): void
    {
        $mockResponse = new MockResponse(
            '',
            [
                'http_code' => self::RESPONSE_STATUS_CODE,
                'response_headers' => self::RESPONSE_SYMFONY_HEADERS,
            ]
        );
        $httpClient = new MockHttpClient($mockResponse, self::REQUEST_URL);

        $client = new HttpTransportSymfony($httpClient);
        $res = $client->head(self::REQUEST_URL);

        $this->assertSame('HEAD', $mockResponse->getRequestMethod());
        $this->assertSame(self::REQUEST_URL, $mockResponse->getRequestUrl());

        $this->assertSame(self::RESPONSE_STATUS_CODE, $res->getStatusCode());
        $this->assertSame(self::RESPONSE_LOCAL_HEADERS, $res->getHeaders());
    }

    /**
     * Проверяет, что объект правильно перехватит исключение при HEAD запросе.
     */
    public function testHeadException(): void
    {
        $mockResponse = new MockResponse(info: ['error' => 'host unreachable']);
        $httpClient = new MockHttpClient($mockResponse, self::REQUEST_URL);

        $client = new HttpTransportSymfony($httpClient);

        $this->expectException(HttpTransportException::class);
        $client->head(self::REQUEST_URL);
    }

    /**
     * Проверяет, что объект правильно отправит GET запрос.
     */
    public function testGet(): void
    {
        $jsonBody = [
            'body_key_1' => 'body_value_1',
            'body_key_2' => 'body_value_2',
        ];
        $body = json_encode($jsonBody);

        $mockResponse = new MockResponse(
            $body,
            [
                'http_code' => self::RESPONSE_STATUS_CODE,
                'response_headers' => self::RESPONSE_SYMFONY_HEADERS,
            ]
        );
        $httpClient = new MockHttpClient($mockResponse, self::REQUEST_URL);

        $client = new HttpTransportSymfony($httpClient);
        $res = $client->get(self::REQUEST_URL);

        $this->assertSame('GET', $mockResponse->getRequestMethod());
        $this->assertSame(self::REQUEST_URL, $mockResponse->getRequestUrl());
        $this->assertSame(['Accept: application/json'], $mockResponse->getRequestOptions()['headers']);

        $this->assertSame(self::RESPONSE_STATUS_CODE, $res->getStatusCode());
        $this->assertSame(self::RESPONSE_LOCAL_HEADERS, $res->getHeaders());
        $this->assertSame($body, $res->getPayload());
        $this->assertSame($jsonBody, $res->getJsonPayload());
    }

    /**
     * Проверяет, что объект правильно перехватит исключение при GET запросе.
     */
    public function testGetException(): void
    {
        $url = 'https://test.test/url';

        $mockResponse = new MockResponse(info: ['error' => 'host unreachable']);
        $httpClient = new MockHttpClient($mockResponse, $url);

        $client = new HttpTransportSymfony($httpClient);

        $this->expectException(HttpTransportException::class);
        $client->get($url);
    }
}
