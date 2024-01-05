<?php

require_once('DatabaseHelper.php');

class Betreut
{
    public function addBetreut(){

        $database = new DatabaseHelper();

        $Mitarbeiter_ID = '';
        if (isset($_POST['Mitarbeiter_ID'])) {
            $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
        }

        $Kundennummer = '';
        if (isset($_POST['Kundennummer'])) {
            $Kundennummer = $_POST['Kundennummer'];
        }

        $Zeitpunkt = '';
        if (isset($_POST['Zeitpunkt'])) {
            $Zeitpunkt = $_POST['Zeitpunkt'];
        }


        $success = $database->insertIntoBetreut($Mitarbeiter_ID, $Kundennummer, $Zeitpunkt);

        if ($success) {
            $message = "Betreung '{$Mitarbeiter_ID} {$Kundennummer} '  erfolgreich gebucht!'";
        } else {
            $message = "Betreung kann nicht gebucht werden. Errorcode: '{$Mitarbeiter_ID} {$Kundennummer}'!";
        }
        echo $message;

    }

    public function deleteBetreut(){
        $database = new DatabaseHelper();

        $Mitarbeiter_ID = '';
        if (isset($_POST['Mitarbeiter_ID'])) {
            $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
        }

        $Kundennummer = '';
        if (isset($_POST['Kundennummer'])) {
            $Kundennummer = $_POST['Kundennummer'];
        }

        $Zeitpunkt = '';
        if (isset($_POST['Zeitpunkt'])) {
            $Zeitpunkt = $_POST['Zeitpunkt'];
        }

        $error_code = $database->deleteBetreut_($Mitarbeiter_ID, $Kundennummer, $Zeitpunkt);


        if ($error_code == 0){
            $message = "Betreung with ID: '{$Mitarbeiter_ID} {$Kundennummer}' successfully deleted!'";
        }
        else{
            $message = "Error can't delete Betreuung with ID: '{$Mitarbeiter_ID} {$Kundennummer}'. Errorcode: {$error_code}";
        }
        echo $message;
    }

    public function updateBetreut(){

        $column = $_POST['column'];
        $value = $_POST['value'];
        $rowId = $_POST['rowId'];
        $originalValue = $_POST['originalValue'];


        var_dump($rowId);

        var_dump($originalValue);


        $database = new DatabaseHelper();
        $database->updateBetreut_($column, $value, $rowId, $originalValue);
        echo "Update successful";
        exit;

    }
}



$betreut = new Betreut();

if (isset($_POST['addButton8'])) {
    $betreut->addBetreut();
}

if (isset($_POST['deleteButton9'])) {
    $betreut->deleteBetreut();
}

if (isset($_POST['action'])) {
    $betreut->updateBetreut();
}

header('Location: index.php');


?>

