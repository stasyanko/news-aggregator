<?php


namespace App\Command;


use App\Entity\Article;
use App\Exceptions\NewsFetchFailedException;
use App\Services\ThirdPartyNewsApiRequesterServiceInterface;
use Psr\Log\LoggerInterface;
use DateTime;
use Throwable;

class FetchNewsFromNewsApiCommand implements FetchNewsCommandInterface
{
    private LoggerInterface $logger;
    private ThirdPartyNewsApiRequesterServiceInterface $newsApiRequester;

    public function __construct(
        LoggerInterface $logger,
        ThirdPartyNewsApiRequesterServiceInterface $newsApiRequester
    )
    {
        $this->logger = $logger;
        $this->newsApiRequester = $newsApiRequester;
    }

    /**
     * @return Article[]
     * @throws NewsFetchFailedException
     */
    public function execute(): array
    {
        try {
            $newsFromApiJson = $this->newsApiRequester->request();
            $newsFromApiObj = json_decode($newsFromApiJson);
        } catch (Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
            throw new NewsFetchFailedException();
        }

        $resArray = [];

        foreach ($newsFromApiObj->articles as $newsItem) {
            $article = new Article();
            $article->setTitle((string) $newsItem->title);
            $article->setDescription((string) $newsItem->description);
            $article->setSource((string)$newsItem->source->id);
            $article->setUrl($newsItem->url);
            $article->setImageUrl((string) $newsItem->urlToImage);
            $article->setPublishedAt(new DateTime());
            $article->setContent((string)$newsItem->content);

            $resArray[] = $article;
        }

        return $resArray;
    }
}