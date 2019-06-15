<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Locale instellingen
     */

    const DB_HOST = 'localhost';
    const DB_USER = 'root';


    const DB_PASSWORD = 'root';
    const DB_NAME = 'MWFregistration';

    /*    const DB_NAME = 'md319900db449972';
        const DB_PASSWORD = 'Tweeling33$';
        const DB_HOST = 'db.movewithfriends.nl';
    const DB_USER = 'md319900db449972';*/


    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    /**
     * sevret key
     *
     */
    const SECRET_KEY = 'aXgnxDh8xwctJh1F5vbIjHuX4X3slb42';


    const MAILGUN_API_KEY = 'key-851798e2cb78ec608dd63599da6f995f';

    Const MAILGUN_DOMAIN = 'https://api.eu.mailgun.net/v3/movewithfriends.nl';
}
