<?php
require_once(__DIR__ . "/../models/Entry.php");
require_once(__DIR__ . "/../connection/connection.php");

function getEntryById_serv($id, $user_id = null) {
    global $connection;
    if (isset($id)) {
        $entry = Entry::find($connection, $id);
        if ($entry && (!$user_id || $entry->getUserID() == $user_id)) {
            return $entry->toArray();
        }
        return "Entry not found :(";
    } else {
        if ($user_id) {
            $entries = Entry::where($connection, ['user_id' => $user_id]);
        } else {
            $entries = Entry::all($connection);
        }
        $result = [];
        foreach ($entries as $entry) {
            $result[] = $entry->toArray();
        }
        return $result;
    }
}

function createEntry_serv($data, $user_id) {
    global $connection;
    if (!isset($data['entry_date'])) {
        return "Entry date is required :)";
    }

    $structured_data = $data['structured_data'] ?? [];
    if (!is_string($structured_data)) {
        $structured_data = json_encode($structured_data);
    }

    $entry_data = [
        'user_id' => $user_id,
        'entry_date' => $data['entry_date'],
        'free_text' => $data['free_text'] ?? '',
        'structured_data' => $structured_data,
        'ai_analysis' => $data['ai_analysis'] ?? ''
    ];

    $entry_id = Entry::create($connection, $entry_data);
    return $entry_id ? "Entry created successfully :)" : "Failed to create entry :(";
}

function updateEntry_serv($id, $data, $user_id) {
    global $connection;
    if (!isset($id)) return "Entry ID is required :)";

    $entry = Entry::find($connection, $id);
    if (!$entry || $entry->getUserID() != $user_id) {
        return "Entry not found or access denied :(";
    }

    if (isset($data['structured_data']) && !is_string($data['structured_data'])) {
        $data['structured_data'] = json_encode($data['structured_data']);
    }

    return Entry::update($connection, $id, $data) ? "Entry updated successfully :)" : "Update failed :(";
}

function deleteEntry_serv($id, $user_id) {
    global $connection;
    if (!isset($id)) return "Entry ID is required :)";

    $entry = Entry::find($connection, $id);
    if (!$entry || $entry->getUserID() != $user_id) {
        return "Entry not found or access denied :(";
    }

    return Entry::delete($connection, $id) ? "Entry deleted successfully :)" : "Delete failed :(";
}
?>