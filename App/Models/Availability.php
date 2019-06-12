<?php


namespace App\Models;

use Core\Model;
use PDO;


class Availability extends Model
{
    public static function getSelectedTimeslot($timeslot)
    {
        $sql = 'SELECT Timeslot
                        FROM Timeslot
                        WHERE id = :id_timeslot';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        /*        $stmt->bindValue(':id_timeslot', $timeslot, PDO::PARAM_INT);*/

        $stmt->execute(array(':id_timeslot' => $timeslot));
        return $stmt->fetch();
    }

    public static function getSelectedDay($day)
    {
        $sql = 'SELECT Day 
                        FROM Day 
                        WHERE id = :id_day';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->bindValue(':id_day', $day, PDO::PARAM_INT);


        $stmt->execute();
        return $stmt->fetch();
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
