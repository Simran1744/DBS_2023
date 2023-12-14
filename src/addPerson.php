<?php
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variables from POST request
$Studio_ID = '';
if(isset($_POST['Studio_ID'])){
    $Studio_ID = $_POST['Studio_ID'];
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

// Insert method
$success = $database->insertIntoFitnessstudio($Studio_ID, $F_Name, $Ort, $Platz, $Strasse);

// Check result
if ($success){
    echo "Fitnessstudio '{$Studio_ID} {$F_Name}' successfully added!'";
}
else{
    echo "Error can't insert Fitnessstudio '{$Studio_ID} {$F_Name}'!";
}
?>

<!-- link back to index page-->
<br>
<a href="index.php">
    go back
</a>