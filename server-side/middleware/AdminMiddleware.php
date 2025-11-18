<?php
require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/../services/ResponseService.php");

class AdminMiddleware {
    public static function checkAdmin() {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? $_GET['token'] ?? null;
        
        if (!$token) {
            echo ResponseService::response(401, "Authentication required :)");
            return false;
        }

        global $connection;
        $user = User::find($connection, $token);
        
        if (!$user || $user->getRole() !== 'admin') {
            echo ResponseService::response(403, "Admin access required :(");
            return false;
        }

        return true;
    }
}
?>