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
    }

    public function updateRezeptionist(){

        $column = $_POST['column'];
        $value = $_POST['value'];
        $rowId = $_POST['rowId'];

        $database = new DatabaseHelper();
        $database->updateRezeptionist_($column, $value, $rowId);
        echo "Update successful";
        exit;
    }



}


$rez = new Rezeptionist();

if (isset($_POST['addButton4'])) {
    $rez->addRezeptionist();
}

if (isset($_POST['action'])) {
    $rez->updateRezeptionist();
}

header("Location: index.php");

?>
