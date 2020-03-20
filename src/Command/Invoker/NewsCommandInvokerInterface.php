<?php

namespace App\Command\Invoker;

use App\Command\FetchNewsCommandInterface;
use App\Entity\Article;

interface NewsCommandInvokerInterface
{
    /**
     * @param FetchNewsCommandInterface $fetchNewsCommand
     * @throws \App\Exceptions\NewsFetchFailedException
     * @return Article[]
     */
    public function execute(FetchNewsCommandInterface $fetchNewsCommand): array;
}