<?php

require_once('DatabaseHelper.php');

class Mitgliedschaft
{

    public function addMG(){

        $database = new DatabaseHelper();

        $Kundennummer = '';
        if (isset($_POST['Kundennummer'])) {
            $Kundennummer = $_POST['Kundennummer'];
        }

        $Mitgliedschaftsnummer= '';
        if (isset($_POST['Mitgliedschaftsnummer'])) {
            $Mitgliedschaftsnummer = $_POST['Mitgliedschaftsnummer'];
        }

        $Mitgliedschafts_Stufe = '';
        if (isset($_POST['Mitgliedschafts_Stufe'])) {
            $Mitgliedschafts_Stufe = $_POST['Mitgliedschafts_Stufe'];
        }

        $Monatskosten = '';
        if (isset($_POST['Monatskosten'])) {
            $Monatskosten = $_POST['Monatskosten'];
        }

        $Gueltigkeit= '';
        if (isset($_POST['Gueltigkeit'])) {
            $Gueltigkeit = $_POST['Gueltigkeit'];
        }

        $Erstellungsdatum = '';
        if (isset($_POST['Erstellungsdatum'])) {
            $Erstellungsdatum = $_POST['Erstellungsdatum'];
        }


        $success = $database->insertIntoMG($Kundennummer, $Mitgliedschaftsnummer, $Mitgliedschafts_Stufe, $Monatskosten,
        $Gueltigkeit, $Erstellungsdatum);

        if ($success) {
            $message = "Mitgliedschaft '{$Kundennummer} ' erstellt!'";
        } else {
            $message = "Mitgliedschaft kann nicht erstellt werden. Errorcode: '{$Kundennummer}'!";
        }
        echo $message;
    }
    public function deleteMG(){

        $database = new DatabaseHelper();

        $Kundennummer = '';
        if (isset($_POST['Kundennummer'])) {
            $Kundennummer = $_POST['Kundennummer'];
        }

        $Mitgliedschaftsnummer= '';
        if (isset($_POST['Mitgliedschaftsnummer'])) {
            $Mitgliedschaftsnummer = $_POST['Mitgliedschaftsnummer'];
        }

        $error_code = $database->deleteMG_($Kundennummer, $Mitgliedschaftsnummer);


        if ($error_code == 0){
            $message = "Mitgliedschaft with ID: '{$Kundennummer} {$Mitgliedschaftsnummer}' successfully deleted!'";
        }
        else{
            $message = "Error can't delete Mitgliedschaft with ID: '{$Kundennummer} {$Mitgliedschaftsnummer}'. Errorcode: {$error_code}";
        }
        echo $message;
    }

    public function updateMG(){

        $database = new DatabaseHelper();


        $column = $_POST['column'];
        $value = $_POST['value'];
        $rowId = $_POST['rowId'];

        $database->updateMG_($column, $value, $rowId);
        echo "Update successful";
        exit;

    }
}





$mg = new Mitgliedschaft();

if (isset($_POST['addButton9'])) {
    $mg->addMG();
}

if (isset($_POST['deleteButton10'])) {
    $mg->deleteMG();
}

if (isset($_POST['action'])) {
    $mg->updateMG();
}

header('Location: index.php');


?>

