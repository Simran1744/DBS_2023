
<?php

// Include DatabaseHelper.php file
require_once('DatabaseHelper.php');

// Instantiate DatabaseHelper class
$database = new DatabaseHelper();

// Get parameter 'person_id', 'surname' and 'name' from GET Request
// Btw. you can see the parameters in the URL if they are set

$Studio_ID = '';
if (isset($_GET['Studio_ID'])) {
    $Studio_ID  = $_GET['Studio_ID'];
}

$F_Name = '';
if (isset($_GET['F_Name'])) {
    $F_Name = $_GET['F_Name'];
}

$Ort = '';
if (isset($_GET['Ort'])) {
    $Ort = $_GET['Ort'];
}

$Platz = '';
if (isset($_GET['Platz'])) {
    $Platz = $_GET['Platz'];
}


$Strasse = '';
if (isset($_GET['Strasse'])) {
    $Strasse = $_GET['Strasse'];
}


//Fetch data from database
$studio_array = $database->selectAllFitnessstudio($Studio_ID, $F_Name, $Ort, $Platz, $Strasse);
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous" >


    <title>My dbs project title</title>
</head>

<body>
<br>
<h1 class="ml-4">Fitnessstudio Management</h1>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>


<!-- Add Person -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        let editedCell = null;

        $('#data-table').on('dblclick', 'td[contenteditable="true"]', function () {

            editedCell = $(this);
            let currentValue = editedCell.text();
            $(this).html('<input id="html_id1" type="text" class="form-control edit-field"  value="' + currentValue + '" >');
            $('#html_id1').focus().select();
        });

        $('#data-table').on('blur', 'input.edit-field', function () {
            console.log($(this))
            let newValue = $(this).val();
            let column = $(this).closest('td').index();
            let rowId = $(this).closest('tr').find('td:first').text();
            editedCell.text(newValue);

            // Use AJAX to update the value in the database
            $.ajax({
                url: 'Fitnessstudio.php',
                method: 'POST',
                data: {
                    action: 'updateFitnessstudio_',
                    column: column,
                    value: newValue,
                    rowId: rowId
                },
                success: function (response) {
                    console.log(response);
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
    });
</script>



<!-- Search result -->
<div class="container ml-2" >
    <div class="row">
        <div class="col-md-6">
            <h2>Fitnessstudio Table:</h2>
            <div class="table-container table-responsive" >
                <table class="table table-bordered table-hover" id="data-table">
                    <thead  class="thead-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Ort</th>
                            <th scope="col">Platz</th>
                            <th scope="col">Strasse</th>
                        </tr>
                    </thead>
                    <style>
                        .table-container{
                            background-color: mintcream;
                            max-height: 500px;
                            overflow-y: auto;
                        }
                    </style>
                    <tbody>
                        <?php foreach ($studio_array as $Fitnessstudio) : ?>
                            <tr>
                                <td contenteditable="true"><?php echo $Fitnessstudio['STUDIO_ID']; ?> </td>
                                <td contenteditable="true"><?php echo $Fitnessstudio['F_NAME']; ?>  </td>
                                <td contenteditable="true"><?php echo $Fitnessstudio['ORT']; ?>  </td>
                                <td contenteditable="true"><?php echo $Fitnessstudio['PLATZ']; ?>  </td>
                                <td contenteditable="true"><?php echo $Fitnessstudio['STRASSE']; ?>  </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-4">
            <h2>Input: </h2>
            <form method="post" action="Fitnessstudio.php" class="row g-3">

                <div class=col-md-6">
                    <label for="new_ID" class="form-label">ID:</label>
                    <input id="new_ID" name="Studio_ID" type="number" min="1" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="new_F_Name" class="form-label">Name:</label>
                    <input id="new_F_Name" name="F_Name" type="text" maxlength="20" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="new_Ort" class="form-label">Ort:</label>
                    <input id="new_Ort" name="Ort" type="text" maxlength="20" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="new_Platz" class="form-label">Platz:</label>
                    <input id="new_Platz" name="Platz" type="number" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="new_Strasse" class="form-label">Strasse:</label>
                    <input id="new_Strasse" name="Strasse" type="text" maxlength="20" class="form-control">
                </div>


                <div class="row mt-3">
                    <div class="col-md-4">
                        <button type="submit" name="addButton1"  class="btn btn-primary">
                            Add Fitnessstudio
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="deleteButton1" class="btn btn-danger">
                            Delete Fitnessstudio
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-2">
            <h2>Search:</h2>
            <form method="get" class="row g-3">
                <div class=col-md-6">
                    <label for="Studio_ID" class="form-label">ID:</label>
                    <input id="Studio_ID" name="Studio_ID" type="number" value='<?php echo $Studio_ID; ?>' min="1" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="F_Name" class="form-label">Name:</label>
                    <input id="F_Name" name="F_Name" type="text" value='<?php echo $F_Name; ?>'
                           maxlength="20" class="form-control">
                </div class=col-md-6">

                <div class=col-md-6">
                    <label for="Ort" class="form-label">Ort:</label>
                    <input id="Ort" name="Ort" type="text" value='<?php echo $Ort; ?>'
                           maxlength="20" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="Platz" class="form-label">Platz:</label>
                    <input id="Platz" name="Platz" type="number" value='<?php echo $Platz; ?>'
                           maxlength="20" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="Strasse" class="form-label">Strasse:</label>
                    <input id="Strasse" name="Strasse" type="text" value='<?php echo $Strasse; ?>'
                           maxlength="20" class="form-control">
                </div>
                <style>
                    .custom-button {
                        width: 118.033px;
                        height: 62px;
                        font-weight: 400; /* Set your desired font size */
                        padding: 0.375rem 0.75rem;
                    }
                </style>
                <div class="mt-3">
                    <button id='submit' type='submit' class="btn btn-info custom-button">
                        Search
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>

<!-- Search form -->





<!-- Delete Person
<h2>Delete Fitnessstudio: </h2>
<form method="post" action="delPerson.php">
    <div>
        <label for="Studio_ID">ID:</label>
        <input id="Studio_ID" name="Studio_ID" type="number" min="1">
    </div>
    <br>
    <div>
        <button type="submit">
            Delete Fitnessstudio
        </button>
    </div>
</form>
<br>
<hr>
-->

<!-- Update Person
<h2>Update Fitnessstudio: </h2>
<form method="post" action="Fitnessstudio.php">
    <div>
        <label for="Studio_IDs">ID:</label>
        <input id="Studio_IDs" name="Studio_ID" type="number" min="1">
    </div>
    <br>
    <h3>Update Values: </h3>
    <div>
        <label for="new_Studio_IDs">ID:</label>
        <input id="new_Studio_IDs" name ="Studio_IDs" type="number" min="1">
    </div>
    <br>
    <div>
        <label for="new_F_Names">Name:</label>
        <input id="new_F_Names" name="F_Name" type="text" maxlength="20">
    </div>
    <br>

    <div>
        <label for="new_Orts">Ort:</label>
        <input id="new_Orts" name="Ort" type="text" maxlength="20">
    </div>
    <br>

    <div>
        <label for="new_Platzs">Platz:</label>
        <input id="new_Platzs" name="Platz" type="number">
    </div>
    <br>

    <div>
        <label for="new_Strasses">Strasse:</label>
        <input id="new_Strasses" name="Strasse" type="text" maxlength="20">
    </div>
    <br>
    <div>
        <button id ='update' type='submit'>
           Update
        </button>
    </div>
</form>
<br>
<hr>
-->




<h2>Add Mitarbeiter: </h2>
<form method="post" action="addMitarbeiter.php">

    <div>
        <label for="new_ID">ID:</label>
        <input id="new_ID" name="Mitarbeiter_ID" type="number">
    </div>
    <br>

    <div>
        <label for="new_F_ID">Studio-ID:</label>
        <input id="new_F_ID" name="Studio_ID" type="number" min="1">
    </div>
    <br>

    <div>
        <label for="new_Vorname">Vorname:</label>
        <input id="new_Vorname" name="Vorname" type="text" maxlength="20">
    </div>
    <br>

    <div>
        <label for="new_Nachname">Nachname:</label>
        <input id="new_Nachname" name="Nachname" type="text" maxlength="20">
    </div>
    <br>

    <!-- Submit button -->
    <div>
        <button type="submit">
            Add Mitarbeiter
        </button>
    </div>
</form>
<br>
<hr>


<!-- Delete Person -->
<h2>Delete Mitarbeiter: </h2>
<form method="post" action="delMitarbeiter.php">
    <div>
        <label for="newM_ID">ID:</label>
        <input id="newM_ID" name="Mitarbeiter_ID" type="number" min="1">
    </div>
    <br>

    <!-- Submit button -->
    <div>
        <button type="submit">
            Delete Mitarbeiter
        </button>
    </div>
</form>
<br>
<hr>



<h2>Update Mitarbeiter: </h2>
<form method="post" action="updateMitarbeiter.php">
    <!-- ID textbox -->
    <div>
        <label for="Mitarbeiter_IDs">ID:</label>
        <input id="Mitarbeiter_IDs" name="Mitarbeiter_ID" type="number">
    </div>
    <br>
    <h3>Update Values: </h3>
    <!-- Name textbox -->
    <div>
        <label for="new_Mitarbeiter_IDs">ID:</label>
        <input id="new_Mitarbeiter_IDs" name ="Mitarbeiter_IDs" type="number">
    </div>
    <br>
    <div>
        <label for="new_Studio_IDs_Names">Studio-ID:</label>
        <input id="new_Studio_IDs_Names" name="Studio_ID"  type="number">
    </div>
    <br>

    <!-- Surname textbox -->
    <div>
        <label for="new_Vorname">Vorname:</label>
        <input id="new_Nachname" name="Vorname" type="text" maxlength="20">
    </div>
    <br>

    <div>
        <label for="new_Nachname">Nachname:</label>
        <input id="new_Nachname" name="Nachname" type="text" maxlength="20">
    </div>
    <br>

    <div>
        <button id ='update' type='submit'>
            Update
        </button>
    </div>
</form>
<br>
<hr>

<?php
$Mitarbeiter_ID = '';
if (isset($_GET['Mitarbeiter_ID'])) {
    $Mitarbeiter_ID  = $_GET['Mitarbeiter_ID'];
}

$Studio_ID= '';
if (isset($_GET['Studio_ID'])) {
    $Studio_ID = $_GET['Studio_ID'];
}

$Vorname = '';
if (isset($_GET['Vorname'])) {
    $Vorname = $_GET['Vorname'];
}

$Nachname = '';
if (isset($_GET['Nachname'])) {
    $Nachname = $_GET['Nachname'];
}


//Fetch data from database
$studio_array = $database->selectAllMitarbeiter($Mitarbeiter_ID, $Studio_ID, $Vorname, $Nachname);
?>

<h2>Mitarbeiter Search:</h2>
<form method="get">
    <!-- ID textbox:-->
    <div>
        <label for="Mitarbeiter_ID">ID:</label>
        <input id="Mitarbeiter_ID" name="Mitarbeiter_ID" type="number" value='<?php echo $Mitarbeiter_ID; ?>'>
    </div>
    <br>

    <div>
        <label for="V_name">Vorname:</label>
        <input id="V_name" name="Vorname" type ="text" class="form-control input-md" value='<?php echo $Vorname; ?>'
               maxlength="20">
    </div>
    <br>

    <div>
        <label for="N_name">Nachname:</label>
        <input id="N_name" name="Nachname" type="text" class="form-control input-md" value='<?php echo $F_Name; ?>'
               maxlength="20">
    </div>
    <br>

    <!-- Submit button -->
    <div>
        <button id='submit_2' type='submit'>
            Search
        </button>
    </div>
</form>
<br>
<hr>

<!-- Search result -->
<h2>Mitarbeiter Search Result:</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Studio-ID</th>
        <th>Vorname</th>
        <th>Nachname</th>

    </tr>
    <?php foreach ($studio_array as $Mitarbeiter) : ?>
        <tr>
            <td><?php echo $Mitarbeiter['MITARBEITER_ID']; ?>  </td>
            <td><?php echo $Mitarbeiter['STUDIO_ID']; ?>  </td>
            <td><?php echo $Mitarbeiter['VORNAME']; ?>  </td>
            <td><?php echo $Mitarbeiter['NACHNAME']; ?>  </td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<hr>

<h2>Add Personal Trainer: </h2>
<form method="post" action="addPersonalTrainer.php">

    <div>
        <label for="P_ID">ID:</label>
        <input id="P_ID" name="Mitarbeiter_ID" type="number">
    </div>
    <br>

    <div>
        <label for="Geschlecht">Geschlecht (Male/Female):</label>
        <input id="Geschlecht" name="Geschlecht" type="text" maxlength="20">
    </div>
    <br>

    <div>
        <label for="Sprachkenntnisse">Sprachkenntnisse:</label>
        <input id="Sprachkenntnisse" name="Sprachkenntnisse" type="text" maxlength="20">
    </div>
    <br>

    <!-- Submit button -->
    <div>
        <button type="submit">
            Add Personal Trainer
        </button>
    </div>
</form>
<br>
<hr>

<h2>Update Personal Trainer: </h2>
<form method="post" action="updatePersonalTrainer.php">
    <div>
        <label for="Personal_IDs">ID:</label>
        <input id="Personal_IDs" name="Mitarbeiter_ID" type="number">
    </div>
    <br>
    <h3>Update Values: </h3>
    <div>
        <label for="new_Personal_IDs">ID:</label>
        <input id="new_Personal_IDs" name ="Mitarbeiter_IDs" type="number">
    </div>
    <br>
    <div>
        <label for="Personal_G">Geschlecht:</label>
        <input id="Personal_G" name="Geschlecht"  type="text" maxlength="20">
    </div>
    <br>

    <div>
        <label for="Personal_S">Sprachkenntnisse:</label>
        <input id="Personal_S" name="Sprachkenntnisse" type="text" maxlength="20">
    </div>
    <br>

    <div>
        <button id ='update_3' type='submit'>
            Update
        </button>
    </div>
</form>
<br>
<hr>

<?php
$Mitarbeiter_ID = '';
if (isset($_GET['Mitarbeiter_ID'])) {
$Mitarbeiter_ID  = $_GET['Mitarbeiter_ID'];
}

$Geschlecht= '';
if (isset($_GET['Geschlecht'])) {
$Geschlecht = $_GET['Geschlecht'];
}

$Sprachkenntnisse= '';
if (isset($_GET['Sprachkenntnisse'])) {
    $Sprachkenntnisse = $_GET['Sprachkenntnisse'];
}


//Fetch data from database
$studio_array = $database->selectAllPersonalTrainer($Mitarbeiter_ID, $Geschlecht, $Sprachkenntnisse);
?>

<h2>Personal Trainer Search:</h2>
<form method="get">
    <!-- ID textbox:-->
    <div>
        <label for="Mitarbeiter_ID">ID:</label>
        <input id="Mitarbeiter_ID" name="Mitarbeiter_ID" type="number" value='<?php echo $Mitarbeiter_ID; ?>'>
    </div>
    <br>

    <div>
        <label for="P_G">Geschlecht:</label>
        <input id="P_G" name="Geschlecht" type ="text" class="form-control input-md" value='<?php echo $Geschlecht; ?>'
               maxlength="20">
    </div>
    <br>

    <div>
        <label for="P_S">Sprachkenntnisse:</label>
        <input id="P_S" name="Sprachkenntnisse" type="text" class="form-control input-md" value='<?php echo $Sprachkenntnisse; ?>'
               maxlength="20">
    </div>
    <br>

    <!-- Submit button -->
    <div>
        <button id='submit_4' type='submit'>
            Search
        </button>
    </div>
</form>
<br>
<hr>

<!-- Search result -->
<h2>Personal Trainer Search Result:</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Geschlecht</th>
        <th>Sprachkenntnisse</th>

    </tr>
    <?php foreach ($studio_array as $Personal_Trainer) : ?>
        <tr>
            <td><?php echo $Personal_Trainer['MITARBEITER_ID']; ?>  </td>
            <td><?php echo $Personal_Trainer['GESCHLECHT']; ?>  </td>
            <td><?php echo $Personal_Trainer['SPRACHKENNTNISSE']; ?>  </td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<hr>

<h2>Add Rezeptionist: </h2>
<form method="post" action="Rezeptionist.php">

    <div>
        <label for="R_ID">ID:</label>
        <input id="R_ID" name="Mitarbeiter_ID" type="number">
    </div>
    <br>

    <div>
        <label for="Arbeitszeiten">Arbeitszeiten:</label>
        <input id="Arbeitszeiten" name="Arbeitszeiten" type="text" maxlength="20">
    </div>
    <br>

    <div>
        <label for="Sprachkenntnisse_">Sprachkenntnisse:</label>
        <input id="Sprachkenntnisse_" name="Sprachkenntnisse" type="text" maxlength="20">
    </div>
    <br>

    <!-- Submit button -->
    <div>
        <button type="submit" name="submitForm">
            Add Rezeptionist
        </button>
    </div>
</form>
<br>
<hr>

<h2>Update Rezeptionist: </h2>
<form method="post" action="Rezeptionist.php">
    <div>
        <label for="Rez_IDs">ID:</label>
        <input id="Rez_IDs" name="Mitarbeiter_ID" type="number">
    </div>
    <br>
    <h3>Update Values: </h3>
    <div>
        <label for="new_Rez_IDs">ID:</label>
        <input id="new_Rez_IDs" name ="Mitarbeiter_IDs" type="number">
    </div>
    <br>
    <div>
        <label for="Rez_A">Arbeitszeiten:</label>
        <input id="Rez_A" name="Arbeitszeiten"  type="text" maxlength="20">
    </div>
    <br>

    <div>
        <label for="Rez_S">Sprachkenntnisse:</label>
        <input id="Rez_S" name="Sprachkenntnisse" type="text" maxlength="20">
    </div>
    <br>

    <div>
        <button id ='update_4' type='submit' name="submitUpdate">
            Update
        </button>
    </div>
</form>
<br>
<hr>

<?php
$Mitarbeiter_ID = '';
if (isset($_GET['Mitarbeiter_ID'])) {
    $Mitarbeiter_ID  = $_GET['Mitarbeiter_ID'];
}

$Arbeitszeiten= '';
if (isset($_GET['Arbeitszeiten'])) {
    $Arbeitszeiten = $_GET['Arbeitszeiten'];
}

$Sprachkenntnisse= '';
if (isset($_GET['Sprachkenntnisse'])) {
    $Sprachkenntnisse = $_GET['Sprachkenntnisse'];
}


//Fetch data from database
$studio_array = $database->selectAllRezeptionist($Mitarbeiter_ID, $Arbeitszeiten, $Sprachkenntnisse);
?>

<h2>Rezeptionist Search:</h2>
<form method="get">
    <div>
        <label for="Mitarbeiter_ID">ID:</label>
        <input id="Mitarbeiter_ID" name="Mitarbeiter_ID" type="number" value='<?php echo $Mitarbeiter_ID; ?>'>
    </div>
    <br>

    <div>
        <label for="R_A">Arbeitszeiten:</label>
        <input id="R_A" name="Arbeitszeiten" type ="text" class="form-control input-md" value='<?php echo $Arbeitszeiten; ?>'
               maxlength="20">
    </div>
    <br>

    <div>
        <label for="R_S">Sprachkenntnisse:</label>
        <input id="R_S" name="Sprachkenntnisse" type="text" class="form-control input-md" value='<?php echo $Sprachkenntnisse; ?>'
               maxlength="20">
    </div>
    <br>

    <div>
        <button id='submit_5' type='submit' >
            Search
        </button>
    </div>
</form>
<br>
<hr>

<!-- Search result -->
<h2>Rezeptionist Search Result:</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Arbeitszeiten</th>
        <th>Sprachkenntnisse</th>

    </tr>
    <?php foreach ($studio_array as $Rezeptionist) : ?>
        <tr>
            <td><?php echo $Rezeptionist['MITARBEITER_ID']; ?>  </td>
            <td><?php echo $Rezeptionist['ARBEITSZEITEN']; ?>  </td>
            <td><?php echo $Rezeptionist['SPRACHKENNTNISSE']; ?>  </td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<hr>


<h2>Add Kunde: </h2>
<form method="post" action="Kunde.php">

    <div>
        <label for="K_N">ID:</label>
        <input id="K_N" name="Kundennummer" type="number">
    </div>
    <br>

    <div>
        <label for="F_ID">Studio-ID:</label>
        <input id="F_ID" name="Studio_ID" type="number" min="1">
    </div>
    <br>

    <div>
        <label for="Vorname">Vorname:</label>
        <input id="Vorname" name="Vorname" type="text" maxlength="20">
    </div>
    <br>

    <div>
        <label for="Nachname">Nachname:</label>
        <input id="Nachname" name="Nachname" type="text" maxlength="20">
    </div>
    <br>

    <div>
        <label for="Geschlecht">Geschlecht:</label>
        <input id="Geschlecht" name="Geschlecht" type ="text"
               maxlength="20">
    </div>
    <br>

    <div>
        <button type="submit" name="submitForm_2">
            Add Kunde
        </button>
    </div>
</form>
<br>
<hr>


<h2>Delete Kunde: </h2>
<form method="post" action="Kunde.php">

    <div>
        <label for="Kundennummer_">ID:</label>
        <input id="Kundennummer_" name="Kundennummer" type="number">
    </div>
    <br>

    <!-- Submit button -->
    <div>
        <button type="submit" name="submitDelete">
            Delete Kunde
        </button>
    </div>
</form>
<br>
<hr>


<h2>Update Kunde: </h2>
<form method="post" action="Kunde.php">
    <div>
        <label for="Kundennummer__">ID:</label>
        <input id="Kundennummer__" name="Kundennummer" type="number">
    </div>
    <br>

    <h3>Update Values: </h3>
    <div>
        <label for="new_Kundennummer__">ID:</label>
        <input id="new_Kundennummer__" name ="Kundennummers" type="number">
    </div>
    <br>
    <div>
        <label for="Studio_ID">Studio-ID:</label>
        <input id="Studio_ID" name="Studio_ID"  type="number">
    </div>
    <br>

    <div>
        <label for="Vorname_">Vorname:</label>
        <input id="Vorname_" name="Vorname" type="text" maxlength="20">
    </div>
    <br>

    <div>
        <label for="Nachname_">Nachname:</label>
        <input id="Nachname_" name="Nachname" type="text" maxlength="20">
    </div>
    <br>

    <div>
        <label for="Geschlecht_">Geschlecht:</label>
        <input id="Geschlecht_" name="Geschlecht" type="text" maxlength="20">
    </div>
    <br>

    <div>
        <button id ='update_5' type='submit' name="submitUpdateK">
            Update
        </button>
    </div>
</form>
<br>
<hr>

<?php

    $Kundennummer = '';
    if (isset($_GET['Kundennummer'])) {
        $Kundennummer = $_GET['Kundennummer'];
    }

    $Studio_ID = '';
    if (isset($_GET['Studio_ID'])) {
        $Studio_ID = $_GET['Studio_ID'];
    }


    $Vorname = '';
    if (isset($_GET['Vorname'])) {
        $Vorname = $_GET['Vorname'];
    }

    $Nachname = '';
    if (isset($_GET['Nachname'])) {
        $Nachname = $_GET['Nachname'];
    }

    $Geschlecht = '';
    if (isset($_GET['Geschlecht'])) {
        $Geschlecht = $_GET['Geschlecht'];
    }

//Fetch data from database
$studio_array = $database->selectAllKunde($Kundennummer, $Studio_ID, $Vorname, $Nachname, $Geschlecht);

?>

<h2>Kunde Search:</h2>
<form method="get">
    <div>
        <label for="_Kundennummer">Kundennummer:</label>
        <input id="_Kundennummer" name="Kundennummer" type="number" value='<?php echo $Kundennummer; ?>'>
    </div>
    <br>

    <div>
        <label for="_Studio_ID">Studio-ID:</label>
        <input id="_Studio_ID" name="Studio_ID" type ="number" value='<?php echo $Studio_ID; ?>'>
    </div>
    <br>

    <div>
        <label for="_Vorname">Vorname:</label>
        <input id="_Vorname" name="Vorname" type="text" class="form-control input-md" value='<?php echo $Vorname; ?>'
               maxlength="20">
    </div>
    <br>

    <div>
        <label for="_Nachname">Nachname:</label>
        <input id="_Nachname" name="Nachname" type="text" class="form-control input-md" value='<?php echo $Nachname; ?>'
               maxlength="20">
    </div>
    <br>

    <div>
        <label for="_Geschlecht">Geschlecht:</label>
        <input id="_Geschlecht" name="Geschlecht" type="text" class="form-control input-md" value='<?php echo $Geschlecht; ?>'
               maxlength="20">
    </div>
    <br>

    <div>
        <button id='submit_7' type='submit'>
            Search
        </button>
    </div>
</form>
<br>
<hr>

<h2>Kunde Search Result:</h2>
<table>
    <tr>
        <th>Kundennummer</th>
        <th>Studio-ID</th>
        <th>Vorname</th>
        <th>Nachname</th>
        <th>Geschlecht</th>

    </tr>
    <?php foreach ($studio_array as $Kunde) : ?>
        <tr>
            <td><?php echo $Kunde['KUNDENNUMMER']; ?>  </td>
            <td><?php echo $Kunde['STUDIO_ID']; ?>  </td>
            <td><?php echo $Kunde['VORNAME']; ?>  </td>
            <td><?php echo $Kunde['NACHNAME']; ?>  </td>
            <td><?php echo $Kunde['GESCHLECHT']; ?>  </td>
        </tr>
    <?php endforeach; ?>
</table>
<br>
<hr>

<h2>Add Trainingseinheit: </h2>
<form method="post" action="Coacht.php">

    <div>
        <label for="M_IDS">Mitarbeiter-ID:</label>
        <input id="M_IDS" name="Mitarbeiter_ID" type="number">
    </div>
    <br>

    <div>
        <label for="K_IDS">Kundennummer:</label>
        <input id="K_IDS" name="Kundennummer" type="number">
    </div>
    <br>

    <div>
        <label for="Beginnzeit">Beginnzeit:</label>
        <input id="Beginnzeit" name="Beginnzeit" type="datetime-local">
    </div>
    <br>

    <div>
        <label for="Endzeit">Endzeit:</label>
        <input id="Endzeit" name="Endzeit" type="datetime-local">
    </div>
    <br>


    <div>
        <label for="Trainingsdatum">Trainingsdatum:</label>
        <input id="Trainingsdatum" name="Trainingsdatum" type="date">
    </div>
    <br>

    <div>
        <button type="submit" name="submitForm_5">
            Add Trainingseinheit
        </button>
    </div>
</form>
<br>
<hr>

<h2>Delete Trainingseinheit: </h2>
<form method="post" action="Coacht.php">

    <div>
        <label for="_Mitarbeiter-ID_">Mitarbeiter-ID:</label>
        <input id="_Mitarbeiter-ID_" name="Mitarbeiter_ID" type="number">
    </div>
    <br>


    <div>
        <label for="_Kundennummer_">Kundennummer:</label>
        <input id="_Kundennummer_" name="Kundennummer" type="number">
    </div>
    <br>

    <div>
        <label for="Beginnzeit_">Beginnzeit:</label>
        <input id="Beginnzeit_" name="Beginnzeit" type="datetime-local">
    </div>
    <br>

    <div>
        <label for="Endzeit_">Endzeit:</label>
        <input id="Endzeit_" name="Endzeit" type="datetime-local">
    </div>
    <br>


    <div>
        <button type="submit" name="submitDelete_">
            Delete Kunde
        </button>
    </div>
</form>
<br>
<hr>


<?php

$Mitarbeiter_ID = '';
if (isset($_GET['Mitarbeiter_ID'])) {
    $Mitarbeiter_ID = $_GET['Mitarbeiter_ID'];
}

$Kundennummer = '';
if (isset($_GET['Kundennummer'])) {
    $Kundennummer = $_GET['Kundennummer'];
}


$Beginnzeit = '';
if (isset($_GET['Beginnzeit'])) {
    $Beginnzeit  = $_GET['Beginnzeit'];
}

$Endzeit = '';
if (isset($_GET['Endzeit'])) {
    $Endzeit = $_GET['Endzeit'];
}

//Fetch data from database
$studio_array = $database->selectAllCoacht($Mitarbeiter_ID, $Kundennummer, $Beginnzeit, $Endzeit);
?>

<h2>Coacht Search:</h2>
<form method="get">

    <div>
        <label for="_Mitarbeiter-IDs_">Mitarbeiter-ID:</label>
        <input id="_Mitarbeiter-IDs_" name="Mitarbeiter_ID" type ="number" value='<?php echo $Mitarbeiter_ID; ?>'>
    </div>
    <br>

    <div>
        <label for="_Kundennummers">Kundennummer:</label>
        <input id="_Kundennummers" name="Kundennummer" type="number" value='<?php echo $Kundennummer; ?>'>
    </div>
    <br>

    <div>
        <label for="_Beginnzeit">Beginnezeit:</label>
        <input id="_Beginnzeit" name="Beginnzeit" type="datetime-local"  value='<?php echo $Beginnzeit; ?>'>
    </div>
    <br>

    <div>
        <label for="_Endzeit">Endzeit:</label>
        <input id="_Endzeit" name="Endzeit" type="datetime-local" value='<?php echo $Endzeit; ?>'>
    </div>
    <br>

    <div>
        <button id='submit_8' type='submit'>
            Search
        </button>
    </div>
</form>
<br>
<hr>

<h2>Trainingseinheit Search Result:</h2>
<table>
    <tr>
        <th>Mitarbeiter-ID</th>
        <th>Kundennummer</th>
        <th>Beginnzeit</th>
        <th>Endzeit</th>
        <th>Trainingsdatum</th>
    </tr>
    <?php foreach ($studio_array as $Coacht) : ?>
        <tr>
            <td><?php echo $Coacht['MITARBEITER_ID']; ?>  </td>
            <td><?php echo $Coacht['KUNDENNUMMER']; ?>  </td>
            <td><?php echo DateTime::createFromFormat('d-M-y H.i.s.u A', $Coacht['BEGINNZEIT'])->format('H:i:s'); ?>  </td>
            <td><?php echo DateTime::createFromFormat('d-M-y H.i.s.u A', $Coacht['ENDZEIT'])->format('H:i:s'); ?>  </td>
            <td><?php echo date('d-m-Y', strtotime($Coacht['TRAININGSDATUM'])); ?>  </td>
        </tr
    <?php endforeach; ?>
</table>
<br>
<hr>


<h2>Add Betreung: </h2>
<form method="post" action="Betreut.php">

    <div>
        <label for="M_IDS_">Mitarbeiter-ID:</label>
        <input id="M_IDS_" name="Mitarbeiter_ID" type="number">
    </div>
    <br>

    <div>
        <label for="K_IDS_">Kundennummer:</label>
        <input id="K_IDS_" name="Kundennummer" type="number">
    </div>
    <br>

    <div>
        <button type="submit" name="submitForm_6">
            Add Betreung
        </button>
    </div>

</form>
<br>
<hr>


<h2>Delete Betreung: </h2>
<form method="post" action="Betreut.php">

    <div>
        <label for="_M_IDS_">Mitarbeiter-ID:</label>
        <input id="_M_IDS_" name="Mitarbeiter_ID" type="number">
    </div>
    <br>

    <div>
        <label for="_K_IDS_">Kundennummer:</label>
        <input id="_K_IDS_" name="Kundennummer" type="number">
    </div>
    <br>

    <div>
        <button type="submit" name="submitDelete_6">
            Delete Betreung
        </button>
    </div>

</form>
<br>
<hr>

<?php

$Mitarbeiter_ID = '';
if (isset($_GET['Mitarbeiter_ID'])) {
    $Mitarbeiter_ID = $_GET['Mitarbeiter_ID'];
}

$Kundennummer = '';
if (isset($_GET['Kundennummer'])) {
    $Kundennummer = $_GET['Kundennummer'];
}

//Fetch data from database
$studio_array = $database->selectAllBetreut($Mitarbeiter_ID, $Kundennummer);
?>


<h2>Betreung Search:</h2>
<form method="get">

    <div>
        <label for="_Mitarbeiter-IDs__">Mitarbeiter-ID:</label>
        <input id="_Mitarbeiter-IDs__" name="Mitarbeiter_ID" type ="number" value='<?php echo $Mitarbeiter_ID; ?>'>
    </div>
    <br>

    <div>
        <label for="_Kundennummers_">Kundennummer:</label>
        <input id="_Kundennummers_" name="Kundennummer" type="number" value='<?php echo $Kundennummer; ?>'>
    </div>
    <br>


    <div>
        <button id='9' type='submit'>
            Search
        </button>
    </div>
</form>
<br>
<hr>

<h2>Betreung Search Result:</h2>

<table>
    <tr>
        <th>Mitarbeiter-ID</th>
        <th>Kundennummer</th>
    </tr>

    <?php foreach ($studio_array as $Betreut) : ?>
        <tr>
            <td><?php echo $Betreut['MITARBEITER_ID']; ?>  </td>
            <td><?php echo $Betreut['KUNDENNUMMER']; ?>  </td>
        </tr
    <?php endforeach; ?>
</table>
<br>
<hr>


<h2>Add Mitgliedschaft: </h2>
<form method="post" action="Mitgliedschaft.php">


    <div>
        <label for="KN">Kundennummer:</label>
        <input id="KN" name="Kundennummer" type="number">
    </div>
    <br>

    <div>
        <label for="MN">Mitgliedschaftsnummer:</label>
        <input id="MN" name="Mitgliedschaftsnummer" type="number">
    </div>
    <br>

    <div>
        <label for="MS">Mitgliedschafts-Stufe:</label>
        <input id="MS" name="Mitgliedschafts_Stufe" type="text">
    </div>
    <br>

    <div>
        <label for="MK">Monatskosten:</label>
        <input id="MK" name="Monatskosten" type="number">
    </div>
    <br>

    <div>
        <label for="G">Gültigkeit:</label>
        <input id="G" name="Gueltigkeit" type="text">
    </div>
    <br>

    <div>
        <label for="ED">Erstellungsdatum:</label>
        <input id="ED" name="Erstellungsdatum" type="date">
    </div>
    <br>

    <div>
        <button type="submit" name="submitForm_7">
            Add Mitgliedschaft
        </button>
    </div>

</form>
<br>
<hr>


<h2>Delete Mitgliedschaft: </h2>
<form method="post" action="Mitgliedschaft.php">


    <div>
        <label for="_K_IDS_">Kundennummer:</label>
        <input id="_K_IDS_" name="Kundennummer" type="number">
    </div>
    <br>

    <div>
        <label for="MN_">Mitgliedschaftsnummer:</label>
        <input id="MN_" name="Mitgliedschaftsnummer" type="number">
    </div>
    <br>

    <div>
        <button type="submit" name="submitDelete_7">
            Delete Mitgliedschaft
        </button>
    </div>

</form>
<br>
<hr>


<?php

$Kundennummer = '';
if (isset($_GET['Kundennummer'])) {
    $Kundennummer = $_GET['Kundennummer'];
}

$Mitgliedschaftsnummer = '';
if (isset($_GET['Mitgliedschaftsnummer'])) {
    $Mitgliedschaftsnummer = $_GET['Mitgliedschaftsnummer'];
}

$Mitgliedschafts_Stufe = '';
if (isset($_GET['Mitgliedschafts_Stufe'])) {
    $Mitgliedschafts_Stufe = $_GET['Mitgliedschafts_Stufe'];
}

$Monatskosten = '';
if (isset($_GET['Monatskosten'])) {
    $Monatskosten = $_GET['Monatskosten'];
}

$Gueltigkeit = '';
if (isset($_GET['Gueltigkeit'])) {
    $Gueltigkeit= $_GET['Gueltigkeit'];
}

$Erstellungsdatum = '';
if (isset($_GET['Erstellungsdatum'])) {
    $Erstellungsdatum = $_GET['Erstellungsdatum'];
}



//Fetch data from database
$studio_array = $database->selectAllMGs($Kundennummer, $Mitgliedschaftsnummer, $Mitgliedschafts_Stufe, $Monatskosten, $Gueltigkeit, $Erstellungsdatum);
?>


<h2>Mitgliedschaft Search:</h2>
<form method="get">

    <div>
        <label for="KNS">Kundennummer:</label>
        <input id="KNS" name="Kundennummer" type="number" value='<?php echo $Kundennummer; ?>'>
    </div>
    <br>
    <div>
        <label for="MNS">Mitgliedschaftsnummer:</label>
        <input id="MNS" name="Mitgliedschaftsnummer" type="number" value='<?php echo $Mitgliedschaftsnummer; ?>'>
    </div>
    <br>
    <div>
        <label for="MSS">Mitgliedschafts_Stufe:</label>
        <input id="MSS" name="Mitgliedschafts_Stufe" type="text" value='<?php echo $Mitgliedschaftsnummer; ?>'>
    </div>
    <br>
    <div>
        <label for="MKS">Monatskosten:</label>
        <input id="MKS" name="Monatskosten" type="number" value='<?php echo $Monatskosten; ?>'>
    </div>
    <br>
    <div>
        <label for="GKS">Gueltigkeit:</label>
        <input id="GKS" name="Gueltigkeit" type="text" value='<?php echo $Gueltigkeit; ?>'>
    </div>
    <br>
    <div>
        <label for="ESD">Erstellungsdatummer:</label>
        <input id="ESD" name="Erstellungsdatum" type="date" value='<?php echo $Erstellungsdatum; ?>'>
    </div>
    <br>

    <div>
        <button id='10' type='submit'>
            Search
        </button>
    </div>
</form>
<br>
<hr>

<h2>Mitgliedschaft Search Result:</h2>

<table>
    <tr>
        <th>Kundennummer</th>
        <th>Mitgliedschaftsnummer</th>
        <th>Mitgliedschafts_Stufe</th>
        <th>Monatskosten</th>
        <th>Gueltigkeit</th>
        <th>Erstellungsdatum</th>
    </tr>

    <?php foreach ($studio_array as $MG) : ?>
        <tr>
            <td><?php echo $MG['KUNDENNUMMER']; ?>  </td>
            <td><?php echo $MG['MITGLIEDSCHAFTSNUMMER']; ?>  </td>
            <td><?php echo $MG['MITGLIEDSCHAFTS_STUFE']; ?>  </td>
            <td><?php echo $MG['MONATSKOSTEN']; ?>  </td>
            <td><?php echo $MG['GUELTIGKEIT']; ?>  </td>
            <td><?php echo date('d-m-Y', strtotime($MG['ERSTELLUNGSDATUM'])); ?>  </td>
        </tr
    <?php endforeach; ?>
</table>
<br>
<hr>

<h2>Add Kontrolliert: </h2>
<form method="post" action="Kontrolliert.php">
    <div>
        <label for="_M_ID">Mitarbeiter-ID:</label>
        <input id="_M_ID" name="Mitarbeiter_ID" type="number">
    </div>
    <br>

    <div>
        <label for="KN_">Kundennummer:</label>
        <input id="KN_" name="Kundennummer" type="number">
    </div>
    <br>

    <div>
        <label for="MN_">Mitgliedschaftsnummer:</label>
        <input id="MN_" name="Mitgliedschaftsnummer" type="number">
    </div>
    <br>


    <div>
        <button type="submit" name="submitForm_8">
            Add Kontrolle
        </button>
    </div>

</form>
<br>
<hr>

<h2>Delete Kontrolle: </h2>
<form method="post" action="Kontrolliert.php">

    <div>
        <label for="M">Mitarbeiter-ID:</label>
        <input id="M" name="Mitarbeiter_ID" type="number">
    </div>
    <br>

    <div>
        <label for="KS">Kundennummer:</label>
        <input id="KS" name="Kundennummer" type="number">
    </div>
    <br>

    <div>
        <label for="_MN_">Mitgliedschaftsnummer:</label>
        <input id="_MN_" name="Mitgliedschaftsnummer" type="number">
    </div>
    <br>

    <div>
        <button type="submit" name="submitDelete_8">
            Delete Kontrolle
        </button>
    </div>

</form>
<br>
<hr>


<?php


$Mitarbeiter_ID = '';
if (isset($_GET['Mitarbeiter_ID'])) {
    $Mitarbeiter_ID = $_GET['Mitarbeiter_ID'];
}

$Kundennummer = '';
if (isset($_GET['Kundennummer'])) {
    $Kundennummer = $_GET['Kundennummer'];
}

$Mitgliedschaftsnummer = '';
if (isset($_GET['Mitgliedschaftsnummer'])) {
    $Mitgliedschaftsnummer = $_GET['Mitgliedschaftsnummer'];
}



//Fetch data from database
$studio_array = $database->selectAllKons($Mitarbeiter_ID, $Kundennummer, $Mitgliedschaftsnummer);
?>



<h2>Mitgliedschaft Search:</h2>
<form method="get">


    <div>
        <label for="MIDSS">Mitarbeiter-ID:</label>
        <input id="MIDSS" name="Mitarbeiter_ID" type="number" value='<?php echo $Mitarbeiter_ID; ?>'>
    </div>
    <br>

    <div>
        <label for="KNDS">Kundennummer:</label>
        <input id="KNDS" name="Kundennummer" type="number" value='<?php echo $Kundennummer; ?>'>
    </div>
    <br>

    <div>
        <label for="MGNS">Mitgliedschaftsnummer:</label>
        <input id="MGNS" name="Mitgliedschaftsnummer" type="number" value='<?php echo $Mitgliedschaftsnummer; ?>'>
    </div>
    <br>

    <div>
        <button id='11' type='submit'>
            Search
        </button>
    </div>
</form>
<br>
<hr>

<h2>Kontrolle Search Result:</h2>

<table>
    <tr>
        <th>Mitarbeiter-ID</th>
        <th>Kundennummer</th>
        <th>Mitgliedschaftsnummer</th>
    </tr>

    <?php foreach ($studio_array as $Kon) : ?>
        <tr>
            <td><?php echo $Kon['MITARBEITER_ID']; ?>  </td>
            <td><?php echo $Kon['KUNDENNUMMER']; ?>  </td>
            <td><?php echo $Kon['MITGLIEDSCHAFTSNUMMER']; ?>  </td>
        </tr
    <?php endforeach; ?>
</table>
<br>
<hr>




</body>
</html>