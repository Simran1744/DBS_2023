<?php

require_once('DatabaseHelper.php');

class Coacht
{
    public function addCoacht(){

        $database = new DatabaseHelper();

        $Mitarbeiter_ID = '';
        if (isset($_POST['Mitarbeiter_ID'])) {
            $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
        }

        $Kundennummer = '';
        if (isset($_POST['Kundennummer'])) {
            $Kundennummer = $_POST['Kundennummer'];
        }

        $Beginnzeit = '';
        if (isset($_POST['Beginnzeit'])) {
            $Beginnzeit = $_POST['Beginnzeit'];
        }

        $Endzeit = '';
        if (isset($_POST['Endzeit'])) {
            $Endzeit = $_POST['Endzeit'];
        }

        $Trainingsdatum = '';
        if (isset($_POST['Trainingsdatum'])) {
            $Trainingsdatum = $_POST['Trainingsdatum'];
        }


        $success = $database->insertIntoCoacht($Mitarbeiter_ID, $Kundennummer, $Beginnzeit, $Endzeit, $Trainingsdatum);

        if ($success) {
            $message = "Trainingseinheit '{$Mitarbeiter_ID} {$Kundennummer} '  erfolgreich gebucht!'";
        } else {
            $message = "Trainigseinheit kann nicht gebucht werden. Errorcode: '{$Mitarbeiter_ID} {$Kundennummer}'!";
        }
        echo $message;

    }


    public function deleteCoacht(){

        $database = new DatabaseHelper();

        $Mitarbeiter_ID = '';
        if (isset($_POST['Mitarbeiter_ID'])) {
            $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
        }

        $Kundennummer = '';
        if (isset($_POST['Kundennummer'])) {
            $Kundennummer = $_POST['Kundennummer'];
        }

        $Beginnzeit = '';
        if (isset($_POST['Beginnzeit'])) {
            $Beginnzeit = $_POST['Beginnzeit'];
        }

        $Endzeit = '';
        if (isset($_POST['Endzeit'])) {
            $Endzeit = $_POST['Endzeit'];
        }

        $error_code = $database->deleteCoacht_($Mitarbeiter_ID, $Kundennummer, $Beginnzeit, $Endzeit);


        if ($error_code == 0){
            $message = "Trainingseinheit with ID: '{$Mitarbeiter_ID} {$Kundennummer}' successfully deleted!'";
        }
        else{
            $message = "Error can't delete Trainingseinheit with ID: '{$Mitarbeiter_ID} {$Kundennummer}'. Errorcode: {$error_code}";
        }
        echo $message;

    }


    public function updateCoacht(){

        $column = $_POST['column'];
        $value = $_POST['value'];
        $rowId = $_POST['rowId'];

        var_dump(
            $rowId
        );


        $database = new DatabaseHelper();
        $database->updateCoacht_($column, $value, $rowId);
        echo "Update successful";
        exit;

    }

}

$coacht = new Coacht();

if (isset($_POST['addButton7'])) {
    $coacht->addCoacht();
}

if (isset($_POST['deleteButton8'])) {
    $coacht->deleteCoacht();
}

if (isset($_POST['action'])) {
    $coacht->updateCoacht();
}

header('Location: index.php');


?>

