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
    $New_Mitarbeiter_ID = $_POST['Mitarbeiter_IDs'];
}
$Geschlecht = '';
if(isset($_POST['Geschlecht'])){
    $Geschlecht = $_POST['Geschlecht'];
}

$Sprachkenntnisse = '';
if(isset($_POST['Sprachkenntnisse'])){
    $Sprachkenntnisse = $_POST['Sprachkenntnisse'];
}

// Update method
$success = $database->updatePersonalTrainer($Mitarbeiter_ID, $New_Mitarbeiter_ID, $Geschlecht, $Sprachkenntnisse);

// Check result
if ($success){
    $message =  "Personal Trainer with ID: '{$Mitarbeiter_ID}' successfully updated!'";
}
else{
    $message = "Error can't update Personal Trainer with ID: '{$Mitarbeiter_ID}'";
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
