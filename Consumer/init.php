<?php

//initialize db/users table (simulate migration)
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

try {
    $dbuser = getenv('DB_USER');
    $dbpass = getenv('DB_PASSWORD');
    $host = getenv('DB_HOST');
    $dbname = getenv('DB_NAME');
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);


    $users = "DROP TABLE IF EXISTS users";
    $pdo->exec($users);

    $schema = "CREATE TABLE users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL,
        password VARCHAR(30) NOT NULL,
        email VARCHAR(50) NOT NULL,
        created_on DATETIME NOT NULL DEFAULT NOW(),
        updated_on DATETIME NOT NULL,
        status TINYINT(1)
    )";
    $pdo->exec($schema);

    $userIndex = "CREATE INDEX username ON users (username);";
    $pdo->exec($userIndex);
    $emailIndex = "CREATE INDEX email ON users (email);";
    $pdo->exec($emailIndex);
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
    die();
}
