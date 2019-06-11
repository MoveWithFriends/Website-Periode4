<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use App\Models\Timeslot;
use App\Models\User;
use \Core\View;
use \App\Models\Interest;
use \App\Models\Day;


class availability extends authenticated
{

    private $interests;
    private $timeslots;
    private $days;
    private $activity_id;

    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }

    public function __construct()
    {
        //var_dump(Interest::getInterests());
        $this->timeslots = Timeslot::getTimeslot();
        $this->days = Day::getDay();
        /*$this->available = Available::getAvailable();*/

    }

    public function indexAction()
    {
        view::rendertemplate('Available/index.html', [
            'timeslots' => $this->timeslots,
            'days' => $this->days
        ]);


    }

    public function editAction()
    {
        echo 'edit action';


    }

    public function selectAction()
    {

        $user_id = $this->user->id;
        User::deleteUserAvailable($user_id);

        /**
         * String van 2 getallen die uit de array komen splitsen in dag en dagdeel
         */
        if (isset($_POST['available']) && ($_POST['available'] !== ""))
            foreach ($_POST['available'] as $selected) {

                $day = $selected[0];
                $timeslot = $selected[1];

                User::addUserAvailable($user_id, $day, $timeslot);

            }

        $this->redirect('/profile/show', [
            'timeslots' => $this->timeslots,
            'days' => $this->days
        ]);

    }


}