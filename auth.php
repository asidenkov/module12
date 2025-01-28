<?php

// Возвращает массив пользователей с хэшами паролей
function getUsersList() {
    return [
        'Марина' => password_hash('password1', PASSWORD_DEFAULT),
        'Анастасия' => password_hash('password2', PASSWORD_DEFAULT),
        'Юлия' => password_hash('password3', PASSWORD_DEFAULT),
    ];
}

// Проверяет, существует ли пользователь
function existsUser($login) {
    $users = getUsersList();
    return isset($users[$login]);
}

// Проверяет корректность пароля
function checkPassword($login, $password) {
    $users = getUsersList();
    if (existsUser($login)) {
        return password_verify($password, $users[$login]);
    }
    return false;
}

// Получает текущего пользователя (если вошел)
function getCurrentUser() {
    session_start();
    return $_SESSION['user'] ?? null;
}
?>
