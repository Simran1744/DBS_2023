<?php

require_once('DatabaseHelper.php');

class PersonalTrainer
{
    public function addPersonalTrainer()
    {

        $database = new DatabaseHelper();

        $Mitarbeiter_ID = '';
        if(isset($_POST['Mitarbeiter_ID'])){
            $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
        }

        $Geschlecht = '';
        if(isset($_POST['Geschlecht'])){
            $Geschlecht = $_POST['Geschlecht'];
        }

        $Spezialisierung= '';
        if(isset($_POST['Spezialisierung'])){
            $Spezialisierung = $_POST['Spezialisierung'];
        }


        $success = $database->insertIntoPersonalTrainer($Mitarbeiter_ID,$Geschlecht,$Spezialisierung);

        if ($success){
            $message = "Personal Trainer '{$Mitarbeiter_ID}' successfully added!'";
        }
        else{
            $message =  "Error can't insert Personal Trainer '{$Mitarbeiter_ID}'!";
        }

        echo $message;

    }



    public function updatePersonalTrainer(){

        $column = $_POST['column'];
        $value = $_POST['value'];
        $rowId = $_POST['rowId'];

        $database = new DatabaseHelper();
        $database->updatePersonalTrainer_($column, $value, $rowId);
        echo "Update successful";
        exit;

    }



}


$pers= new PersonalTrainer();

if (isset($_POST['addButton3'])) {
    $pers->addPersonalTrainer();
}

if (isset($_POST['action'])) {
    $pers->updatePersonalTrainer();
}

header('Location: index.php#data-table-3');

?>