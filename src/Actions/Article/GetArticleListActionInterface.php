<?php

namespace App\Actions\Article;

use App\Entity\Article;

interface GetArticleListActionInterface
{
    /**
     * @return Article[]
     */
    public function execute(): array;
}