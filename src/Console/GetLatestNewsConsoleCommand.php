<?php


namespace App\Console;

use App\Command\FetchNewsFromNewsApiCommand;
use App\Command\Invoker\NewsCommandInvoker;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpKernel\Log\Logger;

class GetLatestNewsConsoleCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'get:latest-news';
    private EntityManagerInterface $em;

    /**
     * OneLevel Constructor
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Gets latest news from all available data sources and stores it to database.')
            ->setHelp('Gets latest news from all available data sources and stores it to database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $newsDataSources = [
            new FetchNewsFromNewsApiCommand(
                new CurlHttpClient(),
                new Logger()
            ),
        ];

        $commandInvoker = new NewsCommandInvoker();

        foreach ($newsDataSources as $newsDataSource) {
            $articles = $commandInvoker->execute($newsDataSource);
            $this->storeArticles($articles);
        }

        return 0;
    }

    /**
     * @param $articles Article[]
     */
    private function storeArticles(array $articles): void
    {
        foreach ($articles as $article) {
            $articleFromDb = $this->em
                ->getRepository(Article::class)
                ->findOneBy([
                    'url' => $article->getUrl()
                ]);

            if ($articleFromDb === null) {
                $this->em->persist($article);
            }
        }

        $this->em->flush();
        $this->em->clear();
    }
}