<?php

namespace App\Controllers\MainController;

require_once '../../vendor/autoload.php';
use App\Models\User;
use App\Models\UserView;

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {

        case 'addUser':
            if (! empty(trim($_POST['firstName'])) && ! empty(trim($_POST['lastName'])) && ! empty(trim($_POST['role']))) {
                if (! is_numeric($_POST['firstName']) || ! is_numeric($_POST['lastName'])) {
                    $firstName = $_POST['firstName'];
                    $lastName = $_POST['lastName'];
                    $status = $_POST['status'];
                    $role = $_POST['role'];

                    $stmt = new User();
                    $result = $stmt->addUser($firstName, $lastName, $status, $role);
                    // Перевірка успішного додавання користувача
                    if ($result) {
                        echo json_encode(['status' => true, 'message' => 'User added successfully']);
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Failed to add user']);
                    }
                } else {
                    echo json_encode(['status' => false, 'message' => 'First name and last name cannot be a numeric. ']);
                }
            } else {
                // Вивід повідомлення, якщо якесь з обов'язкових полів порожнє
                echo json_encode(['status' => false, 'message' => 'All fields are required']);
            }
            break;

        case 'deleteUser':
            if (! empty($_POST['userId'])) {
                $userId = $_POST['userId'];
                $userModel = new User();
                $result = $userModel->deleteUser($userId);
                header('Content-Type: application/json');
                // Перевірка успішного видалення користувача
                if ($result) {
                    echo json_encode(['status' => true, 'message' => 'User deleted successfully']);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Failed to delete user']);
                }
            }
            break;
        case 'editUser':
            if (! empty($_POST['userId']) && ! empty($_POST['firstName']) && ! empty($_POST['lastName']) && ! empty($_POST['role'])) {
                if (! is_numeric($_POST['firstName']) || ! is_numeric($_POST['lastName'])) {
                    $userId = $_POST['userId'];
                    $firstName = $_POST['firstName'];
                    $lastName = $_POST['lastName'];
                    $status = $_POST['status'];
                    $role = $_POST['role'];

                    $userModel = new User();
                    $result = $userModel->updateUser($userId, $firstName, $lastName, $status, $role);
                    header('Content-Type: application/json');
                    if ($result) {
                        echo json_encode(['status' => true, 'message' => 'User edit successfully']);
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Failed to edit user']);
                    }
                } else {
                    echo json_encode(['status' => false, 'message' => 'First name and last name cannot be a numeric. ']);
                }
            } else {
                // Вивід повідомлення, якщо якесь з обов'язкових полів порожнє
                echo json_encode(['status' => false, 'message' => 'All fields are required']);
            }
            break;

        case 'actionWithSelectedUsers':
            if (! empty($_POST['userIds']) && ! empty($_POST['actionSelected'])) {
                $userIds = $_POST['userIds'];
                $actionSelected = $_POST['actionSelected'];

                $userModel = new User();
                if ($actionSelected != 'delete') {
                    foreach ($userIds as $id) {
                        $result = $userModel->updateStatusUsersById($id, $actionSelected);
                    }
                } else {
                    foreach ($userIds as $id) {
                        $result = $userModel->deleteUser($id);
                    }
                }

                if ($result) {
                    echo json_encode(['status' => true, 'message' => 'Users edit successfully']);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Failed to edit users']);
                }
            } else {
                // Вивід повідомлення, якщо якесь з обов'язкових полів порожнє
                echo json_encode(['status' => false, 'message' => 'All fields are required']);
            }
            break;

        case 'live_contend':

            $userModel = new UserView();
            $userModel->userView($userModel->getUsers());
            break;

        case 'getUserById':
            if (! empty($_POST['user_id'])) {
                $userId = $_POST['user_id'];
                $userModel = new User();
                $userData = $userModel->getUsersById($userId);
                echo json_encode($userData);
                break;
            } else {
                echo json_encode(['status' => false, 'message' => 'All fields are required']);
            }
    }
} else {
    echo json_encode(['status' => false, 'message' => 'No action specified']);
}
