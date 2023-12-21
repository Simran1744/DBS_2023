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

        $Trainingsdauer = '';
        if (isset($_POST['Trainingsdauer'])) {
            $Trainingsdauer = $_POST['Trainingsdauer'];
        }



        $success = $database->insertIntoCoacht($Mitarbeiter_ID, $Kundennummer, $Beginnzeit, $Endzeit, $Trainingsdatum, $Trainingsdauer);

        if ($success) {
            $message = "Trainingseinheit '{$Mitarbeiter_ID} {$Kundennummer} '  erfolgreich gebucht!'";
        } else {
            $message = "Trainigseinheit kann nicht gebucht werden. Errorcode: '{$Mitarbeiter_ID} {$Kundennummer}'!";
        }
        echo $message;
        header('Location: index.php');
        exit();
    }

}

$coacht = new Coacht();

if (isset($_POST['submitForm_4'])) {
    $coacht->addCoacht();
}