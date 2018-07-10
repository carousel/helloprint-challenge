<?php

namespace App\Persistence;

use Dotenv\Dotenv;
use PDO;

class DB
{
    /**
     * @return PDO
     */
    public function pdo()
    {
        $dotenv = new Dotenv(__DIR__ . '/../../');
        $dotenv->load();
        $dbuser = getenv('DB_USER');
        $dbpass = getenv('DB_PASSWORD');
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        return new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    }

    /**
     * @param $data
     */
    public function create($data)
    {
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $status = 1;
        $sql = "INSERT INTO users (username, email, password, status) VALUES (?,?,?,?)";
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute([$username, $email, $password, $status]);
    }

    /**
     * @param $column
     * @param $data
     * @param $username
     */
    public function update($column, $data, $username)
    {
        if ($column == 'logged') {
            $sql = "UPDATE users SET status=:status WHERE username=:username";
            $stmt = $this->pdo()->prepare($sql);
            $stmt->bindValue(':status', $data);
            $stmt->bindValue(':username', $username);
            $stmt->execute();
        }
        if ($column == 'username') {
            $sql = "UPDATE users SET username=:data WHERE username=:username";
            $stmt = $this->pdo()->prepare($sql);
            $stmt->execute([':username' => $data, ':username' => $username]);
        }
        if ($column == 'recovery') {
            $sql = "UPDATE users SET password=:password WHERE username=:username";
            $stmt = $this->pdo()->prepare($sql);
            $stmt->bindValue(':password', $data);
            $stmt->bindValue(':username', $username);
            $stmt->execute();
        }
    }

    /**
     * @param $param
     * @return mixed
     */
    public function get($column,$param)
    {
        $sql = "select * from users where $column=:username";
        $stmt = $this->pdo()->prepare($sql);
        $stmt->bindValue(':username', $param);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * @param $username
     * @param $password
     * @return mixed
     */
    public function find($username, $password)
    {
        $sql = "select * from users where username=:username and password=:password";
        $stmt = $this->pdo()->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $password);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

}
