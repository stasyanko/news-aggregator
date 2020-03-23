<?php


namespace App\Actions\Article;


use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

class GetArticleListAction implements GetArticleListActionInterface
{
    /* @var EntityManagerInterface $em */
    protected $em;

    /**
     * OneLevel Constructor
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return Article[]
     */
    public function execute(): array
    {
        return $this->em->getRepository(Article::class)->findAll();
    }
}