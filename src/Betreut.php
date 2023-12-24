<?php
require_once('DatabaseHelper.php');

class Betreut
{
    public function addBetreut(){

        $database = new DatabaseHelper();

        $Mitarbeiter_ID = '';
        if (isset($_POST['Mitarbeiter_ID'])) {
            $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
        }

        $Kundennummer = '';
        if (isset($_POST['Kundennummer'])) {
            $Kundennummer = $_POST['Kundennummer'];
        }

        $success = $database->insertIntoBetreut($Mitarbeiter_ID, $Kundennummer);

        if ($success) {
            $message = "Betreung '{$Mitarbeiter_ID} {$Kundennummer} '  erfolgreich gebucht!'";
        } else {
            $message = "Betreung kann nicht gebucht werden. Errorcode: '{$Mitarbeiter_ID} {$Kundennummer}'!";
        }
        echo $message;

    }

    public function deleteBetreut(){
        $database = new DatabaseHelper();

        $Mitarbeiter_ID = '';
        if (isset($_POST['Mitarbeiter_ID'])) {
            $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
        }

        $Kundennummer = '';
        if (isset($_POST['Kundennummer'])) {
            $Kundennummer = $_POST['Kundennummer'];
        }

        $error_code = $database->deleteBetreut_($Mitarbeiter_ID, $Kundennummer);


        if ($error_code == 0){
            $message = "Betreung with ID: '{$Mitarbeiter_ID} {$Kundennummer}' successfully deleted!'";
        }
        else{
            $message = "Error can't delete Betreuung with ID: '{$Mitarbeiter_ID} {$Kundennummer}'. Errorcode: {$error_code}";
        }
        echo $message;
    }
}



$betreut = new Betreut();

if (isset($_POST['submitForm_6'])) {
    $betreut->addBetreut();
}

if (isset($_POST['submitDelete_6'])) {
    $betreut->deleteBetreut();
}


?>

<br>
<a href="index.php">
    go back
</a>
