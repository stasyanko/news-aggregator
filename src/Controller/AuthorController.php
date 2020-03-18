<?php


namespace App\Controller;
use App\Actions\Author\GetAuthorListActionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends AbstractController
{
    public function list(GetAuthorListActionInterface $getAuthorListAction): Response
    {
        $authors = $getAuthorListAction->execute();
        //TODO: render a view
    }
}