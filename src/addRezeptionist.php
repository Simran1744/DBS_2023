<?php
require_once('DatabaseHelper.php');



//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variables from POST request
$Mitarbeiter_ID = '';
if(isset($_POST['Mitarbeiter_ID'])){
    $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
}


$Arbeitszeiten = '';
if(isset($_POST['Arbeitszeiten'])){
    $Arbeitszeiten = $_POST['Arbeitszeiten'];
}

$Sprachkenntnisse = '';
if(isset($_POST['Sprachkenntnisse'])){
    $Sprachkenntnisse = $_POST['Sprachkenntnisse'];
}



// Insert method
$success = $database->insertIntoRezeptionist($Mitarbeiter_ID,$Arbeitszeiten, $Sprachkenntnisse);

// Check result
if ($success){
    $message = "Rezeptionist '{$Mitarbeiter_ID}' successfully added!'";
}
else{
    $message =  "Error can't insert Rezeptionist '{$Mitarbeiter_ID}'!";
}


?>

<div id="message">
    <?php echo $message; ?>
</div>

<script>
    setTimeout(function () {
        window.location.href = 'index.php';
    }, 1500);
</script>
-->
