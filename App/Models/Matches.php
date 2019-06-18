<?php


namespace App\Models;

use Core\Model;
use PDO;

class Matches extends Model
{


    public static function getMatches($id)
    {
        $sql = 'SELECT * FROM combined WHERE NameA = :id OR NameB = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute(array(':id' => $id));

        return $stmt->fetchAll();

    }


}

