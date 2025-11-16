<?php
require_once(__DIR__ . "/../models/Habit.php");
require_once(__DIR__ . "/../connection/connection.php");

function getHabitById_serv($id, $user_id = null) {
    global $connection;
    if (isset($id)) {
        $habit = Habit::find($connection, $id);
        if ($habit && (!$user_id || $habit->getUserID() == $user_id)) {
            return $habit->toArray();
        }
        return "Habit not found :(";
    } else {
        if ($user_id) {
            $habits = Habit::where($connection, ['user_id' => $user_id]);
        } else {
            $habits = Habit::all($connection);
        }
        $result = [];
        foreach ($habits as $habit) {
            $result[] = $habit->toArray();
        }
        return $result;
    }
}

function createHabit_serv($data, $user_id) {
    global $connection;
    if (!isset($data['name']) || !isset($data['type'])) {
        return "Name and type are required !!!";
    }

    $habit_data = [
        'user_id' => $user_id,
        'name' => $data['name'],
        'type' => $data['type'],
        'target_value' => $data['target_value'] ?? '',
        'is_active' => $data['is_active'] ?? 1
    ];

    $habit_id = Habit::create($connection, $habit_data);
    return $habit_id ? "Habit created successfully :)" : "Failed to create habit :(";
}

function updateHabit_serv($id, $data, $user_id) {
    global $connection;
    if (!isset($id)) return "Habit ID is required !!!";

    $habit = Habit::find($connection, $id);
    if (!$habit || $habit->getUserID() != $user_id) {
        return "Habit not found or access denied !!!";
    }

    return Habit::update($connection, $id, $data) ? "Habit updated successfully :)" : "Update failed :(";
}

function deleteHabit_serv($id, $user_id) {
    global $connection;
    if (!isset($id)) return "Habit ID is required !!!";

    $habit = Habit::find($connection, $id);
    if (!$habit || $habit->getUserID() != $user_id) {
        return "Habit not found or access denied :(";
    }

    return Habit::delete($connection, $id) ? "Habit deleted :)" : "Deletion failed :(";
}
?>