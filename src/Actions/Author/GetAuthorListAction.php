<?php


namespace App\Actions\Author;


use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;

class GetAuthorListAction implements GetAuthorListActionInterface
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
     * @return Author[]
     */
    public function execute(): array
    {
        return $this->em->getRepository(Author::class)->findAll();
    }
}