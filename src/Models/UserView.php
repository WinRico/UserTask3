<?php

namespace App\Models;


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

        $output = "";
        // Цикл по кожному користувачеві
        foreach ($userObject as $user) {
            $output .= '
                <tr id="userRow_'.$user['id'].'">
                    <td><input type="checkbox" class="selectUser" data-id="'.$user['id'].'"></td>
                    <td>'. $user['firstname'] . ' ' . $user['lastname'] .'</td>
                    ';

            // Перевірка статусу користувача та встановлення відповідного кольору
            if ($user['status'] != "Active") {
                $output .= '<td  id="status'.$user['id'].'" class="status-indicator offline"><i class="fa fa-circle"></i></td>';
            } else {
                $output .= '<td id="status'.$user['id'].'" class="status-indicator online"><i class="fa fa-circle"></i></td>';
            }

            // Додавання ролі користувача
            $output .= ' 
                    <td id="role'.$user['id'].'">'. $user['role'] .'</td>
                    <td>
                    <div class="btn-group">
                     <button type="button" data-button-id="2" class="btn btn-sm btn-outline-secondary editBtn" data-id="'. $user['id'] .'"><i class="fa-regular fa-pen-to-square"></i></button>
                     <button type="button" class="btn btn-sm btn-outline-secondary deleteBtn" data-id="'. $user['id'] .'"><i class="fa-solid fa-trash"></i></button>
                    </div>
                    </td>
                </tr>';
        }

        echo $output;
    }
}