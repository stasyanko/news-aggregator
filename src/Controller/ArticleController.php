<?php


namespace App\Controller;
use App\Actions\Article\GetArticleListActionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    public function list(GetArticleListActionInterface $getArticleListAction)
    {
        $articles = $getArticleListAction->execute();

        return $this->render('article/list.html.twig', ['articles' => $articles]);
    }
}