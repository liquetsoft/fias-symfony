<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\HttpTransport;

use Liquetsoft\Fias\Component\Exception\HttpTransportException;
use Liquetsoft\Fias\Component\HttpTransport\HttpTransport;
use Liquetsoft\Fias\Component\HttpTransport\HttpTransportResponse;
use Liquetsoft\Fias\Component\HttpTransport\HttpTransportResponseFactory;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Реализация HTTP клиента, которая использует Sumfony HTTP client.
 */
final class HttpTransportSymfony implements HttpTransport
{
    public function __construct(private readonly HttpClientInterface $symfonyHttpClient)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function head(string $url): HttpTransportResponse
    {
        try {
            $response = $this->symfonyHttpClient->request('HEAD', $url);
            $status = $response->getStatusCode();
            $headers = $this->grabHeadersFromSymfonyResponse($response);
        } catch (\Throwable $e) {
            throw HttpTransportException::wrap($e);
        }

        return HttpTransportResponseFactory::create($status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $url, array $params = []): HttpTransportResponse
    {
        try {
            $response = $this->symfonyHttpClient->request(
                'GET',
                $url,
                [
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                ]
            );
            $status = $response->getStatusCode();
            $headers = $this->grabHeadersFromSymfonyResponse($response);
            $content = $response->getContent(true);
            $jsonContent = $response->toArray(true);
        } catch (\Throwable $e) {
            throw HttpTransportException::wrap($e);
        }

        return HttpTransportResponseFactory::create($status, $headers, $content, $jsonContent);
    }

    /**
     * {@inheritdoc}
     */
    public function download(string $url, $destination, ?int $bytesFrom = null, ?int $bytesTo = null): HttpTransportResponse
    {
        $params = [];
        if ($bytesFrom !== null && $bytesTo !== null) {
            $params['headers']['Range'] = 'bytes=' . $bytesFrom . '-' . ($bytesTo - 1);
        }

        try {
            $response = $this->symfonyHttpClient->request('GET', $url, $params);
            $statusCode = $response->getStatusCode();
            if ($statusCode <= 200 || $statusCode >= 300) {
                return HttpTransportResponseFactory::create($statusCode);
            }
            foreach ($this->symfonyHttpClient->stream($response) as $chunk) {
                fwrite($destination, $chunk->getContent());
            }
            $headers = $this->grabHeadersFromSymfonyResponse($response);
        } catch (\Throwable $e) {
            throw HttpTransportException::wrap($e);
        }

        return HttpTransportResponseFactory::create($statusCode, $headers);
    }

    /**
     * Конвертирует и возвращает заголовки из ответа Http клиента в формате,
     * подходящеи для библиотеки.
     *
     * @return array<string, string>
     */
    private function grabHeadersFromSymfonyResponse(ResponseInterface $response): array
    {
        $return = [];
        foreach ($response->getHeaders() as $name => $value) {
            $headerName = (string) $name;
            $headerValue = (string) end($value);
            $return[$headerName] = $headerValue;
        }

        return $return;
    }
}
