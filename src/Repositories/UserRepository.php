<?php

namespace Repositories;

use App\Database;
use PDO;

class UserRepository
{

    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAll(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query("SELECT * FROM mstuser");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $pdo = $this->database->getConnection();
        $sql = "SELECT * FROM mstuser WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data): string
    {
        $pdo = $this->database->getConnection();
        $sql = "INSERT INTO mstuser(nama, alamat, umur, tgl_lahir, gender, username, userpass)
                VALUES(:nama, :alamat, :umur, :tgl_lahir, :gender, :username, :userpass)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nama', $data['nama'], PDO::PARAM_STR);
        $stmt->bindValue(':alamat', $data['alamat'], PDO::PARAM_STR);
        $stmt->bindValue(':umur', $data['umur'], PDO::PARAM_INT);
        $stmt->bindValue(':tgl_lahir', $data['tgl_lahir'], PDO::PARAM_STR);
        $stmt->bindValue(':gender', $data['gender'], PDO::PARAM_STR);
        $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindValue(':userpass', md5($data['userpass']), PDO::PARAM_STR);

        $stmt->execute();

        return $pdo->lastInsertId();
    }

    public function update(int $id, $data): int
    {
        $pdo = $this->database->getConnection();
        $sql = "UPDATE mstuser 
                SET nama=:nama,	alamat=:alamat,	umur=:umur,	tgl_lahir=:tgl_lahir, gender=:gender, username=:username, userpass=:userpass
                WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nama', $data['nama'], PDO::PARAM_STR);
        $stmt->bindValue(':alamat', $data['alamat'], PDO::PARAM_STR);
        $stmt->bindValue(':umur', $data['umur'], PDO::PARAM_INT);
        $stmt->bindValue(':tgl_lahir', $data['tgl_lahir'], PDO::PARAM_STR);
        $stmt->bindValue(':gender', $data['gender'], PDO::PARAM_STR);
        $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindValue(':userpass', md5($data['userpass']), PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(int $id): int
    {
        $pdo = $this->database->getConnection();
        $sql = "DELETE FROM mstuser 
                WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
