<?php

namespace classes;

use classes\SecurityHandler;
use dao\UserDAO;

/**
 * Handle user authentication.
 */
class AuthenticationHandler
{

    public static $authenticationFailed = false;

    /**
     * Verify or delegate user authentication
     * @return void
     */
    public static function verify()
    {
        AuthenticationHandler::$authenticationFailed = false;

        SecurityHandler::verify();
        AuthenticationHandler::login();

    }

    /**
     * Authenticate the user in SecurityHandler
     * @return void
     */
    private static function login()
    {
        $isLoginPage = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['REQUEST_URI']) - strlen('/login.php')) == '/login.php';

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if (!$isLoginPage || empty($email) || empty($password)) {
            return;
        }

        $dao = new UserDAO();
        $user = $dao->findByEmailAndPassword($email, $password);

        if (is_object($user)) {
            SessionManager::setUser($user);

            header('Location: /index.php');
            exit;

        } else {
            AuthenticationHandler::$authenticationFailed = true;
        }
    }
}