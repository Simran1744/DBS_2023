<?php

require_once('DatabaseHelper.php');


$database = new DatabaseHelper();

$customerID = '';
if(isset($_POST['customerID'])) {
    $customerID = $_POST['customerID'];
}



    // Perform basic validation (you can add more validation as needed)

    // Call the stored procedure
    $output = $database->getMembershipDetails($customerID);

    echo "Membership Details:<br>";
    echo "Membership Number: {$output['Mitgliedschaftsnummer']}<br>";
    echo "Membership Level: {$output['Mitgliedschafts_Stufe']}<br>";
    echo "Monthly Cost: {$output['Monatskosten']}<br>";
    echo "Validity: {$output['Gueltigkeit']}<br>";
