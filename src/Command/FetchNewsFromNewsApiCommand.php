<?php


namespace App\Command;


use App\Entity\Article;
use App\Exceptions\NewsFetchFailedException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use DateTime;
use Throwable;

class FetchNewsFromNewsApiCommand implements FetchNewsCommandInterface
{
    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;

    public function __construct(
        HttpClientInterface $httpClient,
        LoggerInterface $logger
    )
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    /**
     * @return Article[]
     * @throws NewsFetchFailedException
     */
    public function execute(): array
    {
        try {
            $newsFromApiArr = $this->makeRequestToApi();
        } catch (Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
            throw new NewsFetchFailedException();
        }

        $resArray = [];

        foreach ($newsFromApiArr->articles as $newsItem) {
            $article = new Article();
            $article->setTitle($newsItem->title);
            $article->setDescription($newsItem->description);
            $article->setSource((string)$newsItem->source->id);
            $article->setUrl($newsItem->url);
            $article->setImageUrl($newsItem->urlToImage);
            $article->setPublishedAt(new DateTime());
            $article->setContent((string)$newsItem->content);

            $resArray[] = $article;
        }

        return $resArray;
    }

    /**
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function makeRequestToApi(): object
    {
        $newsFromApiJson = $this->httpClient
            ->request(
                'GET',
                'http://newsapi.org/v2/top-headlines?country=us&apiKey=' . $_ENV['NEWS_API_KEY'],
                ['http_version' => '2.0']
            )
            ->getContent();

        return json_decode($newsFromApiJson);
    }
}