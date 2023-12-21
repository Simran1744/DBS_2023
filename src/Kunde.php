<?php

require_once('DatabaseHelper.php');

class Kunde
{
    public function addKunde()
    {

        $database = new DatabaseHelper();

        $Kundennummer = '';
         if (isset($_POST['Kundennummer'])) {
             $Kundennummer = $_POST['Kundennummer'];
         }

        $Studio_ID = '';
        if (isset($_POST['Studio_ID'])) {
            $Studio_ID = $_POST['Studio_ID'];
        }


        $Vorname = '';
        if (isset($_POST['Vorname'])) {
            $Vorname = $_POST['Vorname'];
        }

        $Nachname = '';
        if (isset($_POST['Nachname'])) {
            $Nachname = $_POST['Nachname'];
        }

        $Geschlecht = '';
        if (isset($_POST['Geschlecht'])) {
            $Geschlecht = $_POST['Geschlecht'];
        }


        $success = $database->insertIntoKunde($Kundennummer, $Studio_ID, $Vorname, $Nachname, $Geschlecht);

        if ($success) {
            $message = "Kunde '{$Kundennummer} {$Nachname}'  successfully added!'";
        } else {
            $message = "Error can't insert Kunde '{$Kundennummer} {$Nachname}'!";
        }
        echo $message;
        header('Location: index.php');
        exit();
    }

    public function deleteKunde(){
        $database = new DatabaseHelper();

        $Kundennummer = '';
        if(isset($_POST['Kundennummer'])){
            $Kundennummer = $_POST['Kundennummer'];
        }

        $error_code = $database->deleteKunde_($Kundennummer);


        if ($error_code == 0){
            $message = "Kunde with ID: '{$Kundennummer}' successfully deleted!'";
        }
        else{
            $message = "Error can't delete Kunde with ID: '{$Kundennummer}'. Errorcode: {$error_code}";
        }

        echo $message;
        header('Location: index.php');
        exit();
    }

    public function updateKunde(){
        $database = new DatabaseHelper();

        $database = new DatabaseHelper();

        $Kundennummer = '';
        if (isset($_POST['Kundennummer'])) {
            $Kundennummer = $_POST['Kundennummer'];
        }

        $new_Kundennummer = '';
        if (isset($_POST['Kundennummers'])) {
            $new_Kundennummer = $_POST['Kundennummers'];
        }

        $Studio_ID = '';
        if (isset($_POST['Studio_ID'])) {
            $Studio_ID = $_POST['Studio_ID'];
        }


        $Vorname = '';
        if (isset($_POST['Vorname'])) {
            $Vorname = $_POST['Vorname'];
        }

        $Nachname = '';
        if (isset($_POST['Nachname'])) {
            $Nachname = $_POST['Nachname'];
        }

        $Geschlecht = '';
        if (isset($_POST['Geschlecht'])) {
            $Geschlecht = $_POST['Geschlecht'];
        }


        $success = $database->updateKunde_($Kundennummer, $new_Kundennummer, $Studio_ID, $Vorname, $Nachname, $Geschlecht);

        if ($success){
            $message =  "Kunde with ID: '{$Kundennummer}' successfully updated!'";
        }
        else{
            $message = "Error can't update Kunde with ID: '{$Kundennummer}'";
        }
        echo $message;
        header('Location: index.php');
        exit();

    }

}

$kunde = new Kunde();

if (isset($_POST['submitForm_2'])) {
    $kunde->addKunde();
}
if (isset($_POST['submitDelete'])) {
    $kunde->deleteKunde();
}

if (isset($_POST['submitUpdateK'])) {
    $kunde->updateKunde();
}
