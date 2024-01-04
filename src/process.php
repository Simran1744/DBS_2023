<?php

require_once('DatabaseHelper.php');


$database = new DatabaseHelper();

$Kundennummer = '';
if(isset($_POST['Kundennummer'])) {
    $Kundennummer = $_POST['Kundennummer'];
}



    // Perform basic validation (you can add more validation as needed)

    // Call the stored procedure
    $output = $database->GetMembershipDetails($Kundennummer);

    echo "Membership Details:<br>";
    echo "Membership Number: {$output['MITGLIEDSCHAFTSNUMMER']}<br>";
    echo "Membership Level: {$output['MITGLIEDSCHAFTS_STUFE']}<br>";
    echo "Cost: {$output['MONATSKOSTEN']}<br>";
    echo "Validity: {$output['GUELTIGKEIT']}<br>";

