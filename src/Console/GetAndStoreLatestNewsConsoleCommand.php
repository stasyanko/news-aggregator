<?php


namespace App\Console;

use App\Command\FetchNewsCommandInterface;
use App\Command\FetchNewsFromNewsApiCommand;
use App\Command\Invoker\NewsCommandInvokerInterface;
use App\Entity\Article;
use App\Exceptions\NewsFetchFailedException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetAndStoreLatestNewsConsoleCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'get:latest-news';
    private EntityManagerInterface $em;
    private LoggerInterface $logger;
    private NewsCommandInvokerInterface $newsCommandInvoker;
    /*
     * @var FetchNewsCommandInterface[]
     */
    private array $newsSources;

    /**
     * OneLevel Constructor
     *
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @param NewsCommandInvokerInterface $newsCommandInvoker
     * @param FetchNewsFromNewsApiCommand $fetchNewsFromNewsApiCommand
     */
    public function __construct(
        EntityManagerInterface $em,
        LoggerInterface $logger,
        NewsCommandInvokerInterface $newsCommandInvoker,
        FetchNewsFromNewsApiCommand $fetchNewsFromNewsApiCommand
    )
    {
        parent::__construct();

        $this->em = $em;
        $this->logger = $logger;
        $this->newsCommandInvoker = $newsCommandInvoker;
        $this->addNewsSource($fetchNewsFromNewsApiCommand);
    }

    protected function configure()
    {
        $this
            ->setDescription('Gets latest news from all available data sources and stores it to database.')
            ->setHelp('Gets latest news from all available data sources and stores it to database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->newsSources as $newsDataSource) {
            try {
                $articles = $this->newsCommandInvoker->execute($newsDataSource);
                $this->storeArticles($articles);
            } catch (NewsFetchFailedException $e) {
                $this->logger->error($e->getMessage());
            }
        }

        return 0;
    }

    private function addNewsSource(FetchNewsCommandInterface $fetchNewsCommand): void
    {
        $this->newsSources[] = $fetchNewsCommand;
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