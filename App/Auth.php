<?php

namespace App;

use \App\Models\rememberedLogin;
use \App\Models\User;

/**
 * authentication
 *
 */
class Auth
{
    public static function login($user, $remember_me)
    {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->id;

        if ($remember_me) {

            if ($user->rememberLogin()) {

                setcookie('remember_me', $user->remember_token, $user->expiry_timestamp, '/');

            }
        }
    }

    Public static function logout()
    {
        //usnset all of the session vaiables
        $_SESSION = [];
        //Delete the session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        //Finally destroy the session
        session_destroy();
        static::forgetLogin();

    }

    public static function rememberRequestedPage()
    {
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    }

    public static function getReturnToPage()
    {
        return $_SESSION['return_to'] ?? '/profile/show';
    }

    public static function getUser()
    {
        if (isset($_SESSION['user_id'])) {
            return User::findByID($_SESSION['user_id']);
        } else {
            return static::loginFromRememberedCookie();
        }
    }

    protected static function loginFromRememberedCookie()
    {
        $cookie = $_COOKIE['remember_me'] ?? false;
        if ($cookie) {
            $remembered_login = rememberedLogin::findByToken($cookie);

            if ($remembered_login && !$remembered_login->hasExpired()) {
                $user = $remembered_login->getUser();
                static::login($user, false);
                return $user;
            }
        }
    }

    protected static function forgetLogin()
    {
        $cookie = $_COOKIE['remember_me'] ?? false;

        if ($cookie) {
            $remembered_login = rememberedLogin::findByToken($cookie);
            if ($remembered_login) {
                $remembered_login->delete();

            }
            setcookie('remember_me', '', time() - 3600); //set to expire in the past
        }
    }

}