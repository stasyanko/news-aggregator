<?php

namespace App\Command;

use App\Entity\Article;

interface FetchNewsCommandInterface
{
    /**
     * @return Article[]
     */
    public function execute(): array;
}