<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use App\Models\Matches;
use App\Models\Timeslot;
use App\Models\User;
use \Core\View;
use \App\Models\Day;
use \App\Controllers\Authenticated;


class availability extends Authenticated
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
        $this->timeslots = Timeslot::getTimeslot();
        $this->days = Day::getDay();

    }

    public function indexAction()
    {
        $id = $this->user->id;
        $this->matches = Matches::getMatches($id);

        $countMatches = count($this->matches);
        $availables = \App\Models\Availability::getSelected($this->user->id);

        view::rendertemplate('Available/index.html', [
            'timeslots' => $this->timeslots,
            'days' => $this->days,
            'availables' => $availables,
            'count' => $countMatches
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
                $selected = explode(' ', $selected);
                $day = $selected[0];
                $timeslot = $selected[1];

                User::addUserAvailable($user_id, $day, $timeslot);
            }

        $this->redirect('/profile/show');

    }


}