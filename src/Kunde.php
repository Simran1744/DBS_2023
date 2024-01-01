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
    }

    public function updateKunde(){

        $column = $_POST['column'];
        $value = $_POST['value'];
        $rowId = $_POST['rowId'];

        $database = new DatabaseHelper();
        $database->updateKunde_($column, $value, $rowId);
        echo "Update successful";
        exit;

    }

}



$kunde = new Kunde();

if (isset($_POST['addButton5'])) {
    $kunde->addKunde();
}
if (isset($_POST['deleteButton3'])) {
    $kunde->deleteKunde();
}

if (isset($_POST['action'])) {
    $kunde->updateKunde();
}


header('Location: index.php');

?>