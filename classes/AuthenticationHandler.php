<?php

namespace classes;

use classes\SecurityHandler;

/**
 * Handle user authentication.
 */
class AuthenticationHandler
{

    /**
     * Verify or delegate user authentication
     * @return void
     */
    public static function verify()
    {
        SecurityHandler::verify();
        AuthenticationHandler::login();
    }

    /**
     * Authenticate the user in SecurityHandler
     * @return void
     */
    private static function login()
    {

        if (!isset($_POST['username'], $_POST['password'])) {
            return;
        }

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    }
}