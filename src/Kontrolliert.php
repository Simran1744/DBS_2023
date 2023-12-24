<?php

require_once('DatabaseHelper.php');

class Kontrolliert
{
    public function addKon(){

        $database = new DatabaseHelper();

        $Mitarbeiter_ID = '';
        if(isset($_POST['Mitarbeiter_ID'])){
            $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
        }

        $Kundennummer = '';
        if (isset($_POST['Kundennummer'])) {
            $Kundennummer = $_POST['Kundennummer'];
        }

        $Mitgliedschaftsnummer= '';
        if (isset($_POST['Mitgliedschaftsnummer'])) {
            $Mitgliedschaftsnummer = $_POST['Mitgliedschaftsnummer'];
        }

        $success = $database->insertIntoKon($Mitarbeiter_ID, $Kundennummer, $Mitgliedschaftsnummer);

        if ($success) {
            $message = "Kontrolle '{$Kundennummer} ' erstellt!'";
        } else {
            $message = "Kontrolle kann nicht erstellt werden. Errorcode: '{$Kundennummer}'!";
        }
        echo $message;
    }

    public function deleteKon(){

        $database = new DatabaseHelper();

        $Mitarbeiter_ID = '';
        if(isset($_POST['Mitarbeiter_ID'])){
            $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
        }

        $Kundennummer = '';
        if (isset($_POST['Kundennummer'])) {
            $Kundennummer = $_POST['Kundennummer'];
        }

        $Mitgliedschaftsnummer= '';
        if (isset($_POST['Mitgliedschaftsnummer'])) {
            $Mitgliedschaftsnummer = $_POST['Mitgliedschaftsnummer'];
        }

        $error_code = $database->deleteKon_($Mitarbeiter_ID, $Kundennummer, $Mitgliedschaftsnummer);


        if ($error_code == 0){
            $message = "Mitgliedschaft with ID: '{$Kundennummer} {$Mitgliedschaftsnummer}' successfully deleted!'";
        }
        else{
            $message = "Error can't delete Mitgliedschaft with ID: '{$Kundennummer} {$Mitgliedschaftsnummer}'. Errorcode: {$error_code}";
        }
        echo $message;
    }


}


$kon = new Kontrolliert();

if (isset($_POST['submitForm_8'])) {
    $kon->addKon();
}

if (isset($_POST['submitDelete_8'])) {
    $kon->deleteKon();
}


?>

<br>
<a href="index.php">
    go back
</a>