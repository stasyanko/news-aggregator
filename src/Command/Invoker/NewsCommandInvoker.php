<?php


namespace App\Command\Invoker;


use App\Command\FetchNewsCommandInterface;
use App\Entity\Article;

class NewsCommandInvoker implements NewsCommandInvokerInterface
{
    /**
     * @param FetchNewsCommandInterface $fetchNewsCommand
     * @return Article[]
     * @throws \App\Exceptions\NewsFetchFailedException
     */
    public function execute(FetchNewsCommandInterface $fetchNewsCommand): array
    {
        return $fetchNewsCommand->execute();
    }
}