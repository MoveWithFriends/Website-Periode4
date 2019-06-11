<?php


namespace App\Models;

use PDO;


class Availability extends \Core\Model
{
    public static function getSelectedTimeslot($timeslot)
    {
        $sql = 'SELECT Timeslot.Timeslot
                        FROM Timeslot
                        WHERE timeslot.id = :id_timeslot';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        /*        $stmt->bindValue(':id_timeslot', $timeslot, PDO::PARAM_INT);*/

        $stmt->execute(array(':id_timeslot' => $timeslot));
        return $stmt->fetchAll();
    }

    public static function getSelectedDay($day)
    {
        $sql = 'SELECT Day.Day 
                        FROM Day 
                        WHERE day.id = :id_day';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->bindValue(':id_day', $day, PDO::PARAM_INT);


        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getSelected($id)
    {
        $sql = 'SELECT Available.id_Day, Available.id_Timeslot
                            FROM Available
                            WHERE Available.id_user = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);


        $stmt->execute();
        return $stmt->fetchAll();
    }
}
