<?php

require_once('DatabaseHelper.php');


class Plz
{
    public function addPlz(){

        $database = new DatabaseHelper();

        $Plz = '';
        if(isset($_POST['Plz'])){
            $Plz = $_POST['Plz'];
        }

        $Ort = '';
        if(isset($_POST['Ort'])){
            $Ort = $_POST['Ort'];
        }

        $success = $database->insertIntoPlz($Plz, $Ort);

        if ($success){
            $message =  "Fitnessstudio '{$Plz}' successfully added!'";
        }
        else{
            $message =  "Error can't insert Fitnessstudio '{$Plz}'!";
        }
        echo $message;
    }

}

$plz = new Plz();

if(isset($_POST['addButton15'])){
    $plz->addPlz();
}

header('Location: index.php#data-table-15');

?>