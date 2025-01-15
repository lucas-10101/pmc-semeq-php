<?php

namespace classes;

use models\User;

/**
 * Responsible for controlling the access to the application pages.
 */
class SecurityHandler
{

    static $principal = null;

    const PUBLIC_PAGES = array(
        "/",
        "/index.php",
        "/login.php",
        "/error.php",
        "/403.php"
    );

    const SECURED_PAGES = array(
        '/pages/sale/sale.php' => 'SELLER',
        '/pages/sale/purchase.php' => 'CLIENT'
    );

    /**
     * Validate security againts the current request.
     * @return bool
     */
    public static function verify()
    {
        $request_path = $_SERVER['REQUEST_URI'];

        if (!SecurityHandler::canAccess($request_path)) {
            SecurityHandler::handleAccessDenied();
        }

    }

    /**
     * Get the current authenticated user or an default anonymous user if is not authenticated (public pages not require authentication)
     * @return User The current user
     */
    public static function getCurrentUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            return SecurityHandler::$principal = new User();
        }

        if (self::$principal === null) {
            SecurityHandler::loadUserData();
        }

        SecurityHandler::$authenticated = true;
        return SecurityHandler::$principal;
    }

    public static function canAccess($request_path)
    {

        if (SecurityHandler::isPublic($request_path)) {
            return true;
        }

        $currentUser = SecurityHandler::getCurrentUser();

        foreach (SecurityHandler::SECURED_PAGES as $page => $role) {
            $ending = substr($request_path, strlen($request_path) - strlen($page));

            if ($ending == $page && $currentUser->role == $role) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the specified path is public to access.
     * @param mixed $path The path ($_SERVER['request_uri'])
     * @return bool true If the access is allowed to anynone, else otherwise
     */
    private static function isPublic($request_path)
    {
        foreach (SecurityHandler::PUBLIC_PAGES as $page) {

            $ending = substr($request_path, strlen($request_path) - strlen($page));
            if ($ending == $page) {
                return true;
            }
        }
        return false;
    }

    private static function loadUserData()
    {
        return SecurityHandler::$principal = new User();
    }

    private static function handleAccessDenied()
    {
        $status_code = SecurityHandler::$authenticated ? 403 : 401;

        switch ($status_code) {
            case 403:

                http_response_code(403);
                header('Location: /403.php');
                break;

            default:
                header('Location: /login.php');
                break;
        }
    }

    public static function isAuthenticated()
    {
        SessionManager::initSession();
        return SessionManager::getUser() != null;
    }
}