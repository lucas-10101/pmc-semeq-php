<?php

namespace classes;

use models\User;

/**
 * Responsible for controlling the access to the application pages.
 */
class SecurityHandler
{

    private static $principal = null;
    private static $authenticated = false;

    private const PUBLIC_PAGES = array(
        "/",
        "/index.php",
        "/login.php",
        "/error.php",
        "/403.php"
    );

    private const SECURED_PAGES = array(
        '/pages/sale/sale.php' => 'SELLER'
    );

    /**
     * Validate security againts the current request.
     * @return bool
     */
    public static function verify(): void
    {
        $request_path = $_SERVER['REQUEST_URI'];

        if (SecurityHandler::isPublic($request_path)) {
            return;
        }

        $currentUser = SecurityHandler::getCurrentUser();

        foreach (SecurityHandler::SECURED_PAGES as $page => $role) {
            if (str_ends_with($request_path, $page) && $currentUser->role == $role) {
                return;
            }
        }

        SecurityHandler::handleAccessDenied();
        return;
    }

    /**
     * Get the current authenticated user or an default anonymous user if is not authenticated (public pages not require authentication)
     * @return User The current user
     */
    public static function getCurrentUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            return SecurityHandler::$principal = new User('-1', 'anonymous', 'anonymous');
        }

        if (self::$principal === null) {
            SecurityHandler::loadUserData();
        }

        SecurityHandler::$authenticated = true;
        return SecurityHandler::$principal;
    }

    /**
     * Check if the specified path is public to access.
     * @param mixed $path The path ($_SERVER['request_uri'])
     * @return bool true If the access is allowed to anynone, else otherwise
     */
    private static function isPublic($path)
    {
        foreach (SecurityHandler::PUBLIC_PAGES as $page) {
            if (str_ends_with($path, $page)) {
                return true;
            }
        }
        return false;
    }

    private static function loadUserData()
    {
        return SecurityHandler::$principal = new User('1', 'test', 'NONE');
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
}