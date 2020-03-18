<?php

namespace App\Actions\Author;

use App\Entity\Author;

interface GetAuthorListActionInterface
{
    /**
     * @return Author[]
     */
    public function execute(): array;
}