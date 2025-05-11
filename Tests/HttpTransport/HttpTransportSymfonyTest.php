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

    private const RESPONSE_STATUS_CODE = 200;

    private const RESPONSE_STATUS_CODE_ERROR = 500;

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
     * Проверяет, что объект правильно обработает ошибочный HTTP статус.
     */
    public function testHeadWrongStatus(): void
    {
        $mockResponse = new MockResponse(
            '[]',
            [
                'http_code' => self::RESPONSE_STATUS_CODE_ERROR,
                'response_headers' => self::RESPONSE_SYMFONY_HEADERS,
            ]
        );
        $httpClient = new MockHttpClient($mockResponse, self::REQUEST_URL);

        $client = new HttpTransportSymfony($httpClient);
        $res = $client->head(self::REQUEST_URL);

        $this->assertSame(self::RESPONSE_STATUS_CODE_ERROR, $res->getStatusCode());
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
        $body = json_encode($jsonBody, \JSON_THROW_ON_ERROR);

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
     * Проверяет, что объект правильно обработает ошибочный HTTP статус.
     */
    public function testGetWrongStatus(): void
    {
        $mockResponse = new MockResponse(
            '[]',
            [
                'http_code' => self::RESPONSE_STATUS_CODE_ERROR,
                'response_headers' => self::RESPONSE_SYMFONY_HEADERS,
            ]
        );
        $httpClient = new MockHttpClient($mockResponse, self::REQUEST_URL);

        $client = new HttpTransportSymfony($httpClient);
        $res = $client->get(self::REQUEST_URL);

        $this->assertSame(self::RESPONSE_STATUS_CODE_ERROR, $res->getStatusCode());
        $this->assertSame(self::RESPONSE_LOCAL_HEADERS, $res->getHeaders());
    }

    /**
     * Проверяет, что объект правильно перехватит исключение при GET запросе.
     */
    public function testGetException(): void
    {
        $mockResponse = new MockResponse(info: ['error' => 'host unreachable']);
        $httpClient = new MockHttpClient($mockResponse, self::REQUEST_URL);

        $client = new HttpTransportSymfony($httpClient);

        $this->expectException(HttpTransportException::class);
        $client->get(self::REQUEST_URL);
    }

    /**
     * Проверяет, что объект правильно загрузит файл.
     */
    public function testDownload(): void
    {
        $body = 'test body';
        $destination = $this->getPathToTestFile('testDownload', 'qwe');
        $destinationResource = $this->getDestinationResource($destination);

        $mockResponse = new MockResponse(
            $body,
            [
                'http_code' => self::RESPONSE_STATUS_CODE,
                'response_headers' => self::RESPONSE_SYMFONY_HEADERS,
            ]
        );
        $httpClient = new MockHttpClient($mockResponse, self::REQUEST_URL);

        $client = new HttpTransportSymfony($httpClient);
        $res = $client->download(self::REQUEST_URL, $destinationResource);
        fclose($destinationResource);

        $this->assertSame('GET', $mockResponse->getRequestMethod());
        $this->assertSame(self::REQUEST_URL, $mockResponse->getRequestUrl());

        $this->assertSame(self::RESPONSE_STATUS_CODE, $res->getStatusCode());
        $this->assertSame(self::RESPONSE_LOCAL_HEADERS, $res->getHeaders());
        $this->assertSame($body, file_get_contents($destination));
    }

    /**
     * Проверяет, что объект правильно загрузит файл с указанной точки.
     */
    public function testDownloadBytes(): void
    {
        $body = 'test body';
        $destination = $this->getPathToTestFile('testDownload', 'qwe');
        $destinationResource = $this->getDestinationResource($destination);
        $bytesFrom = 100;
        $bytesTo = 200;

        $mockResponse = new MockResponse(
            $body,
            [
                'http_code' => self::RESPONSE_STATUS_CODE,
                'response_headers' => self::RESPONSE_SYMFONY_HEADERS,
            ]
        );
        $httpClient = new MockHttpClient($mockResponse, self::REQUEST_URL);

        $client = new HttpTransportSymfony($httpClient);
        $res = $client->download(self::REQUEST_URL, $destinationResource, $bytesFrom, $bytesTo);

        $this->assertSame('GET', $mockResponse->getRequestMethod());
        $this->assertSame(self::REQUEST_URL, $mockResponse->getRequestUrl());
        $this->assertSame(
            [
                'Range: bytes=' . $bytesFrom . '-' . ($bytesTo - 1),
                'Accept: */*',
            ],
            $mockResponse->getRequestOptions()['headers']
        );

        $this->assertSame(self::RESPONSE_STATUS_CODE, $res->getStatusCode());
        $this->assertSame(self::RESPONSE_LOCAL_HEADERS, $res->getHeaders());
        $this->assertSame($body, file_get_contents($destination));
    }

    /**
     * Проверяет, что объект правильно обработает ошибочный HTTP статус.
     */
    public function testDownloadWrongStatus(): void
    {
        $destination = $this->getPathToTestFile('testDownload', 'qwe');
        $destinationResource = $this->getDestinationResource($destination);

        $mockResponse = new MockResponse(
            '',
            [
                'http_code' => self::RESPONSE_STATUS_CODE_ERROR,
                'response_headers' => self::RESPONSE_SYMFONY_HEADERS,
            ]
        );
        $httpClient = new MockHttpClient($mockResponse, self::REQUEST_URL);

        $client = new HttpTransportSymfony($httpClient);
        $res = $client->download(self::REQUEST_URL, $destinationResource);
        fclose($destinationResource);

        $this->assertSame(self::RESPONSE_STATUS_CODE_ERROR, $res->getStatusCode());
        $this->assertSame(self::RESPONSE_LOCAL_HEADERS, $res->getHeaders());
    }

    /**
     * Проверяет, что объект правильно перехватит исключение при загрузке файла.
     */
    public function testDownloadException(): void
    {
        $destination = $this->getPathToTestFile('testDownload', 'qwe');
        $destinationResource = $this->getDestinationResource($destination);

        $mockResponse = new MockResponse(info: ['error' => 'host unreachable']);
        $httpClient = new MockHttpClient($mockResponse, self::REQUEST_URL);

        $client = new HttpTransportSymfony($httpClient);

        $this->expectException(HttpTransportException::class);
        $client->download(self::REQUEST_URL, $destinationResource);
    }

    /**
     * @return resource
     */
    private function getDestinationResource(string $path): mixed
    {
        $destinationResource = fopen($path, 'wb');

        if ($destinationResource === false) {
            throw new \RuntimeException("Can't open file to test download");
        }

        return $destinationResource;
    }
}
