<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use App\Models\Admin;
use App\Models\Timeslot;
use App\Models\User;
use \Core\View;
use \App\Models\Interest;
use \App\Models\Day;
use PDO;


class activities extends authenticated
{

    private $interests;

    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }

    public function __construct()
    {
        //var_dump(Interest::getInterests());
        $this->interests = Interest::getInterest();
        $this->users = Admin::getUsers();

    }

    /**
     * Show all activities in the database
     *
     * @return string The request URL
     */
    public function indexAction()
    {

        view::rendertemplate('Activities/index.html', [
            'interests' => $this->interests
        ]);

    }

    /**
     * Connect selected activities from user to database. Before that first delete the
     * delected activities from THAT user that are in the database
     *
     * @return string The request URL
     */

    public function selectAction()
    {
        $userid = $this->user->id;

        User::deleteUserActivity($userid);

        if (isset($_POST['Interest']) && ($_POST['Interest'] !== ""))
            foreach ($_POST['Interest'] as $selected) {

                User::addUserActivity($selected, $userid);
            }

        $this->redirect('/profile/show');

    }


}