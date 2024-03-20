<?php

namespace App\Controllers\MainController;

require_once '../../vendor/autoload.php';
use App\Models\User;
use App\Models\UserView;

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {

        case 'addUser':
            if (! empty($_REQUEST['userData'])) {
                $userData = $_POST['userData'];
                if (! empty($userData['firstName']) && ! empty($userData['lastName']) && ! empty($userData['role'])) {
                    if (! is_numeric($userData['firstName']) && ! is_numeric($userData['lastName'])) {
                        $firstName = $userData['firstName'];
                        $lastName = $userData['lastName'];
                        $status = $userData['status'];
                        $role = $userData['role'];

                        $stmt = new User();
                        $result = $stmt->addUser($firstName, $lastName, $status, $role);
                        // Перевірка успішного додавання користувача
                        $data = json_decode($result, true);
                        $id = $data['id'];
                        if ($result) {
                            echo json_encode(['status' => true, 'message' => 'User added successfully', 'id' => $id]);
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
            }

        case 'deleteUser':
            if (! empty($_POST['userId'])) {
                $userIds = $_POST['userId'];
                $userModel = new User();
                if (! is_string($userIds)) {
                    foreach ($userIds as $id) {
                        $result = $userModel->deleteUser($id);
                    }
                } else {
                    $result = $userModel->deleteUser($userIds);
                }
                header('Content-Type: application/json');
                if ($result) {
                    echo json_encode(['status' => true, 'message' => 'User delete successfully']);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Failed to delete user']);
                }
            } else {
                echo json_encode(['status' => false, 'message' => 'Missing id']);
            }
            break;

        case 'editUser':
            if (! empty($_REQUEST['userData'])) {
                $userData = $_POST['userData'];
                if (! empty($userData['firstName']) && ! empty($userData['lastName']) && ! empty($userData['role'])) {
                    if (! is_numeric($userData['firstName']) && ! is_numeric($userData['lastName'])) {
                        $firstName = $userData['firstName'];
                        $lastName = $userData['lastName'];
                        $status = $userData['status'];
                        $role = $userData['role'];
                        $userId = $userData['userId'];

                        $userModel = new User();
                        $result = $userModel->updateUser($userId, $firstName, $lastName, $status, $role);
                        header('Content-Type: application/json');
                        if ($result) {
                            echo json_encode(['status' => true, 'message' => 'User edit successfully']);
                        } else {
                            echo json_encode(['status' => false, 'message' => 'Failed to edit user']);
                        }
                    }else {
                        echo json_encode(['status' => false, 'message' => 'First name and last name cannot be a numeric. ']);
                    }
                } else {
                    // Вивід повідомлення, якщо якесь з обов'язкових полів порожнє
                    echo json_encode(['status' => false, 'message' => 'All fields are required']);
                }
                break;
            }

        case 'actionWithSelectedUsers':
            if (! empty($_POST['userIds']) && ! empty($_POST['actionSelected'])) {
                $userIds = $_POST['userIds'];
                $actionSelected = $_POST['actionSelected'];

                $userModel = new User();

                foreach ($userIds as $id) {
                    $result = $userModel->updateStatusUsersById($id, $actionSelected);
                }
                if ($result) {
                    echo json_encode(['status' => true, 'message' => 'Users edit successfully']);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Failed to edit users']);
                }

            } else {
                // Вивід повідомлення, якщо якесь з обов'язкових полів порожнє
                echo json_encode(['status' => false, 'message' => 'Missing id']);
            }
            break;

        case 'live_content':

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
                echo json_encode(['status' => false, 'message' => 'Missing id']);
            }
    }
} else {
    echo json_encode(['status' => false, 'message' => 'No action specified']);
}
