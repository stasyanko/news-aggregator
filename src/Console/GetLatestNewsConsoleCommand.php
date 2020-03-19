<?php


namespace App\Console;

use App\Command\FetchNewsFromNewsApiCommand;
use App\Command\Invoker\NewsCommandInvoker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\CurlHttpClient;

class GetLatestNewsConsoleCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'get:latest-news';

    protected function configure()
    {
        $this
            ->setDescription('Gets latest news from all available data sources and stores it to database.')
            ->setHelp('Gets latest news from all available data sources and stores it to database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $newsDataSources = [
            new FetchNewsFromNewsApiCommand(new CurlHttpClient()),
        ];

        $commandInvoker = new NewsCommandInvoker();

        foreach ($newsDataSources as $newsDataSource) {
            $articles = $commandInvoker->execute($newsDataSource);
            //TODO: store to db
            dd($articles);
        }
    }
}