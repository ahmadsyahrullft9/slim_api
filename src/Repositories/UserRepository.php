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

        $stmt = $pdo->query('SELECT * FROM karyawan');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $pdo = $this->database->getConnection();
        $sql = 'SELECT * FROM karyawan WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data): string
    {
        $pdo = $this->database->getConnection();
        $sql = 'INSERT INTO karyawan(nama, alamat, gender, umur, username, `password`, jabatan, `level`)
                VALUES (:nama, :alamat, :gender, :umur, :username, :password, :jabatan, :level)';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nama', $data['nama'], PDO::PARAM_STR);
        $stmt->bindValue(':alamat', $data['alamat'], PDO::PARAM_STR);
        $stmt->bindValue(':gender', $data['gender'], PDO::PARAM_STR);
        $stmt->bindValue(':umur', $data['umur'], PDO::PARAM_INT);
        $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindValue(':password', base64_encode($data['password']), PDO::PARAM_STR);
        $stmt->bindValue(':jabatan', $data['jabatan'], PDO::PARAM_STR);
        $stmt->bindValue(':level', $data['level'], PDO::PARAM_STR);

        $stmt->execute();

        return $pdo->lastInsertId();
    }

    public function update(int $id, $data): int
    {
        $pdo = $this->database->getConnection();
        $sql = 'UPDATE karyawan 
                SET nama=:nama, alamat=:alamat, gender=:gender, umur=:umur, username=:username, password=:password, jabatan=:jabatan, level=:level 
                WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nama', $data['nama'], PDO::PARAM_STR);
        $stmt->bindValue(':alamat', $data['alamat'], PDO::PARAM_STR);
        $stmt->bindValue(':gender', $data['gender'], PDO::PARAM_STR);
        $stmt->bindValue(':umur', $data['umur'], PDO::PARAM_INT);
        $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindValue(':password', base64_encode($data['password']), PDO::PARAM_STR);
        $stmt->bindValue(':jabatan', $data['jabatan'], PDO::PARAM_STR);
        $stmt->bindValue(':level', $data['level'], PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(int $id): int
    {
        $pdo = $this->database->getConnection();
        $sql = 'DELETE FROM karyawan 
                WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
