<?php


namespace App\Controller;
use App\Actions\Author\GetAuthorListActionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    public function list(GetAuthorListActionInterface $getAuthorListAction)
    {
        $authors = $getAuthorListAction->execute();
        //TODO: render a view
    }
}