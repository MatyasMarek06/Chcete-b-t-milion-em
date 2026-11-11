<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Milionář - IT Edice</title>
    <style>
        .vybrana { background: linear-gradient(180deg, #ff9d00, #ff6a00) !important; color: white !important; border: 2px solid white !important; }
        .spravne { background: linear-gradient(180deg, #28a745, #1e7e34) !important; color: white !important; }
        .spatne { background: linear-gradient(180deg, #dc3545, #a71d2a) !important; color: white !important; }
        .pouzito { background: #400 !important; color: #888 !important; border: 1px solid red !important; cursor: not-allowed; opacity: 0.6; }
        .aktivni-uroven { background: gold !important; color: black !important; font-weight: bold; box-shadow: 0 0 15px gold; }

        #potvrdit-odpoved { display: none; margin-top: 20px; width: 100%; padding: 15px; background: gold; color: black; font-weight: bold; border-radius: 50px; cursor: pointer; border: none; }
        #menu-posun { display: none; margin-top: 20px; text-align: center; background: rgba(0,255,0,0.1); padding: 20px; border-radius: 15px; border: 1px solid green; }
    </style>
</head>
<body>

<div class="kontejner">
    <div class="hlavni-cast">
        <div class="napovedy">
            <button id="n-5050" class="<?= ($_SESSION['napovedy']['5050'] ?? false) ? 'pouzito' : '' ?>" onclick="<?= ($_SESSION['napovedy']['5050'] ?? false) ? '' : 'pouzij5050()' ?>">50:50</button>
            <button id="n-tel" class="<?= ($_SESSION['napovedy']['tel'] ?? false) ? 'pouzito' : '' ?>" onclick="<?= ($_SESSION['napovedy']['tel'] ?? false) ? '' : 'pouzijNapovedu(\'tel\')' ?>">📞</button>
            <button id="n-pub" class="<?= ($_SESSION['napovedy']['pub'] ?? false) ? 'pouzito' : '' ?>" onclick="<?= ($_SESSION['napovedy']['pub'] ?? false) ? '' : 'pouzijNapovedu(\'pub\')' ?>">👥</button>
        </div>

        <div class="otazka-box">
            <h2 id="text-otazky"><?= htmlspecialchars($data['zneni']) ?></h2>
        </div>

        <div class="odpovedi">
            <button class="btn-odpoved" id="btn-a" onclick="oznacit('a')"><span>A:</span> <?= htmlspecialchars($data['a']) ?></button>
            <button class="btn-odpoved" id="btn-b" onclick="oznacit('b')"><span>B:</span> <?= htmlspecialchars($data['b']) ?></button>
            <button class="btn-odpoved" id="btn-c" onclick="oznacit('c')"><span>C:</span> <?= htmlspecialchars($data['c']) ?></button>
            <button class="btn-odpoved" id="btn-d" onclick="oznacit('d')"><span>D:</span> <?= htmlspecialchars($data['d']) ?></button>
        </div>

        <button id="potvrdit-odpoved" onclick="poslatKeKontrole(<?= $data['id'] ?>)">POTVRDIT ODPOVĚĎ</button>

        <div id="menu-posun">
            <h3 style="color: #28a745;">✓ Správná odpověď!</h3>
            <button onclick="window.location.reload()" style="padding: 10px 30px; background: #28a745; color: white; border: none; cursor: pointer; border-radius: 5px; font-weight: bold;">DALŠÍ OTÁZKA</button>
        </div>
    </div>

    <div class="zebricek">
        <div class="logo">IT MILIONÁŘ</div>
        <?php
        $ceny = [15=>"10M", 14=>"5M", 13=>"2.5M", 12=>"1.25M", 11=>"640 000", 10=>"320 000", 9=>"160 000", 8=>"80 000", 7=>"40 000", 6=>"20 000", 5=>"10 000", 4=>"5000", 3=>"3000", 2=>"2000", 1=>"1000"];
        foreach ($ceny as $lvl => $castka): ?>
            <div id="lvl-row-<?= $lvl ?>" class="radek-penez <?= ($_SESSION['uroven'] == $lvl) ? 'aktivni-uroven' : '' ?>">
                <span class="cislo-lvl"><?= $lvl ?>.</span> <?= $castka ?> Kč
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    let aktualneVybrano = null;
    let zablokovano = false;

    function oznacit(p) {
        if (zablokovano) return;
        document.querySelectorAll('.btn-odpoved').forEach(b => b.classList.remove('vybrana'));
        aktualneVybrano = p;
        document.getElementById('btn-' + p).classList.add('vybrana');
        document.getElementById('potvrdit-odpoved').style.display = 'block';
    }

    function poslatKeKontrole(id) {
        if (!aktualneVybrano || zablokovano) return;
        zablokovano = true;
        document.getElementById('potvrdit-odpoved').style.display = 'none';

        let fd = new FormData();
        fd.append('id', id);
        fd.append('volba', aktualneVybrano);

        fetch('kontrola.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'spravne') {
                    document.getElementById('btn-' + aktualneVybrano).classList.remove('vybrana');
                    document.getElementById('btn-' + aktualneVybrano).classList.add('spravne');
                    document.getElementById('menu-posun').style.display = 'block';
                } else {
                    document.getElementById('btn-' + aktualneVybrano).classList.remove('vybrana');
                    document.getElementById('btn-' + aktualneVybrano).classList.add('spatne');
                    setTimeout(() => {
                        window.location.href = 'view/Konec.php?stav=prohra&lvl=' + (<?= $_SESSION['uroven'] ?> - 1);
                    }, 1500);
                }
            });
    }

    function pouzij5050() {
        let m = ['a','b','c','d'];
        let spravna = "<?= $data['spravna'] ?>".toLowerCase();
        let s = m.filter(x => x !== spravna).sort(() => 0.5 - Math.random()).slice(0, 2);
        s.forEach(p => document.getElementById('btn-' + p).style.visibility = 'hidden');

        fetch('pouzij_napovedu.php', { method: 'POST', body: (new URLSearchParams({'typ':'5050'})) });
        document.getElementById('n-5050').classList.add('pouzito');
        document.getElementById('n-5050').onclick = null;
    }

    function pouzijNapovedu(typ) {
        fetch('pouzij_napovedu.php', { method: 'POST', body: (new URLSearchParams({'typ':typ})) });
        document.getElementById('n-' + typ).classList.add('pouzito');
        document.getElementById('n-' + typ).onclick = null;
        alert("Nápověda využita!");
    }
</script>
</body>
</html>