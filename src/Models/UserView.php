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

        $output = "";
        // Цикл по кожному користувачеві
        foreach ($userObject as $user) {
            $statusClass = $user['status'] != 'Active' ? 'offline' : 'online';
            $output .= '<tr id="userRow_' . $user['id'] . '">';
            $output .= '<td><input type="checkbox" class="selectUser" data-id="' . $user['id'] . '"></td>';
            $output .= '<td id="userName' . $user['id'] . '">' . $user['firstname'] . ' ' . $user['lastname'] . '</td>';
            $output .= '<td id="status' . $user['id'] . '" class="status-indicator ' . $statusClass . '"><i class="fa fa-circle"></i></td>';
            $output .= '<td id="role' . $user['id'] . '">' . $user['role'] . '</td>';
            $output .= '<td>';
            $output .= '<div class="btn-group">';
            $output .= '<button type="button" data-button-id="2" class="btn btn-sm btn-outline-secondary editBtn" data-id="' . $user['id'] . '"><i class="fa fa-pencil"></i></button>';
            $output .= '<button type="button" class="btn btn-sm btn-outline-secondary deleteBtn" data-id="' . $user['id'] . '"><i class="fa fa-trash"></i></button>';
            $output .= '</div>';
            $output .= '</td>';
            $output .= '</tr>';
        }

        echo $output;
    }
}