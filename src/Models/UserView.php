<?php

namespace App\Models;
include_once '../views/includes.php';

/**
 * Клас, що відповідає за відображення даних користувача.
 */
class UserView extends User
{
    /**
     * Вивід даних таблиці users.
     *
     * @param array $userObject Масив об'єктів користувачів.
     * @return void
     */
    public function userView($userObject) {
        // Перевірка, чи масив користувачів не порожній
        if (empty($userObject)) {
            echo "No users found."; // Вивід повідомлення про відсутність користувачів
            return;
        }

        // Початок формування таблиці
        $output = '
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';

        // Цикл по кожному користувачеві
        foreach ($userObject as $user) {
            $output .= '
                <tr>
                    <td><input type="checkbox" class="selectUser" data-id="'.$user['id'].'"></td>
                    <td>'. $user['firstname'] . ' ' . $user['lastname'] .'</td>
                    ';

            // Перевірка статусу користувача та встановлення відповідного кольору
            if ($user['status'] != "Active") {
                $output .= '<td class="status-indicator offline"><i class="fa fa-circle"></i></td>';
            } else {
                $output .= '<td class="status-indicator"><i class="fa fa-circle"></i></td>';
            }

            // Додавання ролі користувача
            $output .= ' 
                    <td>'. $user['role'] .'</td>
                    <td>
                    <div class="btn-group">
                     <button type="button" data-button-id="2" class="btn btn-sm btn-outline-secondary editBtn" data-id="'. $user['id'] .'"><i data-feather="edit"></i></button>
                     <button type="button" class="btn btn-sm btn-outline-secondary deleteBtn" data-id="'. $user['id'] .'"><i data-feather="trash-2"></i></button>
                    </div>
                    </td>
                </tr>';
        }

        // Завершення формування таблиці
        $output .= '
                </tbody>
            </table>';

        // Виведення сформованої таблиці
        echo $output;
    }
}