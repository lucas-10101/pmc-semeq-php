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
        '/pages/sales.php' => 'AUTHENTICATED',
        '/pages/new_sale.php' => 'SELLER',
        '/pages/products.php' => 'SELLER',
        '/pages/suppliers.php' => 'SELLER',
        '/pages/product_supplier.php' => 'SELLER',
        '/api/autocomplete/clients_autocomplete.php' => 'SELLER',
        '/api/suppliers/list_by_product.php' => 'SELLER',
        '/api/autocomplete/products_autocomplete.php' => 'AUTHENTICATED',
        '/api/sell/save.php' => 'SELLER',
    );

    /**
     * Validate security againts the current request.
     * @return bool
     */
    public static function verify()
    {
        $request_path = $_SERVER['SCRIPT_NAME'];

        if (!SecurityHandler::canAccess($request_path)) {
            SecurityHandler::handleAccessDenied();
        }

    }


    public static function canAccess($request_path)
    {

        if (SecurityHandler::isPublic($request_path)) {
            return true;
        }

        $currentUser = SessionManager::getUser();

        foreach (SecurityHandler::SECURED_PAGES as $page => $role) {
            $ending = substr($request_path, strlen($request_path) - strlen($page));

            if ($ending == $page && ($currentUser->role == $role || $role == "AUTHENTICATED")) {
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

    private static function handleAccessDenied()
    {
        $status_code = SecurityHandler::isAuthenticated() ? 403 : 401;

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