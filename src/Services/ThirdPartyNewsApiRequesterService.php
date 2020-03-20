<?php


namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsApiRequesterService implements ThirdPartyNewsApiRequesterServiceInterface
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return string
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function request(): string
    {
        return $this->httpClient
            ->request(
                'GET',
                'http://newsapi.org/v2/top-headlines?country=us&apiKey=' . $_ENV['NEWS_API_KEY'],
                ['http_version' => '2.0']
            )
            ->getContent();
    }
}