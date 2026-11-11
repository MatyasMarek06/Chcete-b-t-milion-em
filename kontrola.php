<?php
session_start();
require_once 'db_conn.php';
require_once 'controllers/Hra.php';

$hra = new Hra();

if (isset($_POST['id']) && isset($_POST['volba'])) {
    $vysledek = $hra->zkontrolujOdpoved($_POST['id'], $_POST['volba'], $db_objekt);

    // Vrátíme JSON s informací, zda je to správně a jaká je nová úroveň
    echo json_encode([
        'status' => ($vysledek ? 'spravne' : 'spatne'),
        'nova_uroven' => $_SESSION['uroven']
    ]);
}
exit;