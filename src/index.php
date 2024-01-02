
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

        $('.data-table').on('dblclick', 'td[contenteditable="true"]', function () {

            editedCell = $(this);
            let currentValue = editedCell.text();
            //if klausel für datum hinzufügen für type = "date"

            let inputType;
            if (editedCell.hasClass('timestamp-cell')) {
                inputType = 'datetime-local';
            } else if (editedCell.hasClass('date-cell')) {
                inputType = 'date';
            } else {
                inputType = 'text';
            }


            $(this).html(`<input id="html_id1" type="${inputType}" class="form-control edit-field"  value="` + currentValue + '" >');


            $('#html_id1').focus().select();

        });

        $('.data-table').on('blur', 'input.edit-field', function () {

            console.log($(this))
            let newValue = $(this).val();
            let column = $(this).closest('td').index();
            let rowId = $(this).closest('tr').find('td:first').text();
            let phpFile = editedCell.closest('.data-table').data('php-file');
            editedCell.text(newValue);


            // Use AJAX to update the value in the database
            $.ajax({
                url: phpFile,
                method: 'POST',
                data: {
                    action: 'update',
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



<div class="container ml-2" >
    <div class="row">
        <div class="col-md-7">
            <h2>Fitnessstudio Table:</h2>
            <div class="table-container table-responsive">
                <table class="table table-bordered table-hover data-table" data-php-file="Fitnessstudio.php" id="data-table-1">
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

        <div class="col-md-3">
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
                            Add
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="deleteButton1" class="btn btn-danger">
                            Delete
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


<div class="container ml-2" >
    <div class="row">
        <div class="col-md-7">
            <h2>Mitarbeiter Table:</h2>
            <div class="table-container table-responsive" >
                <table class="table table-bordered table-hover data-table" data-php-file="Mitarbeiter.php" id="data-table-2">
                    <thead  class="thead-dark">
                    <tr>
                        <th scope="col">Mitarbeiter_ID</th>
                        <th scope="col">Studio_ID</th>
                        <th scope="col">Vorname</th>
                        <th scope="col">Nachname</th>
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
                    <?php foreach ($studio_array as $Mitarbeiter) : ?>
                        <tr>
                            <td contenteditable="true"><?php echo $Mitarbeiter['MITARBEITER_ID']; ?> </td>
                            <td contenteditable="true"><?php echo $Mitarbeiter['STUDIO_ID']; ?>  </td>
                            <td contenteditable="true"><?php echo $Mitarbeiter['VORNAME']; ?>  </td>
                            <td contenteditable="true"><?php echo $Mitarbeiter['NACHNAME']; ?>  </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-3">
            <h2>Input: </h2>
            <form method="post" action="Mitarbeiter.php" class="row g-3">

                <div class=col-md-6">
                    <label for="new_ID" class="form-label">Mitarbeiter-ID:</label>
                    <input id="new_ID" name="Mitarbeiter_ID" type="number" class="form-control">
                </div>
                <br>

                <div class=col-md-6">
                    <label for="new_F_ID" class="form-label">Studio-ID:</label>
                    <input id="new_F_ID" name="Studio_ID" type="number" min="1" class="form-control">
                </div>
                <br>

                <div class=col-md-6">
                    <label for="new_Vorname" class="form-label">Vorname:</label>
                    <input id="new_Vorname" name="Vorname" type="text" maxlength="20" class="form-control">
                </div>
                <br>

                <div class=col-md-6">
                    <label for="new_Nachname" class="form-label">Nachname:</label>
                    <input id="new_Nachname" name="Nachname" type="text" maxlength="20" class="form-control">
                </div>
                <br>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <button type="submit" name="addButton2"  class="btn btn-primary">
                            Add
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="deleteButton2" class="btn btn-danger">
                            Delete
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-2">
            <h2>Search:</h2>
            <form method="get" class="row g-3">

                <div class=col-md-6">
                    <label for="Mitarbeiter_ID" class="form-label">Mitarbeiter-ID:</label>
                    <input id="Mitarbeiter_ID" name="Mitarbeiter_ID"  class="form-control input-md"  type="number" value='<?php echo $Mitarbeiter_ID; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="Studio_ID" class="form-label">Studio-ID:</label>
                    <input id="Studio_ID" name="Studio_ID" type="number" class="form-control input-md" value='<?php echo $Studio_ID; ?>'>
                </div>

                <div>
                    <label for="V_name" class="form-label">Vorname:</label>
                    <input id="V_name" name="Vorname" type ="text" class="form-control input-md" value='<?php echo $Vorname; ?>'
                           maxlength="20">
                </div>

                <div>
                    <label for="N_name" class="form-label">Nachname:</label>
                    <input id="N_name" name="Nachname" type="text" class="form-control input-md" value='<?php echo $Nachname; ?>'
                           maxlength="20">
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
                    <button id='submit2' type='submit' class="btn btn-info custom-button">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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


<div class="container ml-2" >
    <div class="row">
        <div class="col-md-7">
            <h2>Personal Trainer Table:</h2>
            <div class="table-container table-responsive" >
                <table class="table table-bordered table-hover data-table" data-php-file="PersonalTrainer.php" id="data-table-3">
                    <thead  class="thead-dark">
                    <tr>
                        <th scope="col">Mitarbeiter_ID</th>
                        <th scope="col">Geschlecht</th>
                        <th scope="col">Sprachkenntnisse</th>
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
                    <?php foreach ($studio_array as $PersonalTrainer) : ?>
                        <tr>
                            <td contenteditable="true"><?php echo $PersonalTrainer['MITARBEITER_ID']; ?> </td>
                            <td contenteditable="true"><?php echo $PersonalTrainer['GESCHLECHT']; ?>  </td>
                            <td contenteditable="true"><?php echo $PersonalTrainer['SPRACHKENNTNISSE']; ?>  </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-3">
            <h2>Input: </h2>
            <form method="post" action="PersonalTrainer.php" class="row g-3">

                <div class=col-md-6">
                    <label for="P_ID" class="form-label">Mitarbeiter-ID:</label>
                    <input id="P_ID" name="Mitarbeiter_ID" type="number" class="form-control">
                </div>


                <div class=col-md-6">
                    <label for="Geschlecht" class="form-label">Geschlecht:</label>
                    <input id="Geschlecht" name="Geschlecht" type="text" maxlength="20" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="Sprachkenntnisse" class="form-label">Sprachkenntnissse:</label>
                    <input id="Sprachkenntnisse" name="Sprachkenntnisse" type="text" maxlength="20" class="form-control">
                </div>

                <div class="mt-3">
                    <button type="submit" name="addButton3"  class="btn btn-primary custom-button">
                        Add
                    </button>
                </div>
            </form>
        </div>

        <div class="col-md-2">
            <h2>Search:</h2>
            <form method="get" class="row g-3">

                <div class=col-md-6">
                    <label for="Mitarbeiter_ID"  class="form-label">ID:</label>
                    <input id="Mitarbeiter_ID" name="Mitarbeiter_ID" type="number" class="form-control input-md" value='<?php echo $Mitarbeiter_ID; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="P_G"  class="form-label">Geschlecht:</label>
                    <input id="P_G" name="Geschlecht" type ="text" class="form-control input-md" value='<?php echo $Geschlecht; ?>'
                           maxlength="20">
                </div>

                <div class=col-md-6">
                    <label for="P_S"  class="form-label">Sprachkenntnisse:</label>
                    <input id="P_S" name="Sprachkenntnisse" type="text" class="form-control input-md" value='<?php echo $Sprachkenntnisse; ?>'
                           maxlength="20">
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
                    <button id='submit3' type='submit' class="btn btn-info custom-button">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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



<div class="container ml-2" >
    <div class="row">
        <div class="col-md-7">
            <h2>Rezeptionist Table:</h2>
            <div class="table-container table-responsive" >
                <table class="table table-bordered table-hover data-table" data-php-file="Rezeptionist.php" id="data-table-4">
                    <thead  class="thead-dark">
                    <tr>
                        <th scope="col">Mitarbeiter_ID</th>
                        <th scope="col">Arbeitszeiten</th>
                        <th scope="col">Sprachkenntnisse</th>
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
                    <?php foreach ($studio_array as $Rezeptionist) : ?>
                        <tr>
                            <td contenteditable="true"><?php echo $Rezeptionist['MITARBEITER_ID']; ?> </td>
                            <td contenteditable="true"><?php echo $Rezeptionist['ARBEITSZEITEN']; ?>  </td>
                            <td contenteditable="true"><?php echo $Rezeptionist['SPRACHKENNTNISSE']; ?>  </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-3">
            <h2>Input: </h2>
            <form method="post" action="Rezeptionist.php" class="row g-3">

                <div class=col-md-6">
                    <label for="P_ID" class="form-label">Mitarbeiter-ID:</label>
                    <input id="P_ID" name="Mitarbeiter_ID" type="number" class="form-control">
                </div>


                <div class=col-md-6">
                    <label for="Arbeitszeiten" class="form-label">Arbeitszeiten:</label>
                    <input id="Arbeitszeiten" name="Arbeitszeiten" type="text" maxlength="20" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="Sprachkenntnisse_" class="form-label">Sprachkenntnissse:</label>
                    <input id="Sprachkenntnisse_" name="Sprachkenntnisse" type="text" maxlength="20" class="form-control">
                </div>

                <div class="mt-3">
                        <button type="submit" name="addButton4"  class="btn btn-primary custom-button">
                            Add
                        </button>
                </div>
            </form>
        </div>

        <div class="col-md-2">
            <h2>Search:</h2>
            <form method="get" class="row g-3">

                <div class=col-md-6">
                    <label for="Mitarbeiter_ID"  class="form-label">ID:</label>
                    <input id="Mitarbeiter_ID" name="Mitarbeiter_ID" type="number" class="form-control input-md" value='<?php echo $Mitarbeiter_ID; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="R_A"  class="form-label">Arbeitszeiten:</label>
                    <input id="R_A" name="Arbeitszeiten" type ="text" class="form-control input-md" value='<?php echo $Arbeitszeiten; ?>'
                           maxlength="20">
                </div>

                <div class=col-md-6">
                    <label for="R_S"  class="form-label">Sprachkenntnisse:</label>
                    <input id="R_S" name="Sprachkenntnisse" type="text" class="form-control input-md" value='<?php echo $Sprachkenntnisse; ?>'
                           maxlength="20">
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
                    <button id='submit4' type='submit' class="btn btn-info custom-button">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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


<div class="container ml-2" >
    <div class="row">
        <div class="col-md-7">
            <h2>Kunde Table:</h2>
            <div class="table-container table-responsive" >
                <table class="table table-bordered table-hover data-table" data-php-file="Kunde.php" id="data-table-5">
                    <thead  class="thead-dark">
                    <tr>
                        <th scope="col">Kundennummer</th>
                        <th scope="col">Studio_ID</th>
                        <th scope="col">Vorname</th>
                        <th scope="col">Nachname</th>
                        <th scope="col">Geschlecht</th>
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
                    <?php foreach ($studio_array as $Kunde) : ?>
                        <tr>
                            <td contenteditable="true"><?php echo $Kunde['KUNDENNUMMER']; ?> </td>
                            <td contenteditable="true"><?php echo $Kunde['STUDIO_ID']; ?>  </td>
                            <td contenteditable="true"><?php echo $Kunde['VORNAME']; ?>  </td>
                            <td contenteditable="true"><?php echo $Kunde['NACHNAME']; ?>  </td>
                            <td contenteditable="true"><?php echo $Kunde['GESCHLECHT']; ?>  </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-3">
            <h2>Input: </h2>
            <form method="post" action="Kunde.php" class="row g-3">

                <div class=col-md-6">
                    <label for="K_N"  class="form-label">ID:</label>
                    <input id="K_N" name="Kundennummer" type="number"  class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="F_ID"  class="form-label">Studio-ID:</label>
                    <input id="F_ID" name="Studio_ID" type="number" min="1"  class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="Vorname"  class="form-label">Vorname:</label>
                    <input id="Vorname" name="Vorname" type="text" maxlength="20"  class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="Nachname"  class="form-label">Nachname:</label>
                    <input id="Nachname" name="Nachname" type="text" maxlength="20"  class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="Geschlecht"  class="form-label">Geschlecht:</label>
                    <input id="Geschlecht" name="Geschlecht" type ="text"
                           maxlength="20"  class="form-control">
                </div>


                <div class="row mt-3">
                    <div class="col-md-5">
                        <button type="submit" name="addButton5"  class="btn btn-primary">
                            Add
                        </button>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" name="deleteButton3" class="btn btn-danger">
                            Delete
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-2">
            <h2>Search:</h2>
            <form method="get" class="row g-3">

                <div class=col-md-6">
                    <label for="_Kundennummer" class="form-label">Kundennummer:</label>
                    <input id="_Kundennummer" name="Kundennummer" type="number" class="form-control input-md" value='<?php echo $Kundennummer; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="_Studio_ID" class="form-label">Studio-ID:</label>
                    <input id="_Studio_ID" name="Studio_ID" type ="number" class="form-control input-md" value='<?php echo $Studio_ID; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="_Vorname" class="form-label">Vorname:</label>
                    <input id="_Vorname" name="Vorname" type="text" class="form-control input-md" value='<?php echo $Vorname; ?>'
                           maxlength="20">
                </div class=col-md-6">

                <div class=col-md-6">
                    <label for="_Nachname" class="form-label">Nachname:</label>
                    <input id="_Nachname" name="Nachname" type="text" class="form-control input-md" value='<?php echo $Nachname; ?>'
                           maxlength="20">
                </div>

                <div class=col-md-6">
                    <label for="_Geschlecht" class="form-label">Geschlecht:</label>
                    <input id="_Geschlecht" name="Geschlecht" type="text" class="form-control input-md" value='<?php echo $Geschlecht; ?>'
                           maxlength="20">
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
                    <button id='submit5' type='submit' class="btn btn-info custom-button">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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

$Trainingsdatum = '';
if (isset($_GET['Trainingsdatum'])) {
    $Trainingsdatum = $_GET['Trainingsdatum'];
}

//Fetch data from database
$studio_array = $database->selectAllCoacht($Mitarbeiter_ID, $Kundennummer, $Beginnzeit, $Endzeit, $Trainingsdatum);
?>

<div class="container ml-2" >
    <div class="row">
        <div class="col-md-7">
            <h2>Coacht Table:</h2>
            <div class="table-container table-responsive" >
                <table class="table table-bordered table-hover data-table" data-php-file="Coacht.php" id="data-table-6">
                    <thead  class="thead-dark">
                    <tr>
                        <th scope="col">Mitarbeiter_ID</th>
                        <th scope="col">Kundennummer</th>
                        <th scope="col">Beginnzeit</th>
                        <th scope="col">Endzeit</th>
                        <th scope="col">Trainingsdatum</th>
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
                    <?php foreach ($studio_array as $Coacht) : ?>
                        <tr>
                            <td contenteditable="true"><?php echo $Coacht['MITARBEITER_ID']; ?>  </td>
                            <td contenteditable="true"><?php echo $Coacht['KUNDENNUMMER']; ?>  </td>
                            <td contenteditable="true" class="timestamp-cell"><?php echo DateTime::createFromFormat('d-M-y H.i.s.u A', $Coacht['BEGINNZEIT'])->format('H:i:s'); ?>  </td>
                            <td contenteditable="true" class="timestamp-cell"><?php echo DateTime::createFromFormat('d-M-y H.i.s.u A', $Coacht['ENDZEIT'])->format('H:i:s'); ?>  </td>
                            <td contenteditable="true" class="date-cell"><?php echo date('d-m-Y', strtotime($Coacht['TRAININGSDATUM'])); ?>  </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-3">
            <h2>Input: </h2>
            <form method="post" action="Coacht.php" class="row g-3">

                <div class=col-md-6">
                    <label for="M_IDS" class="form-label">Mitarbeiter-ID:</label>
                    <input id="M_IDS" name="Mitarbeiter_ID" type="number" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="K_IDS" class="form-label">Kundennummer:</label>
                    <input id="K_IDS" name="Kundennummer" type="number" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="Beginnzeit" class="form-label">Beginnzeit:</label>
                    <input id="Beginnzeit" name="Beginnzeit" type="datetime-local" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="Endzeit" class="form-label">Endzeit:</label>
                    <input id="Endzeit" name="Endzeit" type="datetime-local" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="Trainingsdatum" class="form-label">Trainingsdatum:</label>
                    <input id="Trainingsdatum" name="Trainingsdatum" type="date" class="form-control">
                </div>


                <div class="row mt-3">
                    <div class="col-md-6">
                        <button type="submit" name="addButton7"  class="btn btn-primary">
                            Add
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" name="deleteButton8" class="btn btn-danger">
                            Delete
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-2">
            <h2>Search:</h2>
            <form method="get" class="row g-3">


                <div class=col-md-6">
                    <label for="_Mitarbeiter-IDs_" class="form-label">Mitarbeiter-ID:</label>
                    <input id="_Mitarbeiter-IDs_" name="Mitarbeiter_ID" type ="number" class="form-control input-md" value='<?php echo $Mitarbeiter_ID; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="_Kundennummers" class="form-label">Kundennummer:</label>
                    <input id="_Kundennummers" name="Kundennummer" type="number" class="form-control input-md" value='<?php echo $Kundennummer; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="_Beginnzeit" class="form-label">Beginnezeit:</label>
                    <input id="_Beginnzeit" name="Beginnzeit" type="datetime-local" class="form-control input-md"  value='<?php echo $Beginnzeit; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="_Endzeit" class="form-label">Endzeit:</label>
                    <input id="_Endzeit" name="Endzeit" type="datetime-local" class="form-control input-md" value='<?php echo $Endzeit; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="TD" class="form-label">Trainingsdatum:</label>
                    <input id="TD" name="Trainingsdatum" type="date" class="form-control input-md" value='<?php echo $Trainingsdatum; ?>'>
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
                    <button id='submit8' type='submit' class="btn btn-info custom-button">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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

$Zeitpunkt = '';
if (isset($_GET['Zeitpunkt'])) {
    $Zeitpunkt = $_GET['Zeitpunkt'];
}

//Fetch data from database
$studio_array = $database->selectAllBetreut($Mitarbeiter_ID, $Kundennummer, $Zeitpunkt);
?>



<div class="container ml-2" >
    <div class="row">
        <div class="col-md-7">
            <h2>Betreut Table:</h2>
            <div class="table-container table-responsive" >
                <table class="table table-bordered table-hover data-table" data-php-file="Betreut.php" id="data-table-7">
                    <thead  class="thead-dark">
                    <tr>
                        <th scope="col">Mitarbeiter_ID</th>
                        <th scope="col">Kundennummer</th>
                        <th scope="col">Zeitpunkt</th>
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
                    <?php foreach ($studio_array as $Betreut) : ?>
                        <tr>
                            <td contenteditable="true"><?php echo $Betreut['MITARBEITER_ID']; ?>  </td>
                            <td contenteditable="true"><?php echo $Betreut['KUNDENNUMMER']; ?>  </td>
                            <td contenteditable="true" class="timestamp-cell"><?php echo DateTime::createFromFormat('d-M-y H.i.s.u A', $Betreut['ZEITPUNKT'])->format('H:i:s d-m-y'); ?>  </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-3">
            <h2>Input: </h2>
            <form method="post" action="Betreut.php" class="row g-3">

                <div class=col-md-6">
                    <label for="M_IDS" class="form-label">Mitarbeiter-ID:</label>
                    <input id="M_IDS" name="Mitarbeiter_ID" type="number" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="K_IDS" class="form-label">Kundennummer:</label>
                    <input id="K_IDS" name="Kundennummer" type="number" class="form-control">
                </div>

                <div class=col-md-6">
                    <label for="Zeitpunkt" class="form-label">Zeitpunkt:</label>
                    <input id="Zeitpunkt" name="Zeitpunkt" type="datetime-local" class="form-control">
                </div>


                <div class="row mt-3">
                    <div class="col-md-6">
                        <button type="submit" name="addButton8"  class="btn btn-primary">
                            Add
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" name="deleteButton9" class="btn btn-danger">
                            Delete
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-2">
            <h2>Search:</h2>
            <form method="get" class="row g-3">


                <div class=col-md-6">
                    <label for="_Mitarbeiter-IDs__" class="form-label">Mitarbeiter-ID:</label>
                    <input id="_Mitarbeiter-IDs__" name="Mitarbeiter_ID" type ="number" class="form-control input-md" value='<?php echo $Mitarbeiter_ID; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="_Kundennummers_" class="form-label">Kundennummer:</label>
                    <input id="_Kundennummers_" name="Kundennummer" type="number" class="form-control input-md" value='<?php echo $Kundennummer; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="_Zeitpunkt" class="form-label">Zeitpunkt:</label>
                    <input id="_Zeitpunkt" name="Beginnzeit" type="datetime-local" class="form-control input-md"  value='<?php echo $Zeitpunkt; ?>'>
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
                    <button id='submit9' type='submit' class="btn btn-info custom-button">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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