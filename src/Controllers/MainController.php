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
                if (! empty(trim($userData['firstName'])) && ! empty(trim($userData['lastName'])) && ! empty($userData['role'])) {
                    if (!preg_match('/^[a-z]+\s+[a-z]+$/i', $userData['firstName'])&& !preg_match('/^[a-z]+\s+[a-z]+$/i', $userData['lastName'])) {
                        if (! is_numeric($userData['firstName']) && ! is_numeric($userData['lastName'])) {
                            $firstName = $userData['firstName'];
                            $lastName = $userData['lastName'];
                            $status = filter_var($_POST['userData']['status'], FILTER_VALIDATE_BOOLEAN);
                            $role =  $userData['role'];
                            // Конвертація ролі
                            $role = ($role == 'admin') ? 1 : 0;

                            $stmt = new User();
                            $result = $stmt->addUser($firstName, $lastName, $status, $role);

                            $data = json_decode($result, true);
                            $id = $data['id'];

                            $addedUser = [
                                'id' => $id,
                                'firstName' => $firstName,
                                'lastName' => $lastName,
                                'status' => $status,
                                'role' => $role,

                            ];
                            if ($result) {
                                echo json_encode(['status' => true, 'error' => null, 'user' => $addedUser]);
                            } else {
                                echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'Failed to add user']]);
                            }
                        } else {
                            echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'First name and last name cannot be numeric']]);
                        }
                    } else {
                        echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'Incorrect input values']]);
                    }
                } else {
                    // Вивід повідомлення, якщо якесь з обов'язкових полів порожнє
                    echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'All fields are required']]);
                }

            }
            break;

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
                    echo json_encode(['status' => true, 'error' => null]);
                } else {
                    echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'Failed to delete user']]);
                }
            } else {
                print_r($_POST['userId']);
                echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'Missing id']]);
            }
            break;

        case 'editUser':
            if (!empty($_REQUEST['userData'])) {
                $userData = $_POST['userData'];
                if (!empty(trim($userData['firstName'])) && ! empty(trim($userData['lastName'])) && ! empty($userData['role'])) {
                    if (!preg_match('/^[a-z]+\s+[a-z]+$/i', $userData['firstName']) && !preg_match('/^[a-z]+\s+[a-z]+$/i', $userData['lastName'])) {
                        if (! is_numeric($userData['firstName']) && ! is_numeric($userData['lastName'])) {
                            $firstName = $userData['firstName'];
                            $lastName = $userData['lastName'];
                            $status = filter_var($_POST['userData']['status'], FILTER_VALIDATE_BOOLEAN);
                            $role = $userData['role'];

                            // Конвертація ролі
                            $role = ($role == 'admin') ? 1 : 0;
                            $userId = $userData['userId'];

                            $userModel = new User();
                            $result = $userModel->updateUser($userId, $firstName, $lastName, $status, $role);

                            $editedUser = [
                                'id' => $userId,
                                'firstName' => $firstName,
                                'lastName' => $lastName,
                                'status' => $status,
                                'role' => $role,
                            ];

                            if ($result) {
                                echo json_encode(['status' => true, 'error' => null , 'user' => $editedUser ]);
                            } else {
                                echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'Failed to edit user']]);
                            }
                        } else {
                            echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'First name and last name cannot be numeric']]);
                        }
                    }
                    else {
                        echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'Incorrect input values']]);
                    }
                } else {
                    // Вивід повідомлення, якщо якесь з обов'язкових полів порожнє
                    echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'All fields are required']]);
                }

            }
            break;

        case 'updateStatusSelectedUsers':
            if (! empty($_POST['userIds']) && ! empty($_POST['actionSelected'])) {
                $userIds = $_POST['userIds'];
                $actionSelected = $_POST['actionSelected'];

                $userModel = new User();

                foreach ($userIds as $id) {
                    $result = $userModel->updateStatusUsersById($id, $actionSelected);
                }
                if ($result) {
                    echo json_encode(['status' => true, 'error' => null]);
                } else {
                    echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'Failed to edit users']]);
                }

            } else {
                // Вивід повідомлення, якщо якесь з обов'язкових полів порожнє
                echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'Missing id']]);
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
                if (!empty($userData)) {
                    echo json_encode(['status' => true, 'error' => null, 'user' => $userData]);
                } else {
                    echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'Not found user']]);
                }
            } else {
                echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'Missing id']]);
            }
            break;
    }
} else {
    echo json_encode(['status' => false, 'error' => ['code' => 100, 'message' => 'No action specified']]);
}
