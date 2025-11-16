<?php
require_once(__DIR__ . "/../connection/connection.php");
require_once(__DIR__ . "/../services/ResponseService.php");
require_once(__DIR__ . "/../services/AIService.php");
require_once(__DIR__ . "/../middleware/AuthMiddleware.php");

class AIController {
    function parseText() {
        $user_id = AuthMiddleware::authenticate();
        if (!$user_id) return;

        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['free_text'])) {
            echo ResponseService::response(400, "Free text is required !!!");
            return;
        }

        $structured_data = parseFreeTextWithAI($input['free_text']);
        echo ResponseService::response(200, $structured_data);
    }

    function weeklySummary() {
        $user_id = AuthMiddleware::authenticate();
        if (!$user_id) return;

        $summary = generateWeeklySummary($user_id);
        echo ResponseService::response(200, $summary);
    }

    function nutritionAdvice() {
        $user_id = AuthMiddleware::authenticate();
        if (!$user_id) return;

        $input = json_decode(file_get_contents('php://input'), true);
        $advice = getNutritionAdvice($input['user_data'] ?? '');
        echo ResponseService::response(200, ["advice" => $advice]);
    }
}
?>