<?php
class Otazka {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function nactiNahodnou($uroven) {
        $mysqli = $this->db->getConnection();
        $stmt = $mysqli->prepare("SELECT * FROM otazky WHERE uroven = ? ORDER BY RAND() LIMIT 1");
        $stmt->bind_param("i", $uroven);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}