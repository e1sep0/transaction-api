# Symfony5 Docker config

## Description

A project template in the following configuration:
1. Symfony 5.3
2. PHP 7.4
3. MySQL 8
4. Separate Docker containers for Nginx, PHP and a database
5. CS-Fixer and Psalm on board

## Configuring Xdebug settings for PhpStorm IDE

To integrate Xdebug with PhpStorm within a created project you need to do the following:
1. Create a PHP interpreter in the `Settings -> Languages & Frameworks -> PHP` tab from the php-fpm container in the project; make sure that Xdebug works properly in the container.
2. Type the port number `9009` at the menu `Settings -> Languages & Frameworks -> PHP -> Debug -> Xdebug -> Debug`.
3. Create a server named `Docker` in the menu `Settings -> Languages & Frameworks -> PHP -> Servers` (it matches with the value of the `ServerName` field in the IDE config for both interpreters).
4. If necessary, make proper mappings in the PHP interpreter `Settings -> Languages & Frameworks -> PHP -> Path Mappings`,
5. Click the button `Listen for PHP debug connections`; if you have any questions, please read the [documentation](https://www.jetbrains.com/help/phpstorm/debugging-with-phpstorm-ultimate-guide.html).

# Useful makefile commands

1. `make console` - default shell is zsh with preinstalled set of [plugins](https://github.com/ddlzz/symfony5-docker-website-skeleton/blob/main/docker/dev/php-cli/.zshrc)
2. `make test` - PHPUnit tests
3. `make cs` - PHP CS-fixer with predefined [rule sets]
4. `make psalm` - Psalm (default level is 1)


