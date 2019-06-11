<?php

namespace App\Controllers;

use \Core\View;
use \App\A  uth;



Class Items extends Authenticated
{


    Public function indexAction()
    {

        View::renderTemplate('Items/index.html');
    }

    Public function newAction()
    {

        echo "new action";
    }

    Public function showAction()
    {

        echo "show action";
    }
}