# Transaction API

## Description

A project template in the following configuration:
1. Symfony 5.3
2. PHP 7.4
3. MySQL 8
4. Separate Docker containers for Nginx, PHP and a database
5. CS-Fixer and Psalm on board

# At start:
1. `make config` - will copy `.env.dist` to `.env` and `symfony/.env` to `symfony/.env.local`
2. `make init` - install composer, create database and execute migrations
3. `make up` - start project
4. `make db-fixtures` - load first users

# Useful commands
1. `make console` - default shell is zsh with preinstalled set of plugins
2. `make test` - PHPUnit tests
3. `make cs` - PHP CS-fixer with predefined rule sets
4. `make psalm` - Psalm (default level is 1)


