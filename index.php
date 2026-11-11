<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'db_conn.php';
require_once 'models/Otazka.php';

// Úplný restart hry
if (isset($_GET['restart'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Inicializace úrovně a nápověd
if (!isset($_SESSION['uroven'])) {
    $_SESSION['uroven'] = 1;
    $_SESSION['napovedy'] = ['5050' => false, 'tel' => false, 'pub' => false];
}

$otazkaModel = new Otazka($db_objekt);
$data = $otazkaModel->nactiNahodnou($_SESSION['uroven']);

// Pokud už nejsou otázky (vyhraných 15 kol)
if (!$data && $_SESSION['uroven'] > 15) {
    header("Location: view/Konec.php?stav=vyhra&lvl=15");
    exit;
} elseif (!$data) {
    // Záložní řešení pro chybu v DB
    die("Chyba: Nepodařilo se načíst otázku pro úroveň " . $_SESSION['uroven']);
}

include 'view/Herni_Pole.php';