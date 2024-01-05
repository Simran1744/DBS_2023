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

        $Zeitpunkt = '';
        if (isset($_POST['Zeitpunkt'])) {
            $Zeitpunkt = $_POST['Zeitpunkt'];
        }

        $success = $database->insertIntoKon($Mitarbeiter_ID, $Kundennummer, $Mitgliedschaftsnummer, $Zeitpunkt);

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

        $Zeitpunkt = '';
        if (isset($_POST['Zeitpunkt'])) {
            $Zeitpunkt = $_POST['Zeitpunkt'];
        }

        $error_code = $database->deleteKon_($Mitarbeiter_ID, $Kundennummer, $Mitgliedschaftsnummer, $Zeitpunkt);


        if ($error_code == 0){
            $message = "Mitgliedschaft with ID: '{$Kundennummer} {$Mitgliedschaftsnummer}' successfully deleted!'";
        }
        else{
            $message = "Error can't delete Mitgliedschaft with ID: '{$Kundennummer} {$Mitgliedschaftsnummer}'. Errorcode: {$error_code}";
        }
        echo $message;
    }

    public function updateKon(){

        $database = new DatabaseHelper();


        $column = $_POST['column'];
        $value = $_POST['value'];
        $rowId = $_POST['rowId'];
        $originalValue = $_POST['originalValue'];


        $database->updateKon_($column, $value, $rowId,$originalValue);
        echo "Update successful";
        exit;

    }


}


$kon = new Kontrolliert();

if (isset($_POST['addButton10'])) {
    $kon->addKon();
}

if (isset($_POST['deleteButton11'])) {
    $kon->deleteKon();
}

if (isset($_POST['action'])) {
    $kon->updateKon();
}


header('Location: index.php');


?>

