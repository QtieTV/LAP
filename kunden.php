<?php
    require "includes/dbh.inc.php";
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>learn</title>
</head>
<body>
    <?php
    // KUNDEN
    $sql = "
        SELECT *
        FROM tbl_kunden
    ";
    $kunden = $conn->query($sql) or die("Fehler bei der DB Anfrage! " . $conn->error . "<br>" . $sql);
    echo"<ul>";
    while ($kunde = $kunden->fetch_object()) {
        echo"
            <li>". $kunde->Nachname . " " . $kunde->Vorname ."</li>
        ";

        // EINSAETZE
        $sql = "
            SELECT 
                SUM(TIMESTAMPDIFF(MINUTE,Startzeitpunkt,Endzeitpunkt)) AS sumStunden,
                Startzeitpunkt,
                Endzeitpunkt 
            FROM tbl_einsatz 
            WHERE FIDKunde = '". $kunde->IDKunde ."'
        ";
        $einsaetze = $conn->query($sql) or die ("Fehler bei der DB Anfrage! " . $conn->error . "<br>" . $sql);
        echo"<ul>";
        while ($einsatz = $einsaetze->fetch_object()) {
            echo"
                <li>von ". $einsatz->Startzeitpunkt . "<br>bis " . $einsatz->Endzeitpunkt . "</li>
                <li>gearbeitete Stunden: ". $einsatz->sumStunden/60 ."h</li>
                <li>Kosten: ". $einsatz->sumStunden ."€</li>
            ";
        }
        echo"</ul>";
    }
    echo"</ul>";
    ?>
</body>
</html>
