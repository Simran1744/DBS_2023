<?php

require_once('DatabaseHelper.php');

class MG_Stufe
{
    public function addMGs(){

        $database = new DatabaseHelper();

        $Stufe = '';
        if (isset($_POST['Stufe'])) {
            $Stufe = $_POST['Stufe'];
        }

        $Monatskosten = '';
        if (isset($_POST['Monatskosten'])) {
            $Monatskosten = $_POST['Monatskosten'];
        }


        $success = $database->insertIntoMGS($Stufe, $Monatskosten);

        if ($success) {
            $message = "Mitgliedschaft '{$Stufe} ' erstellt!'";
        } else {
            $message = "Mitgliedschaft kann nicht erstellt werden. Errorcode: '{$Stufe}'!";
        }
        echo $message;
    }



}

$mg = new MG_Stufe();

if (isset($_POST['addButton16'])) {
    $mg->addMGs();
}

header('Location: index.php#data-table-16');

?>
