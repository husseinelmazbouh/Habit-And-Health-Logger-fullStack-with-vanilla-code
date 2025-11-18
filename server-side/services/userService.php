<?php
require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/../connection/connection.php");

function getUserById_serv($id) {
    global $connection;
    if (isset($id)) {
        $user = User::find($connection, $id);
        return $user ? $user->toArray() : "User not found :(";
    } else {
        $users = User::all($connection);
        $result = [];
        foreach ($users as $user) {
            $result[] = $user->toArray();
        }
        return $result;
    }
}

function createUser_serv($data) {
    global $connection;
    if (!isset($data['email']) || !isset($data['password'])) {
        return "Email and password are required!!!!";
    }

    $existing = User::where($connection, ['email' => $data['email']]);
    if (!empty($existing)) {
        return "Email exists!!!";
    }

    $user_data = [
        'email' => $data['email'],
        'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        'role' => $data['role'] ?? 'user',
        'is_active' => 1
    ];

    $user_id = User::create($connection, $user_data);
    return $user_id ? "User created successfully :)" : "Failed to create user :(";
}

function loginUser_serv($data) {
    global $connection;
    if (!isset($data['email']) || !isset($data['password'])) {
        return "Email and password are required";
    }

    $user = User::authenticate($connection, $data['email'], $data['password']);
    if ($user) {
        return [
            "user" => $user->toArray(),
            "token" => $user->getID()
        ];
    } else {
        return "Invalid credentials!!";
    }
}

function updateUser_serv($id, $data) {
    global $connection;
    if (!isset($id)) return "User ID is required!!!";

    $user = User::find($connection, $id);
    if (!$user) {
        return "User not found:(";
    }

    if (isset($data['password'])) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    return User::update($connection, $id, $data) ? "User updated successfully :)" : "Update failed :(";
}

function deleteUser_serv($id) {
    global $connection;
    if (!isset($id)) return "User ID is required";

    $user = User::find($connection, $id);
    if (!$user) {
        return "User not found :(";
    }

    return User::delete($connection, $id) ? "User deleted successfully :)" : "Delete failed :(";
}
?>