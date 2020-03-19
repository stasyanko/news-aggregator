<?php


namespace App\Command;


use App\Entity\Article;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use DateTime;

class FetchNewsFromNewsApiCommand implements FetchNewsCommandInterface
{
    private HttpClientInterface $httpClient;
    private const BASE_URL = '';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }
    /**
     * @return Article[]
     */
    public function execute(): array
    {
        //TODO: move request to API to a separate method
        $newsFromApiJson = $this->httpClient
            ->request(
                'GET',
                'http://newsapi.org/v2/top-headlines?country=us&apiKey=' . $_ENV['NEWS_API_KEY'],
                ['http_version' => '2.0']
            )
            ->getContent();
        $newsFromApiArr = json_decode($newsFromApiJson);

        $resArray = [];

        foreach ($newsFromApiArr->articles as $newsItem) {
            $article = new Article();
            $article->setTitle($newsItem->title);
            $article->setDescription($newsItem->description);
            $article->setDescription($newsItem->description);
            $article->setUrl($newsItem->url);
            $article->setImageUrl($newsItem->urlToImage);
            $article->setPublishedAt(new DateTime());
            $article->setContent((string) $newsItem->content);

            $resArray[] = $article;
        }

        return $resArray;
    }
}