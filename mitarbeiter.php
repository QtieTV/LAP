<?php
    require "includes/dbh.inc.php";

    if (count($_POST) == 0) {
        $_POST["inputNachname"] = "";
        $_POST["inputVorname"] = "";
    }
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
    <form method="post">
        <fieldset>
            <legend>Nach Mitarbeiter suchen</legend>
            Nachname: 
            <input type="text" name="inputNachname">
            Vorname: 
            <input type="text" name="inputVorname">
        </fieldset>
        <input type="submit" value="Filtern">
    </form>
    <?php
    $inputs = [];
    $where = "";


    // FILTERN
    if (count($_POST) > 0) {
        if (strlen($_POST["inputNachname"]) > 0) {
            $inputs[] = "Nachname = '" . $_POST["inputNachname"] . "'";
        }
        if (strlen($_POST["inputVorname"]) > 0) {
            $inputs[] = "Vorname = '" . $_POST["inputVorname"] . "'";
        }

        if (count($inputs) > 0) {
            $where = "
                WHERE (
                    ". implode(" AND ", $inputs) ."
                )
            ";
        }
    }

    // MITARBEITER
    $sql = "
        SELECT * 
        FROM tbl_mitarbeiter 
        ". $where ."
    ";
    $alleMitarbeiter = $conn->query($sql) or die("Fehler bei der DB Anfrage " . $conn->error . "<br>" . $sql);
    echo"<ul>";

    while ($mitarbeiter = $alleMitarbeiter->fetch_object()) {
        echo"
                <li>". $mitarbeiter->Nachname . " " . $mitarbeiter->Vorname ."</li>
            <ul>
        ";

        //EINSAETZE
        $sql = "
            SELECT * 
            FROM tbl_einsatz 
            WHERE (
                FIDMitarbeiter = ". $mitarbeiter->IDMitarbeiter ."
            )
        ";
        $einsaetze = $conn->query($sql) or die("Fehler bei der DB Anfrage" . $conn->error . "<br>" . $sql);
        while ($einsatz = $einsaetze->fetch_object()) {
            echo"
                <li>von ". $einsatz->Startzeitpunkt . "<br>bis " . $einsatz->Endzeitpunkt ."</li>
            ";
        }
        echo"</ul>";
    }
    echo"</ul>";
    ?>
</body>
</html>
