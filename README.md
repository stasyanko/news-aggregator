# News aggregator

A news aggregator site built with php and symfony.
To make the codebase extendable, the [command](https://designpatternsphp.readthedocs.io/en/latest/Behavioral/Command/README.html) design pattern is used for news data sources.

### TODO:
- apply finite state machine to an article's status (new, published, rejected), possibly with this library  [https://symfony.com/doc/current/components/workflow.html](https://symfony.com/doc/current/components/workflow.html)
