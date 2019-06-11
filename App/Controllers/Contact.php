<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;


/**
 * Home controller
 *
 * PHP version 7.0
 */
class Contact extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('contact/index.html');
    }
}
