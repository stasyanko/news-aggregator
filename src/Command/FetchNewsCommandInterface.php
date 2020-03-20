<?php

namespace App\Command;

use App\Entity\Article;
use App\Exceptions\NewsFetchFailedException;

interface FetchNewsCommandInterface
{
    /**
     * @return Article[]
     * @throws NewsFetchFailedException
     */
    public function execute(): array;
}