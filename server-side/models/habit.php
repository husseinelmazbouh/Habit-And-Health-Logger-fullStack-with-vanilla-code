<?php
require_once(__DIR__ . "/Model.php");

class Habit extends Model {
    private int $id;
    private int $user_id;
    private string $name;
    private string $type;
    private string $target_value;
    private bool $is_active;
    private string $created_at;

    protected static string $table = "habits";

    public function __construct(array $data) {
        $this->id = (int)$data["id"];
        $this->user_id = (int)$data["user_id"];
        $this->name = $data["name"];
        $this->type = $data["type"];
        $this->target_value = $data["target_value"];
        $this->is_active = (bool)$data["is_active"];
        $this->created_at = $data["created_at"];
    }

    public function getID() { 
        return $this->id; 
    }
    public function getUserID() { 
        return $this->user_id; 
    }
    public function getName() { 
        return $this->name; 
    }
    public function getType() { 
        return $this->type; 
    }
    public function getTargetValue() { 
        return $this->target_value; 
    }
    public function isActive() { 
        return $this->is_active; 
    }
    public function getCreatedAt() { 
        return $this->created_at; 
    }

    public function toArray() {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "name" => $this->name,
            "type" => $this->type,
            "target_value" => $this->target_value,
            "is_active" => $this->is_active,
            "created_at" => $this->created_at
        ];
    }
}
?>