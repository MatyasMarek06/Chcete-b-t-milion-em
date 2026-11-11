<?php
$ceny = [
    0 => "0", 1 => "1 000", 2 => "2 000", 3 => "3 000", 4 => "5 000", 5 => "10 000",
    6 => "20 000", 7 => "40 000", 8 => "80 000", 9 => "160 000", 10 => "320 000",
    11 => "640 000", 12 => "1 250 000", 13 => "2 500 000", 14 => "5 000 000", 15 => "10 000 000"
];

$dosazenyLvl = isset($_GET['lvl']) ? (int)$_GET['lvl'] : 0;
$stav = isset($_GET['stav']) ? $_GET['stav'] : 'prohra';
$vyhra = $ceny[$dosazenyLvl];
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <title>Konec hry</title>
</head>
<body style="background: #000033; color: white; font-family: sans-serif; text-align: center; padding-top: 100px;">

<div style="background: #000066; border: 2px solid gold; border-radius: 20px; display: inline-block; padding: 50px;">
    <?php if ($stav === 'konec' || $dosazenyLvl == 15): ?>
        <h1 style="color: gold;">GRATULUJEME!</h1>
        <p style="font-size: 1.5rem;">Rozhodli jste se skončit.</p>
    <?php else: ?>
        <h1 style="color: #ff4d4d;">KONEC HRY!</h1>
        <p style="font-size: 1.5rem;">Špatná odpověď vás stála postup.</p>
    <?php endif; ?>

    <h2 style="font-size: 2.5rem; margin: 20px 0;">Vyhráváte: <?= $vyhra ?> Kč</h2>

    <button onclick="window.location.href='../index.php?restart=1'"
            style="background: gold; color: black; border: none; padding: 15px 30px; font-weight: bold; cursor: pointer; border-radius: 5px; font-size: 1.1rem;">
        Zkusit znovu
    </button>
</div>

</body>
</html>