#!/bin/bash
red='\e[1;31m%s\e[0m\n'
green='\e[32m%s\e[0m\n'
yellow='\e[33m%s\e[0m\n'
blue='\e[34m%s\e[0m\n'
magenta='\e[1;35m%s\e[0m\n'
cyan='\e[1;36m%s\e[0m\n'

service docker start

#start client service
printf "$blue" "Initializing CLIENT container"
cd Client docker-compose build && docker-compose up -d
printf "$green" "Done!"
echo '---------------------------------------------------------------------------------------'

#start broker service
printf "$blue" "Initializing BROKER container"
cd ../Broker docker build --file .docker/Dockerfile -t broker && docker-compose up -d && composer install && composer dump-autoload
printf "$green" "Done!"
echo '---------------------------------------------------------------------------------------'


#init and migrate consumer schema
printf "$blue" "Migrating CONSUMER schema"
PHP=`which php`
cd ../Consumer composer install && composer dump-autoload && $PHP init.php && service rabbitmq-server restart && $PHP index.php
printf "$green" "Done!"

echo '---------------------------------------------------------------------------------------'
