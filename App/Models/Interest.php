<?php


namespace App\Models;

use Core\Model;
use PDO;

class Interest extends Model
{


    public static function getInterest()
    {
        $sql = 'SELECT * FROM Interest';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function getSelectedInterest($id)
    {
        $sql = 'SELECT interest.Interest
                FROM Interest
                WHERE interest.id IN (
                    SELECT likes.id_Interest
                    FROM Likes
                    WHERE likes.id_user = :id
                ) AND interest.is_active';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute(array(':id' => $id));
        return $stmt->fetchAll();
    }

    public static function getInterestName($id)
    {
        $sql = 'SELECT interest.Interest
                FROM Interest
                WHERE interest.id = :id ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute(array(':id' => $id));
        return $stmt->fetch();
    }


    public static function activateInterest($id)
    {
        $sql = 'UPDATE Interest
                SET is_active = 1
                WHERE id =:id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute(array(':id' => $id));


    }

    public static function deactivateInterest()
    {
        $sql = 'UPDATE Interest
                SET is_active = 0';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute();
    }


}

