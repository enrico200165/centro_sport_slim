<?php
namespace App\Core;

class Auth
{
    public static function login($user)
    {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nome' => $user['nome'],
            'email' => $user['email'],
            'ruolo' => $user['ruolo'],
            'skill_score' => $user['skill_score'] ?? 0
        ];
    }

    public static function logout()
    {
        unset($_SESSION['user']);
    }

    public static function check()
    {
        return isset($_SESSION['user']);
    }

    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }
}
