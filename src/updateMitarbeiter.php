<?php

require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variable id from POST request
$Mitarbeiter_ID = '';
if(isset($_POST['Mitarbeiter_ID'])){
    $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
}

$New_Mitarbeiter_ID = '';
if(isset($_POST['Mitarbeiter_IDs'])){
    $New_Mitarbeiter_IDs = $_POST['Mitarbeiter_IDs'];
}
$Studio_ID= '';
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


// Update method
$success = $database->updateMitarbeiter($Mitarbeiter_ID, $New_Mitarbeiter_ID, $Studio_ID, $Vorname, $Nachname);

// Check result
if ($success){
    $message =  "Mitarbeiter with ID: '{$Mitarbeiter_ID}' successfully updated!'";
}
else{
    $message = "Error can't update Mitarbeiter with ID: '{$Mitarbeiter_ID}'";
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

