<?php
include("../connection/connection.php");

$sql = "CREATE TABLE IF NOT EXISTS habits(
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL,
    target_value VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

$query = $connection->prepare($sql);
$query->execute();
echo "Habits table created! successfully\n ";
?>