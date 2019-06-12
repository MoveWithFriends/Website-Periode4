<?php

namespace App\Controllers;

use App\Auth;
use App\Models\Timeslot;
use \Core\View;
use \App\Models\Interest;
use \App\Models\Day;
use \App\Models\Admin;
use \App\Models\user;



class Administrator extends authenticated
{
    private $interests;
    private $timeslots;
    private $days;
    private $users;
    private $activity;
    private $is_admin;
    private $user;


    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
        if (!$this->user->is_admin) {
            View::renderTemplate('/404.html');
            exit;
        }
    }

    public function __construct()
    {
        /*      $this->InterestId = $this->id;*/
        $this->interests = Interest::getInterest();
        $this->timeslots = Timeslot::getTimeslot();
        $this->days = Day::getDay();
        $this->users = Admin::getUsers();

    }


    /**
     * Show the add activity page
     *
     * @return void
     */
    public function activityAction()
    {
        view::rendertemplate('Administrator/activity.html', [
            'interests' => $this->interests
        ]);
    }

    public function timeslotAction()
    {
        view::rendertemplate('Administrator/timeslot.html', [
            'timeslots' => $this->timeslots,
            'days' => $this->days
        ]);

    }


    /**
     * Show the edit Users page
     *
     * @return void
     */
    public function usersAction()
    {
        View::renderTemplate('Administrator/users.html', [
            'users' => $this->users
        ]);
    }

    public function addActivityAction()
    {
        if (isset($_POST['activityName']) && ($_POST['activityName'] !== "")) {
            $activity = $_POST['activityName'];

            $this->activity = Admin::addActivity($activity);
            $this->redirect('/administrator/activity');
            exit;

        } else {
            $this->redirect('/administrator/activity');
            exit;
        }
    }

    public function updateActivityAction()
    {
        if (isset($_POST['InterestDelete']) && ($_POST['InterestDelete'] !== "")) {
            $this->deleteActivityAction();
        }
        Interest::deactivateInterest();
        foreach ($_POST['InterestActivate'] as $selected) {
            $this->activity = Interest::activateInterest($selected);
        }
        $this->redirect('/administrator/activity');
    }

    public function deleteActivityAction()
    {
        foreach ($_POST['InterestDelete'] as $selected) {
            $this->deleted = Admin::deleteActivity($selected);
        }
    }

    public function deleteTimeslotAction()
    {
        foreach ($_POST['TimeslotDelete'] as $selected) {
            $this->deleted = Admin::deleteTimeslot($selected);
        }
        $this->redirect('/administrator/timeslot');
    }

    public function addTimeslotAction()
    {
        if (isset($_POST['timeslotName']) && ($_POST['timeslotName'] !== "")) {
            $activity = $_POST['timeslotName'];

            $this->timeslots = Admin::addTimeslot($activity);
            $this->redirect('/administrator/timeslot');
            exit;

        } else {
            $this->redirect('/administrator/timeslot');
            exit;
        }
    }

    public function searchAction()
    {
        if (isset($_POST['submit']))
            $query = $_POST['query'];
        $column = $_POST['column'];


        $this->selectedusers = Admin::findByName($column, $query);
        View::renderTemplate('Administrator/users.html', [
            'users' => $this->selectedusers
        ]);
    }


    public function adminupdateAction()
    {
        $this->adminupdate = admin::clearAdminUser($this->user->id);
        if (isset($_POST['is_admin'])) {
            print_r($_POST['is_admin']);
            foreach ($_POST['is_admin'] as $selected) {
                $this->adminupdate = Admin::activateAdminUser($selected);

            }

            $this->redirect('/administrator/users');
            exit;
        } else {
            $this->redirect('/administrator/users');
            exit;
        }
    }
}

