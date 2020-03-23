<?php


namespace App\Console;

use App\Command\FetchNewsFromNewsApiCommand;
use App\Command\Invoker\NewsCommandInvokerInterface;
use App\Entity\Article;
use App\Exceptions\NewsFetchFailedException;
use App\Services\NewsApiRequesterService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpKernel\Log\Logger;

class GetAndStoreLatestNewsConsoleCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'get:latest-news';
    private EntityManagerInterface $em;
    private LoggerInterface $logger;
    private NewsCommandInvokerInterface $newsCommandInvoker;

    /**
     * OneLevel Constructor
     *
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @param NewsCommandInvokerInterface $newsCommandInvoker
     */
    public function __construct(
        EntityManagerInterface $em,
        LoggerInterface $logger,
        NewsCommandInvokerInterface $newsCommandInvoker
    )
    {
        parent::__construct();
        $this->em = $em;
        $this->logger = $logger;
        $this->newsCommandInvoker = $newsCommandInvoker;
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
            //TODO: move it to factory
            new FetchNewsFromNewsApiCommand(
                new Logger(),
                new NewsApiRequesterService(
                    new CurlHttpClient()
                )
            ),
        ];

        foreach ($newsDataSources as $newsDataSource) {
            try {
                $articles = $this->newsCommandInvoker->execute($newsDataSource);
                $this->storeArticles($articles);
            } catch (NewsFetchFailedException $e) {
                $this->logger->error($e->getMessage());
            }
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