<?php


namespace App\Tests\Command\Invoker;


use App\Command\FetchNewsCommandInterface;
use App\Command\Invoker\NewsCommandInvoker;
use App\Entity\Article;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use DateTime;

class NewsCommandInvokerTest extends TestCase
{
    public function testExecuteReturnsArrayOfArticles()
    {
        $numberOfArticles = 5;

        $fetchNewsCommandResolver = $this->createMock(FetchNewsCommandInterface::class);
        $fetchNewsCommandResolver
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue($this->getArrayOfArticles($numberOfArticles)));

        $commandInvoker = new NewsCommandInvoker();
        $arrayOfArticles = $commandInvoker->execute($fetchNewsCommandResolver);

        $this->assertEquals($numberOfArticles, count($arrayOfArticles));
    }

    /**
     * @param int $numberOfArticles
     * @return Article[]
     * @throws \Exception
     */
    private function getArrayOfArticles(int $numberOfArticles): array
    {
        $resArr = [];
        $faker = Factory::create();

        foreach (range(1, $numberOfArticles) as $article) {
            $article = new Article();
            $article->setTitle($faker->title);
            $article->setDescription($faker->text());
            $article->setSource($faker->numberBetween(1,100));
            $article->setUrl($faker->url);
            $article->setImageUrl($faker->url);
            $article->setPublishedAt(new DateTime());
            $article->setContent($faker->text());

            $resArr[] = $article;
        }

        return $resArr;
    }
}