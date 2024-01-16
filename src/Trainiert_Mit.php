<?php

require_once('DatabaseHelper.php');

class Trainiert_Mit
{
    public function addTraining(){

        $database = new DatabaseHelper();

        $Kundennummer1 = '';
        if(isset($_POST['Kundennummer1'])){
            $Kundennummer1 = $_POST['Kundennummer1'];
        }

        $Kundennummer2 = '';
        if(isset($_POST['Kundennummer2'])){
            $Kundennummer2 = $_POST['Kundennummer2'];
        }

        $Zeitpunkt = '';
        if (isset($_POST['Zeitpunkt'])) {
            $Zeitpunkt = $_POST['Zeitpunkt'];
        }

        $success = $database->insertIntoTraining($Kundennummer1, $Kundennummer2, $Zeitpunkt);

        if ($success) {
            $message = "Training '{$Kundennummer1} ' erstellt!'";
        } else {
            $message = "Training kann nicht erstellt werden. Errorcode: '{$Kundennummer1}'!";
        }
        echo $message;
    }

    public function deleteTraining(){

        $database = new DatabaseHelper();

        $Kundennummer1 = '';
        if(isset($_POST['Kundennummer1'])){
            $Kundennummer1 = $_POST['Kundennummer1'];
        }

        $Kundennummer2 = '';
        if(isset($_POST['Kundennummer2'])){
            $Kundennummer2 = $_POST['Kundennummer2'];
        }

        $Zeitpunkt = '';
        if (isset($_POST['Zeitpunkt'])) {
            $Zeitpunkt = $_POST['Zeitpunkt'];
        }

        $error_code = $database->deleteTraining_($Kundennummer1, $Kundennummer2, $Zeitpunkt);


        if ($error_code == 0){
            $message = "Training with ID: '{$Kundennummer1} {$Kundennummer2}' successfully deleted!'";
        }
        else{
            $message = "Error can't delete Training with ID: '{$Kundennummer1} {$Kundennummer2}'. Errorcode: {$error_code}";
        }
        echo $message;
    }

    public function updateTraining(){

        $database = new DatabaseHelper();


        $column = $_POST['column'];
        $value = $_POST['value'];
        $rowId = $_POST['rowId'];
        $originalValue = $_POST['originalValue'];

        $database->updateTraining_($column, $value, $rowId,$originalValue);
        echo "Update successful";
        exit;

    }


}


$tr = new Trainiert_Mit();

if (isset($_POST['addButton11'])) {
    $tr->addTraining();
}

if (isset($_POST['deleteButton12'])) {
    $tr->deleteTraining();
}

if (isset($_POST['action'])) {
    $tr->updateTraining();
}


header('Location: index.php#data-table-10');

?>