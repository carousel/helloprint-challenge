#!/bin/bash
red='\e[1;31m%s\e[0m\n'
green='\e[32m%s\e[0m\n'
yellow='\e[33m%s\e[0m\n'
blue='\e[34m%s\e[0m\n'
magenta='\e[1;35m%s\e[0m\n'
cyan='\e[1;36m%s\e[0m\n'

service docker start

printf "$red" "Initializing CLIENT container"
cd Client && docker-compose build && docker-compose up -d
printf "$blue" "Done!"

cd ../Broker

printf "$red" "Initializing BROKER container"
docker build --file .docker/Dockerfile -t broker . && docker-compose up -d
printf "$blue" "Done!"

PHP=`which php`
cd Consumer
$PHP init.php
