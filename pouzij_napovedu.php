<?php
session_start();

// Pokud v session nápovědy ještě nejsou, vytvoříme je
if (!isset($_SESSION['napovedy'])) {
    $_SESSION['napovedy'] = ['5050' => false, 'tel' => false, 'pub' => false];
}

if (isset($_POST['typ'])) {
    $typ = $_POST['typ']; // např. '5050', 'tel' nebo 'pub'
    if (array_key_exists($typ, $_SESSION['napovedy'])) {
        $_SESSION['napovedy'][$typ] = true; // Označíme jako použité navždy (do restartu)
        echo json_encode(['status' => 'success', 'typ' => $typ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Neznámý typ nápovědy']);
    }
}
exit;