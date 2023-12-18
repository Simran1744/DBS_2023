<?php

require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variable id from POST request
$Studio_ID = '';
if(isset($_POST['Studio_ID'])){
    $Studio_ID = $_POST['Studio_ID'];
}

$New_Studio_ID = '';
if(isset($_POST['Studio_IDs'])){
    $New_Studio_ID = $_POST['Studio_IDs'];
}
$F_Name = '';
if(isset($_POST['F_Name'])){
    $F_Name = $_POST['F_Name'];
}

$Ort = '';
if(isset($_POST['Ort'])){
    $Ort = $_POST['Ort'];
}

$Platz = '';
if(isset($_POST['Platz'])){
    $Platz = $_POST['Platz'];
}

$Strasse = '';
if(isset($_POST['Strasse'])){
    $Strasse = $_POST['Strasse'];
}

// Update method
$success = $database->updateFitnessstudio($Studio_ID, $New_Studio_ID, $F_Name, $Ort, $Platz, $Strasse);

// Check result
if ($success){
    $message =  "Fitnessstudio with ID: '{$Studio_ID}' successfully updated!'";
}
else{
    $message = "Error can't update Fitnessstudio with ID: '{$Studio_ID}'";
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




