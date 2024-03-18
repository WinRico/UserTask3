<?php

namespace App\PDO;

use PDO;
use PDOException;

class ConnectToDb
{

    public static function connect()
    {
        try {
            // Підключення до бази даних за допомогою PDO
            $conn = new PDO("mysql:host=localhost:3307;dbname=task3", "root", "");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            // Обробка помилок з'єднання
            die("Connection failed: " . $e->getMessage());
        }
    }
}