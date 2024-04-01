<?php

namespace App\Models;

use App\PDO\ConnectToDb;
use PDO;

/**
 * Клас моделі користувача.
 */
class User {
    private $db;

    /**
     * Конструктор класу.
     */
    function __construct() {
        // Підключення до бази даних при створенні екземпляру класу
        $this->db = ConnectToDb::connect();
    }

    /**
     * Отримати інформацію про всіх користувачів.
     *
     * @return array Масив з інформацією про користувачів.
     */
    public function getUsers() {
        // Підготовка та виконання запиту для отримання користувачів
        $statement = $this->db->prepare("SELECT * FROM users");
        $statement->execute();
        // Повернення результату у вигляді асоціативного масиву
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getUsersById($id) {
        $statement = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $statement->execute([$id]);
        // Повернення результату у вигляді асоціативного масиву
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Додати нового користувача до бази даних.
     *
     * @param string $firstName Ім'я користувача.
     * @param string $lastName Прізвище користувача.
     * @param int $status Статус користувача (1 - активний, 0 - неактивний).
     * @param int $role Роль користувача.
     * @return string JSON-рядок з результатом операції.
     */
    public function addUser($firstName, $lastName, $status, $role) {
        $status = !$status ? 0 : 1;
        // SQL запит на додавання користувача
        $query = "INSERT INTO users (firstname, lastname, status, role) VALUES (?, ?, ?, ?)";
        // Виконання запиту
        $stmt = $this->db->prepare($query);
        $stmt->execute([$firstName, $lastName, $status, $role]);

        $userId = $this->db->lastInsertId();

        // Повернення результату операції у форматі JSON
        return json_encode(['status' => true, 'error' => null, 'id' => $userId]);
    }
    function updateUser($id, $firstname, $lastname, $status, $role){
        $status =  !$status ? 0 : 1;
        $updatedUser = $this->updateUserById($id, $firstname, $lastname, $status, $role);
        if (!$updatedUser) {
            return json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'not found user']]);
        }
        return json_encode(['status' => true, 'error' => null, 'user' => $updatedUser]);
    }

    /**
     * Оновлює дані користувача за ідентифікатором.
     *
     * @param int $id Ідентифікатор користувача.
     * @param string $firstname Ім'я користувача.
     * @param string $lastname Прізвище користувача.
     * @param int $status Статус користувача (1 - активний, 0 - неактивний).
     * @param int $role Роль користувача.
     * @return bool Результат операції (чи вдалося оновити дані користувача).
     */
    function updateUserById($id, $firstname, $lastname, $status, $role){
        $query = "UPDATE users SET firstname = ?, lastname = ?, status = ?, role = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$firstname, $lastname, $status, $role, $id]);
    }

    /**
     * Оновлює статус користувачів за ідентифікатором.
     *
     * @param int $userIds Масив ідентифікаторів користувачів.
     * @param string $action Дія, яку слід виконати (наприклад, 'setActive').
     * @return string JSON-рядок з результатом операції.
     */
    function updateStatusUsersById($userIds,$action){

        $status = $action === 'setActive' ? 1 : 0;
        $query = "UPDATE users SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$status, $userIds]);
    }

    /**
     * Видаляє користувача за ідентифікатором.
     *
     * @param int $userId Ідентифікатор користувача, якого слід видалити.
     * @return bool Чи вдалося видалити користувача.
     */
    public function deleteUser($userId): bool
    {
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->rowCount() > 0;
    }

}