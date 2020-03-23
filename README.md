# News aggregator

A news aggregator site built with php and symfony.
To make the codebase extendable, the [command](https://designpatternsphp.readthedocs.io/en/latest/Behavioral/Command/README.html) design pattern is used for news data sources.

### Starting the project:

First, copy and rename .env.test.example to .env.test and .env.example to .env.

Set database credentials in DATABASE_URL and run:

`php bin/console doctrine:database:create`

`php bin/console doctrine:migrations:migrate`

You need to get api key from https://newsapi.org and set NEWS_API_KEY variable in .env and .env.test to your value.

To start the project, it is advisable to have symfony cli installed. You can start the project like this:

`symfony serve`

### Running tests:

`php bin/phpunit`

### TODO:
- apply finite state machine to an article's status (new, published, rejected), possibly with this library  [https://symfony.com/doc/current/components/workflow.html](https://symfony.com/doc/current/components/workflow.html)
- add CI/CD
- add codecov
- add Docker