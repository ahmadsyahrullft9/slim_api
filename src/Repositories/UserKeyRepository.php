<?php

namespace Repositories;

use App\Database;
use PDO;

class UserKeyRepository
{

    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function create($userEmail, $userPassword)
    {
        $pdo = $this->database->getConnection();

        $userId = explode('@', $userEmail)[0];

        $sql = "INSERT INTO user_key(user_id, user_email, user_password) 
                VALUES(:user_id, :user_email, :user_password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_STR);
        $stmt->bindValue(':user_email', $userEmail, PDO::PARAM_STR);
        $stmt->bindValue(':user_password', md5($userPassword), PDO::PARAM_STR);
        $stmt->execute();

        return $pdo->lastInsertId();
    }

    public function getById($userId)
    {
        $pdo = $this->database->getConnection();
        $sql = "SELECT * FROM user_key WHERE user_id=:user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByEmailPassword($userEmail, $userPassword)
    {
        $userPassword = md5($userPassword);
        $pdo = $this->database->getConnection();
        $sql = "SELECT * FROM user_key WHERE user_email=:user_email AND user_password=:user_password";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_email', $userEmail, PDO::PARAM_STR);
        $stmt->bindValue(':user_password', $userPassword, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
