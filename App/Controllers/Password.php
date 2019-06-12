<?php

namespace App\Controllers;

use Core\Controller;
use \Core\View;
use \App\Models\User;

/**
 * Password controller
 *
 * PHP version 7.0
 */
class Password extends Controller
{

    /**
     * Show the forgotten password page
     *
     * @return void
     */
    public function forgotAction()
    {
        View::renderTemplate('Password/forgot.html');
    }

    /**
     * Send the password reset link to the supplied email
     *
     * @return void
     */
    public function requestResetAction()
    {
        $email = $_POST['email'];
        $user = $this->getUserOrExitEmail($email);
        if ($user) {
            User::sendPasswordReset($_POST['email']);
            View::renderTemplate('Password/reset_requested.html');
        } else {
            echo "foutje bedankt";
        }


    }

    public function resetAction()
    {
        $token = $this->route_params['token'];
        $user = $this->getUserOrExit($token);

        View::renderTemplate('Password/reset.html', ['token' => $token]);

    }

    public function resetPasswordAction()
    {
        $token = $_POST['token'];

        $user = $this->getUserOrExit($token);

        if ($user->resetPassword($_POST['password'])) {

            View::renderTemplate('Password/reset_success.html');

        } else {
            View::renderTemplate('Password/reset.html', [
                'token' => $token,
                'user' => $user
            ]);
        }


    }


    protected function getUserOrExit($token)
    {
        $user = User::findPasswordReset($token);
        if ($user) {
            return $user;
        } else {
            View::renderTemplate('Password/token_expired.html');
            exit;
        }
    }

    protected function getUserOrExitEmail($email)
    {
        $user = User::findByEmail($email);
        if ($user) {
            return $user;
        } else {
            View::renderTemplate('Password/email_not_in_database.html');
            exit;
        }
    }

}