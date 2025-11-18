<?php
require_once(__DIR__ . "/../models/Habit.php");
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../services/ResponseService.php");
require_once(__DIR__ . "/../services/HabitService.php");
require_once(__DIR__ . "/../middleware/AuthMiddleware.php");

class HabitController {
    function getHabits() {
        $user_id = AuthMiddleware::authenticate();
        if (!$user_id) return;

        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $result = getHabitById_serv($id, $user_id);
        } else {
            $result = getHabitById_serv(null, $user_id);
        }
        echo ResponseService::response(200, $result);
    }

    function createHabit() {
        $user_id = AuthMiddleware::authenticate();
        if (!$user_id) return;

        $input = json_decode(file_get_contents('php://input'), true);
        $result = createHabit_serv($input, $user_id);
        echo ResponseService::response(200, $result);
    }

    function updateHabit() {
        $user_id = AuthMiddleware::authenticate();
        if (!$user_id) return;

        if (!isset($_GET["id"])) {
            echo ResponseService::response(400, "Habit ID is required :)");
            return;
        }

        $id = $_GET["id"];
        $input = json_decode(file_get_contents('php://input'), true);
        $result = updateHabit_serv($id, $input, $user_id);
        echo ResponseService::response(200, $result);
    }

    function deleteHabit() {
        $user_id = AuthMiddleware::authenticate();
        if (!$user_id) return;

        if (!isset($_GET["id"])) {
            echo ResponseService::response(400, "Habit ID is required :)");
            return;
        }

        $id = $_GET["id"];
        $result = deleteHabit_serv($id, $user_id);
        echo ResponseService::response(200, $result);
    }
}
?>