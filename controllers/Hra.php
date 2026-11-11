<?php
class Hra {
    public function zkontrolujOdpoved($id, $volba, $db) {
        $mysqli = $db->getConnection();

        // Získání správné odpovědi
        $stmt = $mysqli->prepare("SELECT spravna FROM otazky WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $otazka = $res->fetch_assoc();

        if ($otazka && strtolower($volba) === strtolower($otazka['spravna'])) {
            // DŮLEŽITÉ: Zvýšení úrovně v session
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['uroven']++;
            return true;
        }
        return false;
    }
}