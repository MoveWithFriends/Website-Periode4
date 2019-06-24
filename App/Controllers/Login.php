<?php

namespace App\Controllers;

use Core\Controller;
use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;


Class Login extends Controller
{
    /**
     * Show the login page
     *
     * @return void
     */
    Public Function newAction()
    {
        View::renderTemplate('Login/new.html');
    }

    /**
     * Log in a user
     *
     * @return void
     */
    public function createAction()
    {
        $user = User::authenticate($_POST['email'], $_POST['password']);

        $remember_me = isset($_POST['remember_me']);

        if ($user) {

            Auth::login($user, $remember_me);

            Flash::addMessage('Inloggen geslaagd');

            $this->redirect(Auth::getReturnToPage());

        } else {
            Flash::addMessage('Inloggen niet geslaagd, probeer het opnieuw', Flash::WARNING);

            View::renderTemplate('Login/new.html', [
                'email' => $_POST['email'],
                'remember_me' => $remember_me
            ]);
        }
    }


    /**
     * Log out a user
     *
     * @return void
     */
    public function destroyAction()
    {

        Auth::logout();
        $this->redirect('/login/show-logout-message');
    }

    public function showLogoutMessage()
    {
        Flash::addMessage('Uitloggen geslaagd');
        $this->redirect('/');
    }


}