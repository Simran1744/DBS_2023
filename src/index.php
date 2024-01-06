
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

            editedCell.data('original-value', currentValue);


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
            let rowId = [];
            let originalValue = editedCell.data('original-value');
            $(this).closest('tr').find('td').each(function(){
                rowId.push(($(this).text()))});
            let phpFile = editedCell.closest('.data-table').data('php-file');
            editedCell.text(newValue);


            $.ajax({
                url: phpFile,
                method: 'POST',
                data: {
                    action: 'update',
                    column: column,
                    value: newValue,
                    originalValue: originalValue,
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

                <div class="col-md-10">
                    <label for="new_ID" class="form-label">ID:</label>
                    <input id="new_ID" name="Studio_ID" type="number" min="1" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="new_F_Name" class="form-label">Name:</label>
                    <input id="new_F_Name" name="F_Name" type="text" maxlength="20" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="new_Ort" class="form-label">Ort:</label>
                    <input id="new_Ort" name="Ort" type="text" maxlength="20" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="new_Platz" class="form-label">Platz:</label>
                    <input id="new_Platz" name="Platz" type="number" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="new_Strasse" class="form-label">Strasse:</label>
                    <input id="new_Strasse" name="Strasse" type="text" maxlength="20" class="form-control">
                </div>


                <div class="row mt-3">
                    <div class="col-md-5">
                        <button type="submit" name="addButton1"  class="btn btn-primary custom-button">
                            Add
                        </button>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" name="deleteButton1" class="btn btn-danger custom-button ">
                            Delete
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-2">
            <h2>Search:</h2>
            <form method="get" class="row g-3">
                <div class=col-md-10">
                    <label for="Studio_ID" class="form-label">ID:</label>
                    <input id="Studio_ID" name="Studio_ID" type="number" value='<?php echo $Studio_ID; ?>' min="1" class="form-control">
                </div>

                <div class=col-md-10">
                    <label for="F_Name" class="form-label">Name:</label>
                    <input id="F_Name" name="F_Name" type="text" value='<?php echo $F_Name; ?>'
                           maxlength="20" class="form-control">
                </div>

                <div class=col-md-10">
                    <label for="Ort" class="form-label">Ort:</label>
                    <input id="Ort" name="Ort" type="text" value='<?php echo $Ort; ?>'
                           maxlength="20" class="form-control">
                </div>

                <div class=col-md-10">
                    <label for="Platz" class="form-label">Platz:</label>
                    <input id="Platz" name="Platz" type="number" value='<?php echo $Platz; ?>'
                           maxlength="20" class="form-control">
                </div>

                <div class=col-md-10">
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

                <div class="col-md-10">
                    <label for="new_ID" class="form-label">Mitarbeiter-ID:</label>
                    <input id="new_ID" name="Mitarbeiter_ID" type="number" class="form-control">
                </div>
                <br>

                <div class="col-md-10">
                    <label for="new_F_ID" class="form-label">Studio-ID:</label>
                    <input id="new_F_ID" name="Studio_ID" type="number" min="1" class="form-control">
                </div>
                <br>

                <div class="col-md-10">
                    <label for="new_Vorname" class="form-label">Vorname:</label>
                    <input id="new_Vorname" name="Vorname" type="text" maxlength="20" class="form-control">
                </div>
                <br>

                <div class="col-md-10">
                    <label for="new_Nachname" class="form-label">Nachname:</label>
                    <input id="new_Nachname" name="Nachname" type="text" maxlength="20" class="form-control">
                </div>
                <br>

                <div class="row mt-3">
                    <div class="col-md-5">
                        <button type="submit" name="addButton2"  class="btn btn-primary custom-button">
                            Add
                        </button>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" name="deleteButton2" class="btn btn-danger custom-button">
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

                <div class="col-md-10">
                    <label for="P_ID" class="form-label">Mitarbeiter-ID:</label>
                    <input id="P_ID" name="Mitarbeiter_ID" type="number" class="form-control">
                </div>


                <div class="col-md-10">
                    <label for="Geschlecht" class="form-label">Geschlecht:</label>
                    <input id="Geschlecht" name="Geschlecht" type="text" maxlength="20" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="Sprachkenntnisse" class="form-label">Sprachkenntnissse:</label>
                    <input id="Sprachkenntnisse" name="Sprachkenntnisse" type="text" maxlength="20" class="form-control">
                </div>

                <div class="mt-3 col-md-10">
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

                <div class="col-md-10">
                    <label for="P_ID" class="form-label">Mitarbeiter-ID:</label>
                    <input id="P_ID" name="Mitarbeiter_ID" type="number" class="form-control">
                </div>


                <div class="col-md-10">
                    <label for="Arbeitszeiten" class="form-label">Arbeitszeiten:</label>
                    <input id="Arbeitszeiten" name="Arbeitszeiten" type="text" maxlength="20" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="Sprachkenntnisse_" class="form-label">Sprachkenntnissse:</label>
                    <input id="Sprachkenntnisse_" name="Sprachkenntnisse" type="text" maxlength="20" class="form-control">
                </div>

                <div class="mt-3 col-md-10">
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

                <div class="col-md-10">
                    <label for="K_N"  class="form-label">ID:</label>
                    <input id="K_N" name="Kundennummer" type="number"  class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="F_ID"  class="form-label">Studio-ID:</label>
                    <input id="F_ID" name="Studio_ID" type="number" min="1"  class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="Vorname"  class="form-label">Vorname:</label>
                    <input id="Vorname" name="Vorname" type="text" maxlength="20"  class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="Nachname"  class="form-label">Nachname:</label>
                    <input id="Nachname" name="Nachname" type="text" maxlength="20"  class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="Geschlecht"  class="form-label">Geschlecht:</label>
                    <select id="Geschlecht" name="Geschlecht" type ="text" class="form-control input-md">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>


                <div class="row mt-3">
                    <div class="col-md-5">
                        <button type="submit" name="addButton5"  class="btn btn-primary custom-button">
                            Add
                        </button>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" name="deleteButton3" class="btn btn-danger custom-button">
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
                    <select id="_Geschlecht" name="Geschlecht" type="text" class="form-control input-md" value='<?php echo $Geschlecht; ?>'>
                        <option value="">All</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
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
<div class="container ml-2">
    <h2>Membership Details</h2>
        <form action="process.php" method="post">
            <label for="customerID" class="form-label">Customer ID:</label>
            <input id="customerID" type="number"  name="Kundennummer">
            <button type="submit" class="btn btn-info custom-button">Get Details</button>
        </form>
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
                            <td contenteditable="true" class="timestamp-cell"><?php echo DateTime::createFromFormat('d-M-y H.i.s.u A', $Coacht['BEGINNZEIT'])->format('H:i:s y-m-d'); ?>  </td>
                            <td contenteditable="true" class="timestamp-cell"><?php echo DateTime::createFromFormat('d-M-y H.i.s.u A', $Coacht['ENDZEIT'])->format('H:i:s y-m-d'); ?>  </td>
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

                <div class="col-md-10">
                    <label for="M_IDS" class="form-label">Mitarbeiter-ID:</label>
                    <input id="M_IDS" name="Mitarbeiter_ID" type="number" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="K_IDS" class="form-label">Kundennummer:</label>
                    <input id="K_IDS" name="Kundennummer" type="number" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="Beginnzeit" class="form-label">Beginnzeit:</label>
                    <input id="Beginnzeit" name="Beginnzeit" type="datetime-local" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="Endzeit" class="form-label">Endzeit:</label>
                    <input id="Endzeit" name="Endzeit" type="datetime-local" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="Trainingsdatum" class="form-label">Trainingsdatum:</label>
                    <input id="Trainingsdatum" name="Trainingsdatum" type="date" class="form-control">
                </div>


                <div class="row mt-3">
                    <div class="col-md-5">
                        <button type="submit" name="addButton7"  class="btn btn-primary custom-button">
                            Add
                        </button>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" name="deleteButton8" class="btn btn-danger custom-button">
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
                            <td contenteditable="true" class="timestamp-cell"><?php echo DateTime::createFromFormat('d-M-y H.i.s.u A', $Betreut['ZEITPUNKT'])->format('H:i:s y-m-d'); ?>  </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-3">
            <h2>Input: </h2>
            <form method="post" action="Betreut.php" class="row g-3">

                <div class="col-md-10">
                    <label for="M_IDS" class="form-label">Mitarbeiter-ID:</label>
                    <input id="M_IDS" name="Mitarbeiter_ID" type="number" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="K_IDS" class="form-label">Kundennummer:</label>
                    <input id="K_IDS" name="Kundennummer" type="number" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="Zeitpunkt" class="form-label">Zeitpunkt:</label>
                    <input id="Zeitpunkt" name="Zeitpunkt" type="datetime-local" class="form-control">
                </div>


                <div class="row mt-3">
                    <div class="col-md-5">
                        <button type="submit" name="addButton8"  class="btn btn-primary custom-button">
                            Add
                        </button>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" name="deleteButton9" class="btn btn-danger custom-button">
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


<div class="container ml-2" >
    <div class="row">
        <div class="col-md-7">
            <h2>Mitgliedschaft Table:</h2>
            <div class="table-container table-responsive" >
                <table class="table table-bordered table-hover data-table" data-php-file="Mitgliedschaft.php" id="data-table-8">
                    <thead  class="thead-dark">
                    <tr>
                        <th scope="col">Kundenummer</th>
                        <th scope="col">Mitgliedschaftsnummer</th>
                        <th scope="col">Mitgliedschafts_Stufe</th>
                        <th scope="col">Monatskosten</th>
                        <th scope="col">Gueltigkeit</th>
                        <th scope="col">Erstellungsdatum</th>
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
                    <?php foreach ($studio_array as $MG) : ?>
                        <tr>
                            <td contenteditable="true"><?php echo $MG['KUNDENNUMMER']; ?>  </td>
                            <td contenteditable="true"><?php echo $MG['MITGLIEDSCHAFTSNUMMER']; ?>  </td>
                            <td contenteditable="true"><?php echo $MG['MITGLIEDSCHAFTS_STUFE']; ?>  </td>
                            <td contenteditable="true"><?php echo $MG['MONATSKOSTEN']; ?>  </td>
                            <td contenteditable="true"><?php echo $MG['GUELTIGKEIT']; ?>  </td>
                            <td contenteditable="true" class="date-cell"><?php echo date('d-m-Y', strtotime($MG['ERSTELLUNGSDATUM'])); ?>  </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-3">
            <h2>Input: </h2>
            <form method="post" action="Mitgliedschaft.php" class="row g-3">

                <div class="col-md-10">
                    <label for="KS_" class="form-label">Kundennummer:</label>
                    <input id="KS_" name="Kundennummer" type="number" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="MGS" class="form-label">Mitgliedschaftsnummer:</label>
                    <input id="MGS" name="Mitgliedschaftsnummer" type="number" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="Stufe" class="form-label">Mitgliedschafts-Stufe:</label>
                    <select id="Stufe" name="Mitgliedschafts_Stufe" type="text" class="form-control">
                        <option value="Bronze">Bronze</option>
                        <option value="Silber">Silber</option>
                        <option value="Gold">Gold</option>
                    </select>
                </div>

                <div class="col-md-10">
                    <label for="MO" class="form-label">Monatskosten:</label>
                    <input id="MO" name="Monatskosten" type="number" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="GU" class="form-label">Gültigkeit:</label>
                    <select id="GU" name="Gueltigkeit" type="text" class="form-control input-md">
                        <option value="gueltig">Gueltig</option>
                        <option value="ungueltig">Ungueltig</option>
                    </select>
                </div>

                <div class="col-md-10">
                    <label for="ERST" class="form-label">Erstellungsdatum:</label>
                    <input id="ERST" name="Erstellungsdatum" type="date" class="form-control input-md">
                </div>

                <div class="row mt-3">
                    <div class="col-md-5">
                        <button type="submit" name="addButton9"  class="btn btn-primary custom-button">
                            Add
                        </button>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" name="deleteButton10" class="btn btn-danger custom-button">
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
                    <label for="K_S" class="form-label">Kundennummer:</label>
                    <input id="K_S" name="Kundennummer" type ="number" class="form-control input-md" value='<?php echo $Kundennummer; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="MGS_" class="form-label">Mitgliedschaftsnummer:</label>
                    <input id="MGS_" name="Mitglidschaftsnummer" type="number" class="form-control input-md" value='<?php echo $Mitgliedschaftsnummer; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="MTGS" class="form-label">Mitgliedschafts-Stufe:</label>
                    <select id="MTGS" name="Mitgliedschafts_Stufe" type="text" class="form-control input-md"  value='<?php echo $Mitgliedschafts_Stufe; ?>'>
                        <option value="">All</option>
                        <option value="Bronze">Bronze</option>
                        <option value="Silber">Silber</option>
                        <option value="Gold">Gold</option>
                    </select>
                </div>

                <div class=col-md-6">
                    <label for="MONAT" class="form-label">Monatskosten:</label>
                    <input id="MONAT" name="Monatskosten" type="number" class="form-control input-md"  value='<?php echo $Monatskosten; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="GUS" class="form-label">Gültigkeit:</label>
                    <select id="GUS" name="Gueltigkeit" type="text" class="form-control input-md"  value='<?php echo $Gueltigkeit; ?>'>
                        <option value="">All</option>
                        <option value="gueltig">Gueltig</option>
                        <option value="ungueltig">Ungueltig</option>
                    </select>
                </div>

                <div class=col-md-6">
                    <label for="ED" class="form-label">Erstellungsdatum:</label>
                    <input id="ED" name="Erstellungsdatum" type="date" class="form-control input-md"  value='<?php echo $Erstellungsdatum; ?>'>
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
                    <button id='submit10' type='submit' class="btn btn-info custom-button">
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

$Mitgliedschaftsnummer = '';
if (isset($_GET['Mitgliedschaftsnummer'])) {
    $Mitgliedschaftsnummer = $_GET['Mitgliedschaftsnummer'];
}

$Zeitpunkt = '';
if (isset($_GET['Zeitpunkt'])) {
    $Zeitpunkt = $_GET['Zeitpunkt'];
}

//Fetch data from database
$studio_array = $database->selectAllKons($Mitarbeiter_ID, $Kundennummer, $Mitgliedschaftsnummer, $Zeitpunkt);
?>


<div class="container ml-2" >
    <div class="row">
        <div class="col-md-7">
            <h2>Kontrolliert Table:</h2>
            <div class="table-container table-responsive" >
                <table class="table table-bordered table-hover data-table" data-php-file="Kontrolliert.php" id="data-table-9">
                    <thead  class="thead-dark">
                    <tr>
                        <th scope="col">Mitarbeiter_ID</th>
                        <th scope="col">Kundennummer</th>
                        <th scope="col">Mitgliedschaftsnummer</th>
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
                    <?php foreach ($studio_array as $Kons) : ?>
                        <tr>
                            <td contenteditable="true"><?php echo $Kons['MITARBEITER_ID']; ?>  </td>
                            <td contenteditable="true"><?php echo $Kons['KUNDENNUMMER']; ?>  </td>
                            <td contenteditable="true"><?php echo $Kons['MITGLIEDSCHAFTSNUMMER']; ?>  </td>
                            <td contenteditable="true" class="timestamp-cell"><?php echo DateTime::createFromFormat('d-M-y H.i.s.u A', $Kons['ZEITPUNKT'])->format('H:i:s y-m-d'); ?>  </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-3">
            <h2>Input: </h2>
            <form method="post" action="Kontrolliert.php" class="row g-3">

                <div class="col-md-10">
                    <label for="M_" class="form-label">Mitarbeiter-ID:</label>
                    <input id="M_" name="Mitarbeiter_ID" type="number" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="K_" class="form-label">Kundennummer:</label>
                    <input id="K_" name="Kundennummer" type="number" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="N_" class="form-label">Mitgliedschaftsnummer:</label>
                    <input id="N_" name="Mitgliedschaftsnummer" type="number" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="Z_" class="form-label">Zeitpunkt:</label>
                    <input id="Z_" name="Zeitpunkt" type="datetime-local" class="form-control">
                </div>

                <div class="row mt-3">
                    <div class="col-md-5">
                        <button type="submit" name="addButton10"  class="btn btn-primary custom-button">
                            Add
                        </button>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" name="deleteButton11" class="btn btn-danger custom-button">
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
                    <label for="_M" class="form-label">Mitarbeiter-ID:</label>
                    <input id="_M" name="Mitarbeiter_ID" type ="number" class="form-control input-md" value='<?php echo $Mitarbeiter_ID; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="_N" class="form-label">Kundennummer:</label>
                    <input id="_N" name="Kundennummer" type="number" class="form-control input-md" value='<?php echo $Kundennummer; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="_S" class="form-label">Mitgliedschaftsnummer:</label>
                    <input id="_S" name="Mitgliedschaftsnummer" type="number" class="form-control input-md"  value='<?php echo $Mitgliedschaftsnummer; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="_Z" class="form-label">Zeitpunkt:</label>
                    <input id="_Z" name="Zeitpunkt" type="datetime-local" class="form-control input-md"  value='<?php echo $Zeitpunkt; ?>'>
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
                    <button id='submit11' type='submit' class="btn btn-info custom-button">
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

$Kundennummer1 = '';
if (isset($_GET['Kundennummer1'])) {
    $Kundennummer1 = $_GET['Kundennummer1'];
}

$Kundennummer2 = '';
if (isset($_GET['Kundennummer2'])) {
    $Kundennummer2 = $_GET['Kundennummer2'];
}

$Zeitpunkt = '';
if (isset($_GET['Zeitpunkt'])) {
    $Zeitpunkt = $_GET['Zeitpunkt'];
}

//Fetch data from database
$studio_array = $database->selectAllTraining($Kundennummer1, $Kundennummer2, $Zeitpunkt);
?>

<div class="container ml-2" >
    <div class="row">
        <div class="col-md-7">
            <h2>Training Table:</h2>
            <div class="table-container table-responsive" >
                <table class="table table-bordered table-hover data-table" data-php-file="Trainiert_Mit.php" id="data-table-10">
                    <thead  class="thead-dark">
                    <tr>
                        <th scope="col">Kundennummer1</th>
                        <th scope="col">Kundennummer2</th>
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
                    <?php foreach ($studio_array as $Train) : ?>
                        <tr>
                            <td contenteditable="true"><?php echo $Train['KUNDENNUMMER1']; ?>  </td>
                            <td contenteditable="true"><?php echo $Train['KUNDENNUMMER2']; ?>  </td>
                            <td contenteditable="true" class="timestamp-cell"><?php echo DateTime::createFromFormat('d-M-y H.i.s.u A', $Train['ZEITPUNKT'])->format('H:i:s y-m-d'); ?>  </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-3">
            <h2>Input: </h2>
            <form method="post" action="Trainiert_Mit.php" class="row g-3">

                <div class="col-md-10">
                    <label for="M_1" class="form-label">Kundennummer1:</label>
                    <input id="M_1" name="Kundennummer1" type="number" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="K_2" class="form-label">Kundennummer2:</label>
                    <input id="K_2" name="Kundennummer2" type="number" class="form-control">
                </div>

                <div class="col-md-10">
                    <label for="Z_1" class="form-label">Zeitpunkt:</label>
                    <input id="Z_1" name="Zeitpunkt" type="datetime-local" class="form-control">
                </div>

                <div class="row mt-3">
                    <div class="col-md-5">
                        <button type="submit" name="addButton11"  class="btn btn-primary custom-button">
                            Add
                        </button>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" name="deleteButton12" class="btn btn-danger custom-button">
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
                    <label for="_M1" class="form-label">Kundennummer1:</label>
                    <input id="_M1" name="Kundennummer1" type ="number" class="form-control input-md" value='<?php echo $Mitarbeiter_ID; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="_N2" class="form-label">Kundennummer2:</label>
                    <input id="_N2" name="Kundennummer2" type="number" class="form-control input-md" value='<?php echo $Kundennummer; ?>'>
                </div>

                <div class=col-md-6">
                    <label for="_Z1" class="form-label">Zeitpunkt:</label>
                    <input id="_Z1" name="Zeitpunkt" type="datetime-local" class="form-control input-md"  value='<?php echo $Zeitpunkt; ?>'>
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
                    <button id='submit12' type='submit' class="btn btn-info custom-button">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<br>
<hr>







</body>
</html>