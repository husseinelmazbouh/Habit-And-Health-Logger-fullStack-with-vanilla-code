<?php
include("../connection/connection.php");

$sql = "CREATE TABLE IF NOT EXISTS users(
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$query = $connection->prepare($sql);
$query->execute();
echo "Users table created! successfully\n";

//admin
$admin_password = password_hash('hussein123', PASSWORD_DEFAULT);
$sql = "INSERT IGNORE INTO users (email, password, role) VALUES (?, ?, 'admin')";
$query = $connection->prepare($sql);
$admin_email = 'husseinelmazbouh@test.com';
$query->bind_param("ss", $admin_email, $admin_password);
$query->execute();
echo "Admin user created! successfully\n";
?>