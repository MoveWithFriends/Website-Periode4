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

}

