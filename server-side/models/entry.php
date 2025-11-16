<?php
require_once(__DIR__ . "/Model.php");

class Entry extends Model {
    private int $id;
    private int $user_id;
    private string $entry_date;
    private string $free_text;
    private string $structured_data;
    private string $ai_analysis;
    private string $created_at;

protected static string $table = "entries";

public function __construct(array $data) {
        $this->id = (int)$data["id"];
        $this->user_id = (int)$data["user_id"];
        $this->entry_date = $data["entry_date"];
        $this->free_text = $data["free_text"];
        $this->structured_data = $data["structured_data"];
        $this->ai_analysis = $data["ai_analysis"];
        $this->created_at = $data["created_at"];
    }
public function getID() { 
        return $this->id; 
    }
public function getUserID() { 
        return $this->user_id; 
    }
public function getEntryDate() { 
        return $this->entry_date; 
    }
public function getFreeText() { 
        return $this->free_text; 
    }
public function getStructuredData() { 
        return $this->structured_data; 
    }
public function getAiAnalysis() { 
        return $this->ai_analysis; 
    }
public function getCreatedAt() { 
        return $this->created_at; 
    }

public function toArray() {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "entry_date" => $this->entry_date,
            "free_text" => $this->free_text,
            "structured_data" => json_decode($this->structured_data, true) ?: [],
            "ai_analysis" => $this->ai_analysis,
            "created_at" => $this->created_at
        ];
    }
}
?>