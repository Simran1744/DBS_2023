<?php

require_once('DatabaseHelper.php');

class Mitarbeiter
{

    public function addMitarbeiter()
    {

        $database = new DatabaseHelper();

        $Mitarbeiter_ID = '';
        if(isset($_POST['Mitarbeiter_ID'])){
            $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
        }

        $Studio_ID = '';
        if(isset($_POST['Studio_ID'])){
            $Studio_ID = $_POST['Studio_ID'];
        }

        $Vorname = '';
        if(isset($_POST['Vorname'])){
            $Vorname = $_POST['Vorname'];
        }

        $Nachname = '';
        if(isset($_POST['Nachname'])){
            $Nachname = $_POST['Nachname'];
        }

        $success = $database->insertIntoMitarbeiter($Mitarbeiter_ID, $Studio_ID, $Vorname, $Nachname);

        if ($success){
            $message = "Mitarbeiter '{$Mitarbeiter_ID} {$Nachname}' successfully added!'";
        }
        else{
            $message =  "Error can't insert Mitarbeiter '{$Mitarbeiter_ID} {$Nachname}'!";
        }

        echo $message;
    }

    public function deleteMitarbeiter(){

        $database = new DatabaseHelper();
        $Mitarbeiter_ID = '';
        if(isset($_POST['Mitarbeiter_ID'])){
            $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
        }

        $error_code = $database->deleteMitarbeiter_($Mitarbeiter_ID);


        if ($error_code == 0){
            $message = "Mitarbeiter with ID: '{$Mitarbeiter_ID}' successfully deleted!'";
        }
        else{
            $message = "Error can't delete Mitarbeiter with ID: '{$Mitarbeiter_ID}'. Errorcode: {$error_code}";
        }
        echo $message;

    }

    public function updateMitarbeiter(){

        $column = $_POST['column'];
        $value = $_POST['value'];
        $rowId = $_POST['rowId'];

        $database = new DatabaseHelper();
        $database->updateMitarbeiter_($column, $value, $rowId);
        echo "Update successful";
        exit;

    }


}




$mit = new Mitarbeiter();

if (isset($_POST['addButton2'])) {
    $mit->addMitarbeiter();
}
if (isset($_POST['deleteButton2'])) {
    $mit->deleteMitarbeiter();
}

if (isset($_POST['action'])) {
    $mit->updateMitarbeiter();
}

header('Location: index.php#data-table-2');

?>
