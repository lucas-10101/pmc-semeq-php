<?php

namespace classes;

use models\User;

class SessionManager
{

    private static const SESSION_SCOPE = "SALES_APP_SESSION_ID";

    /**
     * Set user in current session
     * @param \models\User $user
     * @return void
     */
    public static function setUser($user)
    {

    }

    public static function isEnabled()
    {
        return session_status() !== PHP_SESSION_NONE;
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
}