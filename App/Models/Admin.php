<?php

namespace App\Models;

use Core\Model;
use PDO;


/**
 * User model
 *
 * PHP version 7.0
 */
class Admin extends Model
{

    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     *
     * @param array $data Initial property values
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public static function getUsers()
    {
        $sql = 'SELECT * FROM users ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function findByName($column, $query)
    {
        $sql = "SELECT * FROM users WHERE firstname LIKE :searchquery";
        if ($column == 'lastname') {
            $sql = "SELECT * FROM users WHERE lastname LIKE :searchquery";
        }

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());


        $stmt->execute(array(":searchquery" => "%" . $query . "%"));


        $count = $stmt->rowCount();
        $sql = "INSERT INTO search (field, countresult, query) VALUES (:column, :count, :searchquery)";
        $stmt2 = $db->prepare($sql);
        $stmt2->execute(array(":searchquery" => $query, ":count" => $count, ":column" => $column));

        return $stmt->fetchAll();
    }

    /*Delete activity from */
    public static function deleteActivity($id)
    {
        $sql = 'DELETE FROM Interest WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute(array(':id' => $id));
    }

    public static function addActivity($activity)
    {
        $sql = "INSERT INTO Interest (Interest) VALUES (:activity)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute(array(":activity" => $activity));
    }

    /**Delete timeslot from */
    public static function deleteTimeslot($id)
    {
        $sql = 'DELETE FROM Timeslot WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute(array(':id' => $id));
    }

    /**add timeslot to */
    public static function addTimeslot($timeslot)
    {
        $sql = "INSERT INTO Timeslot (Timeslot) VALUES (:timeslot)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute(array(":timeslot" => $timeslot));
    }

    public static function updateAdmin($is_admin, $user)
    {
        $sql = 'UPDATE users
                SET is_active = :is_active
                WHERE id =:id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute(array(':id' => $user, ':is_active' => $is_admin));
    }

    public static function activateAdminUser($user)
    {
        $sql = 'UPDATE users
                SET is_admin = 1
                WHERE id =:id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->execute(array(':id' => $user));
    }

    public static function clearAdminUser($current)
    {
        $sql = 'UPDATE users SET is_admin = 0 WHERE id <> :current';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute(array(':current' => $current));

    }


}