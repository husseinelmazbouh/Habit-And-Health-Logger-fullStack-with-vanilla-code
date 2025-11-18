<?php
require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/../services/ResponseService.php");

class AuthMiddleware {
    public static function authenticate() {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? $_GET['token'] ?? null;
        
        if (!$token) {
            echo ResponseService::response(401, "Authentication required :)");
            return null;
        }

        $user_id = self::validateToken($token);
        if (!$user_id) {
            echo ResponseService::response(401, "Invalid token :( ");
            return null;
        }

        return $user_id;
    }

    private static function validateToken($token) {
        global $connection;
        $user = User::find($connection, $token);
        return $user ? $user->getID() : null;
    }
}
?>