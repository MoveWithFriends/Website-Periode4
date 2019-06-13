<?php

namespace App\Controllers;

use App\Flash;
use App\Models\User;
use App\Models\Day;
use App\Models\Interest;
use App\Models\Matches;
use App\Models\Timeslot;
use App\Models\Availability;
use \Core\View;
use \App\Auth;

Class Profile extends Authenticated
{
    private $interests;
    private $timeslots;
    private $activities;
    private $availables;
    private $days;
    private $matches;
    public $countMatches;


    public function __construct()
    {

        $this->interests = Interest::getInterest();
        $this->timeslots = Timeslot::getTimeslot();
        $this->days = Day::getDay();

    }

    /**  show profile
     */


    public function showAction()
    {
        $id = $this->user->id;

        $this->matches = Matches::getMatches($id);

        /*        if ($this->matches == "") {
                    $this->matches = Matches::getMatchesB($id);
                }*/

        $countMatches = count($this->matches);

        $this->activities = Interest::getSelectedInterest($id);
        $this->availables = Availability::getSelected($id);

        $combined = [];
        $matchdata = [];


        foreach ($this->availables as $selected) {

            $timeslot = ($selected->id_Timeslot);
            $day = ($selected->id_Day);

            $timeslotName = Availability::getSelectedTimeslot($timeslot)->Timeslot;
            $dayName = Availability::getSelectedDay($day)->Day;

            array_push($combined, [$dayName, $timeslotName]);

        }
        foreach ($this->matches as $selected) {

            $timeslot = ($selected->id_Timeslot);
            $day = ($selected->id_Day);
            $intID = ($selected->id_Interest);

            if ($selected->NameB == $id) {
                $name = ($selected->NameA);
            } else {
                $name = ($selected->NameB);
            }

            $timeslotName = Availability::getSelectedTimeslot($timeslot)->Timeslot;
            $dayName = Availability::getSelectedDay($day)->Day;
            $InterestName = Interest::getInterestName($intID)->Interest;
            $usersmail = User::findByID($name)->email;
            $usersname = User::findByID($name)->firstname;


            /*                echo "$dayName $timeslotName en activiteit $intID met $usersname ( $usersmail ) <br>";*/

            array_push($matchdata, [$usersname, $usersmail, $InterestName, $dayName, $timeslotName]);

        }

        View::renderTemplate('Profile/show.html', [
            'user' => $this->user,
            'activities' => $this->activities,
            'interests' => $this->interests,
            'timeslots' => $this->timeslots,
            'availables' => $combined,
            'days' => $this->days,
            'matches' => $matchdata,
            'count' => $countMatches
        ]);

    }

    public function editAction()
    {

        View::renderTemplate('Profile/edit.html', [
            'user' => $this->user,

        ]);
    }

    public function updateAction()
    {
        $user = Auth::getUser();
        if ($user->updateProfile($_POST)) {
            Flash::addMessage('Veranderingen opgeslagen');

            $this->redirect('/profile/show');
        } else {
            View::renderTemplate('Profile/edit.html', [
                'user' => $user,

            ]);
        }

    }

    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();

    }
}