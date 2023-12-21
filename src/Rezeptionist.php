<?php

require_once('DatabaseHelper.php');

class Rezeptionist
{

    public function addRezeptionist()
    {
        $database = new DatabaseHelper();
        $Mitarbeiter_ID = '';
        if (isset($_POST['Mitarbeiter_ID'])) {
            $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
        }


        $Arbeitszeiten = '';
        if (isset($_POST['Arbeitszeiten'])) {
            $Arbeitszeiten = $_POST['Arbeitszeiten'];
        }

        $Sprachkenntnisse = '';
        if (isset($_POST['Sprachkenntnisse'])) {
            $Sprachkenntnisse = $_POST['Sprachkenntnisse'];
        }
        $success = $database->insertIntoRezeptionist($Mitarbeiter_ID, $Arbeitszeiten, $Sprachkenntnisse);

        if ($success) {
            $message = "Rezeptionist '{$Mitarbeiter_ID}' successfully added!'";
        } else {
            $message = "Error can't insert Rezeptionist '{$Mitarbeiter_ID}'!";
        }
        echo $message;
        header('Location: index.php');
        exit();
    }

    public function updateRezeptionist(){
        $database = new DatabaseHelper();

        $Mitarbeiter_ID = '';
        if(isset($_POST['Mitarbeiter_ID'])){
            $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
        }

        $New_Mitarbeiter_ID = '';
        if(isset($_POST['Mitarbeiter_IDs'])){
            $New_Mitarbeiter_ID = $_POST['Mitarbeiter_IDs'];
        }
        $Arbeitszeiten = '';
        if(isset($_POST['Arbeitszeiten'])){
            $Arbeitszeiten = $_POST['Arbeitszeiten'];
        }

        $Sprachkenntnisse = '';
        if(isset($_POST['Sprachkenntnisse'])){
            $Sprachkenntnisse = $_POST['Sprachkenntnisse'];
        }
        $success = $database->updateRezeptionist_($Mitarbeiter_ID, $New_Mitarbeiter_ID, $Arbeitszeiten, $Sprachkenntnisse);

        if ($success){
            $message =  "Rezeptionist with ID: '{$Mitarbeiter_ID}' successfully updated!'";
        }
        else{
            $message = "Error can't update Rezeptionist with ID: '{$Mitarbeiter_ID}'";
        }
        echo $message;
        header('Location: index.php');
        exit();
    }



}

$rezeptionist = new Rezeptionist();

if (isset($_POST['submitForm'])) {
    $rezeptionist->addRezeptionist();
}

if (isset($_POST['submitUpdate'])) {
    $rezeptionist->updateRezeptionist();
}


