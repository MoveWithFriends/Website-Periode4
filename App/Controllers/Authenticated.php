<?php

namespace App\Controllers;

Abstract class Authenticated extends \Core\Controller
{
    protected function before()
    {
        $this->requireLogin();
    }
}
