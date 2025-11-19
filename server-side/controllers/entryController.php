<?php
require_once(__DIR__ . "/../models/Entry.php");
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../services/ResponseService.php");
require_once(__DIR__ . "/../services/EntryService.php");
require_once(__DIR__ . "/../middleware/AuthMiddleware.php");

class EntryController {
    function getEntries() {
        $user_id = AuthMiddleware::authenticate();
        if (!$user_id) return;

        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $result = getEntryById_serv($id, $user_id);
        } else {
            $result = getEntryById_serv(null, $user_id);
        }
        echo ResponseService::response(200, $result);
    }

    function createEntry() {
        
        $user_id = AuthMiddleware::authenticate();
        if (!$user_id) return;

        $input = json_decode(file_get_contents('php://input'), true);
        $result = createEntry_serv($input, $user_id);
        echo ResponseService::response(200, $result);
    }

    function updateEntry() {
        $user_id = AuthMiddleware::authenticate();
        if (!$user_id) return;

        if (!isset($_GET["id"])) {
            echo ResponseService::response(400, "Entry ID is required :)");
            return;
        }

        $id = $_GET["id"];
        $input = json_decode(file_get_contents('php://input'), true);
        $result = updateEntry_serv($id, $input, $user_id);
        echo ResponseService::response(200, $result);
    }

    function deleteEntry() {
        $user_id = AuthMiddleware::authenticate();
        if (!$user_id) return;

        if (!isset($_GET["id"])) {
            echo ResponseService::response(400, "Entry ID is required :)");
            return;
        }

        $id = $_GET["id"];
        $result = deleteEntry_serv($id, $user_id);
        echo ResponseService::response(200, $result);
    }
}
?>