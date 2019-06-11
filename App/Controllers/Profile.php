<?php

namespace App\Controllers;

use App\Flash;
use \Core\View;
use \App\Auth;

Class Profile extends Authenticated
{


    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }

    /**  show profile
     */

    public function showAction()
    {
        View::renderTemplate('Profile/show.html', [
            'user' => $this->user
        ]);
    }

    Public function editAction()
    {
        View::renderTemplate('Profile/edit.html', [
            'user' => $this->user
        ]);
    }

    public function updateAction()
    {
        if ($this->user->updateProfile($_POST)) {

            Flash::addMessage('Changes saved');

            $this->redirect('/profile/show');

        } else {
            View::renderTemplate('Profile/edit.html', [
                'user' => $this->user
            ]);
        }

    }
}