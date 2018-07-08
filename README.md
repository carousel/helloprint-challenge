# Helloprint challenge

## Overview 
### Cluster
*   three nodes (client,broker,consumer)
*   client and broker are dockerized (local environment)
*   consumer is deployed on VPS

* client listen on client:8088
* broker listen on broker:8880
* consumer listen on 146.185.140.33 (self hosted)


Starting point is bootstrap.sh file, that will pull all docker images and init PHP db migration.

Client stack is made of mostly vanilla JS and HTML/CSS, using nginx server. On client side, I was using fetch polyfill (for ajax request). 
User interface is made of three simple HTML forms: login,register and logout. Validation messages are displayed, based on response from broker.  
Simle styling is applied on client interface.

Broker node is using PHP7.1 with Apache server, php-ampqlib (for queue) and symfony http-foundation component (for easy request/response handling)
It has simple PHP form validation that returns response messages to client. Broker responsibilities are to accept and validat client input and to put messages on queue

Consumer node is not dockerized. it is using PHP, MySql (PDO). Dotenv package is also used for env variables. There is init.php script, that is migrating
initial users table schema. For mail management, PHPMailer library is used. Code in src folder is organized by responsibility, following good practices.
There is repository for abstracting persistence (with interface implementation). Also services layer is present. 


There is exensive use of composer (for PHP dependecy) on broker and consumer nodes. 
I have included couple of simple test cases (PHPUnit) on input validation
