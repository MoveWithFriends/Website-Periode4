<?php

namespace App\Models;

use Core\Model;
use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;


/**
 * User model
 *
 * PHP version 7.0
 */
class User extends Model
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
        /*        $this->activity = Interest::getInterest();*/
    }

    /**
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $token = new Token();
            $hashed_token = $token->getHash();
            $this->activation_token = $token->getValue();


            $sql = 'INSERT INTO users (firstname, lastname, email, password_hash, phonenumber, gender, birthdate, activation_hash, preferredgender)
                    VALUES (:firstname, :lastname, :email, :password_hash, :phonenumber, :gender, :birthdate, :activation_hash, :preferredgender)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
            $stmt->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':phonenumber', $this->phonenumber, PDO::PARAM_STR);
            $stmt->bindValue(':gender', $this->gender, PDO::PARAM_STR);
            $stmt->bindValue(':preferredgender', $this->preferredgender, PDO::PARAM_STR);
            $stmt->bindValue(':birthdate', $this->birthdate, PDO::PARAM_STR);
            $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    /**
     * Validate current property values, adding validation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        // Name
        if ($this->firstname == '') {
            $this->errors[] = 'Voornaam is verplicht';
        }

        // email address
        if ($this->email == '') {
            $this->errors[] = 'Emailadres ontbreekt';
        } elseif (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Ongeldig emailadres';
        }
        if (static::emailExists($this->email, $this->id ?? null)) {
            $this->errors[] = 'Email is al in gebruik';
        }


        // Password

        if (isset($this->password)) {

            if (strlen($this->password) < 6) {
                $this->errors[] = 'Wachtwoord moet uit minimaal 6 karakters bestaan';
            } else {

                if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
                    $this->errors[] = 'Wachtwoord moet tenminste een letter bevatten';
                }

                if (preg_match('/.*[0-9]+.*/i', $this->password) == 0) {
                    $this->errors[] = 'Wachtwoord moet tenminste een cijfer bevatten';
                }
            }

        }
        if (isset($this->phonenumber)) {

            /*            if (strlen($this->phonenumber) <> 10) {
                            $this->errors[] = 'Telefoonnummer moet uit 10 cijfers bestaan';
                        }*/
            if ($this->phonenumber == '') {
                $this->errors[] = 'Telefoonnummer is verplicht';
            } else {

                if (preg_match('/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im', $this->phonenumber) == 0) {
                    $this->errors[] = 'Telefoonummer voldoet niet aan de voorwaarden';
                }
            }
        }
        if (isset($this->birthdate)) {

            /*            if (strlen($this->phonenumber) <> 10) {
                            $this->errors[] = 'Telefoonnummer moet uit 10 cijfers bestaan';
                        }*/
            if ($this->birthdate == '') {
                $this->errors[] = 'Geboortedatum is verplicht';
            } /*else {

                if (preg_match('/^([1-9]|[1][012])\/|-([1-9]|[1][0-9]|[2][0-9]|[3][01])\/|-([1][6-9][0-9][0-9]|[2][0][01][0-9])$/', $this->birthdate) == 0) {
                    $this->errors[] = 'Geboortedatum voldoet niet aan de voorwaarden';
                }
            }*/
        }

    }

    /**
     * See if a user record already exists with the specified email
     *
     * @param string $email email address to search for
     *
     * @return boolean  True if a record already exists with the specified email, false otherwise
     */
    public static function emailExists($email, $ignore_id = null)
    {
        $user = static::findByEmail($email);
        if ($user) {
            if ($user->id != $ignore_id) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $email email adres to serach for
     * @return mixed User object if found, false otherwise
     */
    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Authenticate a user by email and password.
     *
     * @param string $email email address
     * @param string $password password
     *
     * @return mixed  The user object or false if authentication fails
     */
    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if ($user && $user->is_active)
            if (password_verify($password, $user->password_hash)) {
                return $user;

            }

        return false;
    }

    /**
     * Find a user model by ID
     *
     * @param string $id The user ID
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Remember the login by inserting a new unique token into the remembered_logins table
     * for this user record
     *
     * @return boolean  True if the login was remembered successfully, false otherwise
     */
    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();

        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;  // 30 days from now

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Send password reset instructions to the user specified
     *
     * @param string $email The email address
     *
     * @return void
     */
    public static function sendPasswordReset($email)
    {
        $user = static::findByEmail($email);

        if ($user) {

            if ($user->startPasswordReset()) {

                $user->sendPasswordResetEmail();

            }
        }

    }

    /**
     * Start the password reset process by generating a new token and expiry
     *
     * @return void
     */
    protected function startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->password_reset_token = $token->getValue();

        $expiry_timestamp = time() + 60 * 60 * 2;  // 2 hours from now

        $sql = 'UPDATE users
                SET password_reset_hash = :token_hash,
                    password_reset_expires_at = :expires_at
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Send password reset instructions in an email to the user
     *
     * @return void
     */
    protected function sendPasswordResetEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;

        $text = View::getTemplate('Password/reset_email.txt', ['url' => $url]);
        $html = View::getTemplate('Password/reset_email.html', ['url' => $url]);

        Mail::send($this->email, 'Password reset', $text, $html);
    }

    public static function findPasswordReset($token)

    {
        $token = new Token($token);
        $hashed_token = $token->getHash();

        $sql = 'SELECT * FROM users
                WHERE password_reset_hash = :token_hash';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        $user = $stmt->fetch();
        if ($user) {
            if (strtotime($user->password_reset_expires_at) > time()) {
                return $user;
            }
        }

    }

    public function resetPassword($password)
    {
        $this->password = $password;

        $this->validate();

        if (empty($this->errors)) {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
            $sql = 'UPDATE users
                    SET password_hash = :password_hash,
                    password_reset_hash = NULL,
                    password_reset_expires_at = NULL
                        WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();


        }
        return false;

    }

    /**
     * Send an email to the user containing the activation link
     *
     * @return void
     */
    public function sendActivationEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/signup/activate/' . $this->activation_token;

        $text = View::getTemplate('Signup/activation_email.txt', ['url' => $url]);
        $html = View::getTemplate('Signup/activation_email.html', ['url' => $url]);

        Mail::send($this->email, 'Account activation', $text, $html);
    }

    /**
     * Activate the user account with the specified activation token
     *
     * @param string $value Activation token from the URL
     *
     * @return void
     */
    public static function activate($value)
    {
        $token = new Token($value);
        $hashed_token = $token->getHash();

        $sql = 'UPDATE users
                SET is_active = 1,
                    activation_hash = null
                WHERE activation_hash = :hashed_token';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function updateProfile($data)
    {

        $this->firstname = $data['firstname'];
        $this->lastname = $data['lastname'];

        if ($data['password'] != '') {
            $this->password = $data['password'];
        }
        $this->email = $data['email'];
        $this->phonenumber = $data['phonenumber'];
        $this->birthdate = $data['birthdate'];
        $this->preferredgender = $data['preferredgender'];
        $this->validate();
        if (empty($this->errors)) {
            $sql = 'UPDATE users
                    SET firstname = :firstname,
                        lastname = :lastname,
                        email = :email,
                        phonenumber = :phonenumber,
                        birthdate = :birthdate,
                        preferredgender = :preferredgender
                        ';

            //Add password if it is set
            if (isset($this->password)) {
                $sql .= ', password_hash = :password_hash';
            }
            $sql .= "\nWHERE id = :id";

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
            $stmt->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':phonenumber', $this->phonenumber, PDO::PARAM_STR);
            $stmt->bindValue(':birthdate', $this->birthdate, PDO::PARAM_STR);
            $stmt->bindValue(':preferredgender', $this->preferredgender, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            //add password if it is set
            if (isset($this->password)) {
                $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
                $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            }

            return $stmt->execute();

        }
        return false;

    }

    /*    public function permissions()
        {
            return $this->('App\Models\UserPermission', 'User_id');
        }*/
    public static function addUserActivity($data, $user)
    {
        $sql = "INSERT INTO Likes (id_user, id_Interest) VALUES (:user_id, :id)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $user, PDO::PARAM_INT);
        $stmt->bindValue(':id', $data, PDO::PARAM_INT);
        $stmt->execute();

    }

    public static function deleteUserActivity($userid)
    {
        /** Alle gegevens van de current user moeten uit de likes tabel worden gehaald
         *
         **/
        $sql = "DELETE FROM Likes WHERE id_user = :user_id";


        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $userid, PDO::PARAM_INT);
        $stmt->execute();
    }

    public static function addUserAvailable($user_id, $day, $timeslot)
    {
        $sql = "INSERT INTO Available (id_user, id_Day, id_Timeslot) VALUES (:user_id, :day_id, :timeslot_id)";


        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':day_id', $day, PDO::PARAM_INT);
        $stmt->bindValue(':timeslot_id', $timeslot, PDO::PARAM_INT);
        $stmt->execute();

    }

    public static function deleteUserAvailable($user_id)
    {
        /** Alle gegevens van de current user moeten uit de likes tabel worden gehaald
         *
         **/
        $sql = "DELETE FROM Available WHERE id_user = :user_id";


        $db = static::getDB();
        $stmt = $db->prepare($sql);

        /**current user ID moet nog gekoppeld worden aan de user_id. Moet nog uitzoeken HOE
         **/
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    }


}
