<?php
require_once(__DIR__ . "/../connection/connection.php");

function parseFreeTextWithAI($free_text) {
    $api_key = "";
    
    $prompt = "Parse this health log into structured JSON. Extract: sleep_hours, steps, exercise_minutes, caffeine_units, water_glasses, mood (1-10). Return ONLY valid JSON: 
    {\"sleep_hours\": number, \"steps\": number, \"exercise_minutes\": number, \"caffeine_units\": number, \"water_glasses\": number, \"mood\": number}
    
    Text: \"$free_text\"
    
    If any field is not mentioned, set it to null.";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        "model" => "gpt-3.5-turbo",
        "messages" => [["role" => "user", "content" => $prompt]],
        "temperature" => 0.1
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $api_key
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    $content = $data['choices'][0]['message']['content'] ?? '{}';
    
    return json_decode($content, true) ?: [];
}

function generateWeeklySummary($user_id) {
    global $connection;
    
    $last_week = date('Y-m-d', strtotime('-7 days'));
    $entries = Entry::where($connection, ['user_id' => $user_id]);

    $summary_data = [];
    foreach ($entries as $entry) {
        if ($entry->getEntryDate() >= $last_week) {
            $structured = json_decode($entry->getStructuredData(), true);
            if ($structured) {
                $summary_data[] = $structured;
            }
        }
    }

    if (empty($summary_data)) {
        return ["averages" => [], "suggestion" => "No data available for the past week."];
    }

    $sleep_sum = $steps_sum = $mood_sum = 0;
    $sleep_count = $steps_count = $mood_count = 0;

    foreach ($summary_data as $data) {
        if (isset($data['sleep_hours']) && $data['sleep_hours'] !== null) {
            $sleep_sum += $data['sleep_hours'];
            $sleep_count++;
        }
        if (isset($data['steps']) && $data['steps'] !== null) {
            $steps_sum += $data['steps'];
            $steps_count++;
        }
        if (isset($data['mood']) && $data['mood'] !== null) {
            $mood_sum += $data['mood'];
            $mood_count++;
        }
    }

    $averages = [
        'sleep' => $sleep_count > 0 ? round($sleep_sum / $sleep_count, 1) : 0,
        'steps' => $steps_count > 0 ? round($steps_sum / $steps_count, 0) : 0,
        'mood' => $mood_count > 0 ? round($mood_sum / $mood_count, 1) : 0
    ];

    $suggestion = "Maintain consistent habits for better results.";
    if ($averages['sleep'] < 7) {
        $suggestion = "Try to get more sleep - aim for 7-9 hours per night.";
    } elseif ($averages['steps'] < 5000) {
        $suggestion = "Consider increasing your daily steps for better health.";
    }

    return [
        'averages' => $averages,
        'suggestion' => $suggestion
    ];
}

function getNutritionAdvice($user_data) {
    return "Consider a balanced meal with lean protein, vegetables, and whole grains. Estimated needs: 500-600 calories.";
}
?>