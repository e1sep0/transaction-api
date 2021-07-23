FROM php:7.4-fpm

COPY .docker/php/conf/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt-get update \
    && apt-get install -y git acl zsh openssl openssh-client wget zip nano librabbitmq-dev libssh-dev \
    && apt-get install -y libpng-dev libonig-dev zlib1g-dev libzip-dev libxml2-dev libicu-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip gd soap opcache \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug opcache


RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

ENV COMPOSER_ALLOW_SUPERUSER 1

# Zsh is the default shell
RUN curl -fsSL https://raw.githubusercontent.com/robbyrussell/oh-my-zsh/master/tools/install.sh | zsh || true
COPY .docker/php/conf/.zshrc /root/.zshrc
RUN chsh -s /bin/zsh

WORKDIR /app
