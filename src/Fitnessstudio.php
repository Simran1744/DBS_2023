<?php

require_once('DatabaseHelper.php');

class Fitnessstudio
{
   public function addFitnessstudio(){
       $database = new DatabaseHelper();

       $Studio_ID = '';
       if(isset($_POST['Studio_ID'])){
           $Studio_ID = $_POST['Studio_ID'];
       }

       $F_Name = '';
       if(isset($_POST['F_Name'])){
           $F_Name = $_POST['F_Name'];
       }

       $Plz = '';
       if(isset($_POST['Plz'])){
           $Plz = $_POST['Plz'];
       }

       $Strasse = '';
       if(isset($_POST['Strasse'])){
           $Strasse = $_POST['Strasse'];
       }

       $success = $database->insertIntoFitnessstudio($Studio_ID, $F_Name, $Plz, $Strasse);

       if ($success){
           $message =  "Fitnessstudio '{$Studio_ID} {$F_Name}' successfully added!'";
       }
       else{
           $message =  "Error can't insert Fitnessstudio '{$Studio_ID} {$F_Name}'!";
       }
       echo $message;

   }

   public function deleteFitnessstudio(){

       $database = new DatabaseHelper();

       $Studio_ID = '';
       if(isset($_POST['Studio_ID'])){
           $Studio_ID = $_POST['Studio_ID'];
       }

       $error_code = $database->deleteFitnessstudio($Studio_ID);


       if ($error_code == 0){
           $message = "Fitnessstudio with ID: '{$Studio_ID}' successfully deleted!'";
       }
       else{
           $message = "Error can't delete Fitnessstudio with ID: '{$Studio_ID}'. Errorcode: {$error_code}";
       }

       echo $message;

   }
   public function updateFitnessstudio_(){

           $column = $_POST['column'];
           $value = $_POST['value'];
           $rowId = $_POST['rowId'];

           $database = new DatabaseHelper();
           $database->updateFitnessstudio($column, $value, $rowId);
           echo "Update successful";
           exit;

   }



}

$fit = new Fitnessstudio();

if (isset($_POST['addButton1'])) {
    $fit->addFitnessstudio();
}
if (isset($_POST['deleteButton1'])) {
    $fit->deleteFitnessstudio();
}

if (isset($_POST['action'])) {
    $fit->updateFitnessstudio_();
}


header("Location: index.php");

?>



<!--
<script>
    setTimeout(function () {
        window.location.href = 'index.php';
    }, 20000);
</script>
-->










