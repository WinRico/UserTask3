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
        $output = '<table id="userTable" class="table table-bordered">';
        $output .='<thead><tr>';
        $output .='<th><input type="checkbox" id="selectAll"></th>';
        $output .='<th>Name</th>';
        $output .='<th>Status</th>';
        $output .='<th>Role</th>';
        $output .='<th>Actions</th>';
        $output .='</tr>';
        $output .='</thead>';
        $output .='<tbody>';

        // Цикл по кожному користувачеві
        foreach ($userObject as $user) {
            $statusClass = !$user['status'] ? 'offline' : 'online';
            $role = $user['role'] != 1 ? 'user' : 'admin';
            $output .= '<tr id="userRow_' . $user['id'] . '">';
            $output .= '<td><input type="checkbox" class="selectUser" data-id="' . $user['id'] . '"></td>';
            $output .= '<td><a id="firstName_' . $user['id'] . '">' . $user['firstname'] . '</a>'. ' ' .'<a id="lastName_' . $user['id'] . '">' . $user['lastname'] . '</a></td>';
            $output .= '<td id="status_' . $user['id'] . '" class="status-indicator ' . $statusClass . '"><i class="fa fa-circle"></i></td>';
            $output .= '<td id="role_' . $user['id'] . '">' . $role . '</td>';
            $output .= '<td>';
            $output .= '<div class="btn-group">';
            $output .= '<button type="button" data-button-id="2" class="btn btn-sm btn-outline-secondary editBtn" data-id="' . $user['id'] . '"><i class="fa fa-pencil"></i></button>';
            $output .= '<button type="button" class="btn btn-sm btn-outline-secondary deleteBtn" data-id="' . $user['id'] . '"><i class="fa fa-trash"></i></button>';
            $output .= '</div>';
            $output .= '</td>';
            $output .= '</tr>';
        }
        $output .='</tbody></table>';
        echo $output;
    }
}