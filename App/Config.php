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
     * Database host
     * @var string
     */
    /*    const DB_HOST = 'db.movewithfriends.nl';*/
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'movewithfriends';
    /*    const DB_NAME = 'md319900db449972';*/

    /**
     * Database user
     * @var string
     */
    /*    const DB_USER = 'md319900db449972';*/
    const DB_USER = 'root';

    /**
     * Database password
     * @var string
     */
    /*    const DB_PASSWORD = 'Tweeling33$';*/
    const DB_PASSWORD = '';
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