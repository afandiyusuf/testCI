#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

sudo set -xe

# Install git (the php image doesn't have it) which is required by composer
sudo apt-get update -yqq
sudo apt-get install -y -qq git curl wget php5-cli zip unzip

sudo curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install phpunit, the tool that we will use for testing
sudo curl --location --output /usr/local/bin/phpunit https://phar.phpunit.de/phpunit.phar
sudo chmod +x /usr/local/bin/phpunit

# Install mysql driver
# Here you can install any other extension that you need
sudo docker-php-ext-install pdo_mysql