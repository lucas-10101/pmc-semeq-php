<?php

namespace classes;

use dao\ClientDAO;
use dao\SellerDAO;
use models\User;

error_reporting(E_ERROR);

class SessionManager
{

    const SESSION_SCOPE = "SALES_APP_SESSION_ID";

    /**
     * Set user in current session
     * @param \models\User $user
     * @return void
     */
    public static function setUser($user)
    {
        self::initSession();

        $person = null;

        switch ($user->role) {
            case 'SELLER':
                $dao = new SellerDAO();
                $person = $dao->findByUserid($user->id);
                break;
            case 'CLIENT':
                $dao = new ClientDAO();
                $person = $dao->findByUserid($user->id);
                break;
            default:
                break;
        }
        self::setObject("user", $user);
        self::setObject("user-person", $person);
    }


    public static function getUser()
    {
        return self::getObject("user");
    }

    public static function getUserPerson()
    {
        return self::getObject("user-person");
    }

    public static function initSession()
    {
        session_name(SessionManager::SESSION_SCOPE);
        session_start();
    }

    public static function logout()
    {

        self::initSession();
        session_unset();
        session_destroy();
        $_SESSION = [];

        header("Location: /index.php");
        exit();
    }

    public static function setObject($keyName, $object)
    {
        $_SESSION["$keyName"] = serialize($object);
    }

    public static function getObject($keyName)
    {
        self::initSession();
        if (array_key_exists($keyName, $_SESSION)) {
            return unserialize($_SESSION["$keyName"]);
        }
        return null;
    }
}