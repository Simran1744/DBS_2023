<?php
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variable id from POST request
$Studio_ID = '';
if(isset($_POST['Studio_ID'])){
    $Studio_ID = $_POST['Studio_ID'];
}

// Delete method
$error_code = $database->deleteFitnessstudio($Studio_ID);

// Check result
if ($error_code == 0){
    echo "Fitnessstudio with ID: '{$Studio_ID}' successfully deleted!'";
}
else{
    echo "Error can't delete Fitnessstudio with ID: '{$Studio_ID}'. Errorcode: {$error_code}";
}
?>

<!-- link back to index page-->
<br>
<a href="index.php">
    go back
</a>