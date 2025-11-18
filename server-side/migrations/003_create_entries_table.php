<?php
include("../connection/connection.php");

$sql = "CREATE TABLE IF NOT EXISTS entries(
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    entry_date DATE NOT NULL,
    free_text TEXT,
    structured_data JSON,
    ai_analysis TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_date (user_id, entry_date)
)";

$query = $connection->prepare($sql);
$query->execute();
echo "Entries table created! successfully\n ";
?>