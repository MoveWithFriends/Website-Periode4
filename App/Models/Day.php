<?php


namespace App\Models;


use PDO;

class Day extends \Core\Model
{


    public static function getDay()
    {
        $sql = 'SELECT * FROM Day';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
