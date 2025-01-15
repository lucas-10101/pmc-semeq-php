<?php

namespace classes;

use dao\ClientDAO;
use dao\SellerDAO;
use models\User;

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
        self::setObject("user", $user);

        echo "foi";
        switch ($user->role) {
            case 'SELLER':
                $dao = new SellerDAO();
                $seller = $dao->findByUserid($user->id);
                $name = $seller->name;
                break;
            case 'CLIENT':
                $dao = new ClientDAO();
                $client = $dao->findByUserid($user->id);
                $name = $client->name;
                break;

            default:
                echo "falhou";
                break;
        }
    }


    public static function getUser()
    {
        return self::getObject("user");
    }

    public static function isEnabled()
    {
        return session_status() != PHP_SESSION_NONE;
    }

    public static function initSession()
    {

        if (!SessionManager::isEnabled()) {
            session_name(SessionManager::SESSION_SCOPE);
            session_start();
        }
    }

    public static function logout()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        header("Location: /index.php");
        exit();
    }

    public static function setObject($keyName, $object)
    {
        $_SESSION["$keyName"] = serialize($object);
    }

    public static function getObject($keyName)
    {
        return self::isEnabled() ? unserialize($_SESSION["$keyName"]) : null;
    }
}