<?php
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variable id from POST request
$Mitarbeiter_ID = '';
if(isset($_POST['Mitarbeiter_ID'])){
    $Mitarbeiter_ID = $_POST['Mitarbeiter_ID'];
}

// Delete method
$error_code = $database->deleteMitarbeiter($Mitarbeiter_ID);

// Check result

if ($error_code == 0){
    $message = "Mitarbeiter with ID: '{$Mitarbeiter_ID}' successfully deleted!'";
}
else{
    $message = "Error can't delete Mitarbeiter with ID: '{$Mitarbeiter_ID}'. Errorcode: {$error_code}";
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
