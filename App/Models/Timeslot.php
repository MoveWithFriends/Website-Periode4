<?php


namespace App\Models;


use PDO;

class Timeslot extends \Core\Model
{


    public static function getTimeslot()
    {
        $sql = 'SELECT * FROM Timeslot';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetchAll();
    }


    /**
     * @return mixed
     * onderstaande methodes zijn no gniet functioneel maar kunnen in
     * de toekomst door de ADMIN worden toegevoegd aan de functionaliteit om
     * timeslots aan te passen als dagdelen niet meer toereikend zijn
     */
    public static function deleteTimeslot()
    {
        $sql = 'DELETE * FROM Timeslot';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function addTimeslot()
    {
        $sql = 'ADD check FROM Timeslot';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetchAll();
    }
}

