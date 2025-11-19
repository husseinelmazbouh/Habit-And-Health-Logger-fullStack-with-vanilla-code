<?php
require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../services/ResponseService.php");
require_once(__DIR__ . "/../services/UserService.php");

class UserController {
    
    function register() {
        global $connection;
        $input = json_decode(file_get_contents('php://input'), true);
        $result = createUser_serv($input);
        echo ResponseService::response(200, $result);
    }

    function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        $result = loginUser_serv($input);
        
        if (is_array($result)) {
            echo ResponseService::response(200, $result);
        } else {
            echo ResponseService::response(401, $result);
        }
    }

    function getUsers() {
        require_once(__DIR__ . "/../middleware/AdminMiddleware.php");
        if (!AdminMiddleware::checkAdmin()) return;

        $result = getUserById_serv(null);
        echo ResponseService::response(200, $result);
    }

    function updateUser() {
        require_once(__DIR__ . "/../middleware/AdminMiddleware.php");
        if (!AdminMiddleware::checkAdmin()) return;

        if (!isset($_GET["id"])) {
            echo ResponseService::response(400, "User ID is required :)");
            return;
        }

        $id = $_GET["id"];
        $input = json_decode(file_get_contents('php://input'), true);
        $result = updateUser_serv($id, $input);
        echo ResponseService::response(200, $result);
    }

    function deleteUser() {
        require_once(__DIR__ . "/../middleware/AdminMiddleware.php");
        if (!AdminMiddleware::checkAdmin()) return;

        if (!isset($_GET["id"])) {
            echo ResponseService::response(400, "User ID is required :) ");
            return;
        }

        $id = $_GET["id"];
        $result = deleteUser_serv($id);
        echo ResponseService::response(200, $result);
    }
}
?>