<?php

require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variables from POST request
$Mitarbeiter_ID = '';
if(isset($_POST['Mitarbeiter_ID'])){
    $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
}

$Studio_ID = '';
if(isset($_POST['Studio_ID'])){
    $Studio_ID = $_POST['Studio_ID'];
}

$Vorname = '';
if(isset($_POST['Vorname'])){
    $Vorname = $_POST['Vorname'];
}

$Nachname = '';
if(isset($_POST['Nachname'])){
    $Nachname = $_POST['Nachname'];
}

// Insert method
$success = $database->insertIntoMitarbeiter($Mitarbeiter_ID, $Studio_ID, $Vorname, $Nachname);

// Check result
if ($success){
    $message = "Mitarbeiter '{$Mitarbeiter_ID} {$Nachname}' successfully added!'";
}
else{
    $message =  "Error can't insert Mitarbeiter '{$Mitarbeiter_ID} {$Nachname}'!";
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

