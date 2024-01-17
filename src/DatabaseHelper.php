<?php

class DatabaseHelper
{
// Since the connection details are constant, define them as const
    // We can refer to constants like e.g. DatabaseHelper::username
    const username = 'a12224337'; // use a + your matriculation number
    const password = 'dbs77'; // use your oracle db password
    const con_string = '//oracle19.cs.univie.ac.at:1521/orclcdb';

    // Since we need only one connection object, it can be stored in a member variable.
    // $conn is set in the constructor.
    protected $conn;

    // Create connection in the constructor
    public function __construct()
    {
        try {
            // Create connection with the command oci_connect(String(username), String(password), String(connection_string))
            $this->conn = oci_connect(
                DatabaseHelper::username,
                DatabaseHelper::password,
                DatabaseHelper::con_string
            );

            //check if the connection object is != null
            if (!$this->conn) {
                // die(String(message)): stop PHP script and output message:
                die("DB error: Connection can't be established!");
            }

        } catch (Exception $e) {
            die("DB error: {$e->getMessage()}");
        }
    }

    public function __destruct()
    {
        // clean up
        oci_close($this->conn);
    }

    // This function creates and executes a SQL select statement and returns an array as the result
    // 2-dimensional array: the result array contains nested arrays (each contains the data of a single row)
    public function selectAllFitnessstudio($Studio_ID, $F_Name, $Plz, $Strasse)
    {
        // Define the sql statement string
        // Notice that the parameters $person_id, $surname, $name in the 'WHERE' clause
        $sql = "SELECT * FROM Fitnessstudio
            WHERE Studio_ID LIKE '%{$Studio_ID}%'
              AND upper(f_name) LIKE upper('%{$F_Name}%')
              AND upper(strasse) LIKE upper('%{$Strasse}%')
              AND upper(plz) LIKE upper('%{$Plz}%')";


        $statement = oci_parse($this->conn, $sql);

        // Executes the statement
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function selectAllPlz($Plz, $Ort)
    {

        $sql = "SELECT * FROM POSTLEITZAHL
            WHERE Plz LIKE '%{$Plz}%'
              AND upper(ort) LIKE upper('%{$Ort}%')";


        $statement = oci_parse($this->conn, $sql);

        // Executes the statement
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }


    public function selectAllMitarbeiter($Mitarbeiter_ID, $Studio_ID, $Vorname, $Nachname){

        $sql = "SELECT * FROM MITARBEITER
            WHERE MITARBEITER_ID LIKE '%{$Mitarbeiter_ID}%'
              AND upper(STUDIO_ID) LIKE upper('%{$Studio_ID}%')
              AND upper(VORNAME) LIKE upper('%{$Vorname}%')
              AND upper(NACHNAME) LIKE upper('%{$Nachname}%')";
        $statement = oci_parse($this->conn, $sql);

        // Executes the statement
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function selectAllPersonalTrainer($Mitarbeiter_ID, $Geschlecht, $Spezialisierung){


        $sql = "SELECT MITARBEITER.MITARBEITER_ID, MITARBEITER.STUDIO_ID, PERSONAL_TRAINER.GESCHLECHT, PERSONAL_TRAINER.SPEZIALISIERUNG
        FROM PERSONAL_TRAINER
        JOIN MITARBEITER ON PERSONAL_TRAINER.MITARBEITER_ID = MITARBEITER.MITARBEITER_ID
        WHERE upper(PERSONAL_TRAINER.MITARBEITER_ID) LIKE upper('%{$Mitarbeiter_ID}%')
          AND upper(PERSONAL_TRAINER.SPEZIALISIERUNG) LIKE upper('%{$Spezialisierung}%')";

        if($Geschlecht != ''){
            $sql .= " AND upper(PERSONAL_TRAINER.GESCHLECHT) = upper('{$Geschlecht}')";
        }

        /*
        $sql = "SELECT * FROM PERSONAL_TRAINER
            WHERE MITARBEITER_ID LIKE '%{$Mitarbeiter_ID}%'
              AND upper(SPEZIALISIERUNG) LIKE upper('%{$Spezialisierung}%')";

        if($Geschlecht != ''){
            $sql .= "AND upper(GESCHLECHT) = upper('{$Geschlecht}')";
        }*/

        $statement = oci_parse($this->conn, $sql);




        // Executes the statement
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function selectAllRezeptionist($Mitarbeiter_ID, $Arbeitszeiten, $SprichtEnglisch){

        $sql = "SELECT * FROM REZEPTIONIST
            WHERE MITARBEITER_ID LIKE '%{$Mitarbeiter_ID}%'
              AND upper(ARBEITSZEITEN) LIKE upper('%{$Arbeitszeiten}%')
              AND upper(SPRICHTENGLISCH) LIKE upper('%{$SprichtEnglisch}%')";
        $statement = oci_parse($this->conn, $sql);

        // Executes the statement
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function selectAllKunde($Kundennummer, $Studio_ID, $Vorname, $Nachname, $Geschlecht){
        $sql = "SELECT * FROM KUNDE
            WHERE KUNDENNUMMER LIKE '%{$Kundennummer}%'
              AND upper(STUDIO_ID) LIKE upper('%{$Studio_ID}%')
              AND upper(VORNAME) LIKE upper('%{$Vorname}%')
              AND upper(NACHNAME) LIKE upper('%{$Nachname}%')";

        if($Geschlecht != ''){
            $sql .= "AND upper(GESCHLECHT) = upper('{$Geschlecht}')";
        }

        $statement = oci_parse($this->conn, $sql);

        // Executes the statement
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function selectAllCoacht($Mitarbeiter_ID, $Kundennummer, $Beginnzeit, $Endzeit){
        $f_beginnzeit = date('Y-m-d H:i:s', strtotime($Beginnzeit));
        $f_endzeit = date('Y-m-d H:i:s', strtotime($Endzeit));



        $str_beg = strval($f_beginnzeit);
        $str_end = strval($f_endzeit);

        $sql = "SELECT * FROM COACHT
            WHERE MITARBEITER_ID LIKE  '%{$Mitarbeiter_ID}%'
            AND KUNDENNUMMER LIKE '%{$Kundennummer}%'";


        if ($str_beg !== '1970-01-01 01:00:00') {
            $sql .= " AND BEGINNZEIT = TO_TIMESTAMP('{$str_beg}', 'YYYY-MM-DD HH24:MI:SS')";
        }

        if ($str_end !== '1970-01-01 01:00:00') {
            $sql .= " AND ENDZEIT = TO_TIMESTAMP('{$str_end}', 'YYYY-MM-DD HH24:MI:SS')";
        }

        $statement = oci_parse($this->conn, $sql);


        // Executes the statements
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function selectAllBetreut($Mitarbeiter_ID, $Kundennummer, $Zeitpunkt){

        $f_zeit = date('Y-m-d H:i:s', strtotime($Zeitpunkt));
        $str_zeit = strval($f_zeit);


    $sql = "SELECT * FROM BETREUT
            WHERE upper(MITARBEITER_ID) LIKE upper('%{$Mitarbeiter_ID}%')
            AND upper(KUNDENNUMMER) LIKE upper('%{$Kundennummer}%')";


        if ($str_zeit !== '1970-01-01 01:00:00') {
            $sql .= " AND ZEITPUNKT = TO_TIMESTAMP('{$str_zeit}', 'YYYY-MM-DD HH24:MI:SS')";
        }

    $statement = oci_parse($this->conn, $sql);

    // Executes the statements
    oci_execute($statement);

    oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    //clean up;
    oci_free_statement($statement);

    return $res;
    }

    public function selectAllMGSt($Stufe, $Monatskosten){

        $sql = "SELECT * FROM MITGLIEDSCHAFTS_STUFE
            WHERE upper(STUFE) LIKE upper('%{$Stufe}%')
            AND upper(MONATSKOSTEN) LIKE upper('%{$Monatskosten}%')
            ";

        $statement = oci_parse($this->conn, $sql);

        // Executes the statements
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function selectAllMGs($Kundennummer, $Mitgliedschaftsnummer, $Stufe, $Gueltigkeit, $Erstellungsdatum){

        $f_erstellungsdatum = date('Y-m-d', strtotime($Erstellungsdatum));

        $str_dat = strval($f_erstellungsdatum);



        $sql = "SELECT * FROM MITGLIEDSCHAFT
            WHERE upper(KUNDENNUMMER) LIKE upper('%{$Kundennummer}%')
            AND upper(MITGLIEDSCHAFTSNUMMER) LIKE upper('%{$Mitgliedschaftsnummer}%')
            AND upper(STUFE) LIKE upper('%{$Stufe}%')
            
            
            ";

        if($Gueltigkeit !== ''){
            $sql .= " AND upper(GUELTIGKEIT) LIKE upper('{$Gueltigkeit}')";
        }
        if ($str_dat !== '1970-01-01') {
            $sql .= " AND ERSTELLUNGSDATUM LIKE TO_DATE('{$str_dat}'), 'YYYY-MM-DD')";
        }


        $statement = oci_parse($this->conn, $sql);

        // Executes the statements
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function selectAllKons($Mitarbeiter_ID, $Kundennummer, $Mitgliedschaftsnummer, $Zeitpunkt){

        $f_zeit = date('Y-m-d H:i:s', strtotime($Zeitpunkt));
        $str_zeit = strval($f_zeit);


        $sql = "SELECT * FROM KONTROLLIERT
            WHERE upper(MITARBEITER_ID) LIKE upper('%{$Mitarbeiter_ID}%')
            AND upper(KUNDENNUMMER) LIKE upper('%{$Kundennummer}%')
            AND upper(MITGLIEDSCHAFTSNUMMER) LIKE upper('%{$Mitgliedschaftsnummer}%')       
            ";

        if ($str_zeit !== '1970-01-01 01:00:00') {
            $sql .= " AND ZEITPUNKT = TO_TIMESTAMP('{$str_zeit}', 'YYYY-MM-DD HH24:MI:SS')";
        }


        $statement = oci_parse($this->conn, $sql);

        // Executes the statements
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function selectAllTraining($Kundennummer1, $Kundennummer2, $Zeitpunkt){

        $f_zeit = date('Y-m-d H:i:s', strtotime($Zeitpunkt));
        $str_zeit = strval($f_zeit);

        $sql = "SELECT * FROM TRAINIERT_MIT
            WHERE upper(KUNDENNUMMER1) LIKE upper('%{$Kundennummer1}%')
            AND upper(KUNDENNUMMER2) LIKE upper('%{$Kundennummer2}%')";

        if ($str_zeit !== '1970-01-01 01:00:00') {
            $sql .= " AND ZEITPUNKT = TO_TIMESTAMP('{$str_zeit}', 'YYYY-MM-DD HH24:MI:SS')";
        }
        $statement = oci_parse($this->conn, $sql);

        // Executes the statements
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }


    public function insertIntoPlz($Plz, $Ort)
    {
        $sql = "INSERT INTO POSTLEITZAHL (Plz, Ort) VALUES ('{$Plz}','{$Ort}')";


        var_dump($Plz . $Ort);

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }


    public function insertIntoFitnessstudio($Studio_ID, $F_Name, $Plz, $Strasse)
    {
        $sql = "INSERT INTO Fitnessstudio (Studio_ID, F_Name, Plz, Strasse) VALUES ('{$Studio_ID}','{$F_Name}','{$Plz}','{$Strasse}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }


    public function deleteFitnessstudio($Studio_ID)
    {

        $errorcode = 0;


        $sql = "DELETE FROM Fitnessstudio WHERE Studio_ID = :Studio_ID";

        $statement = oci_parse($this->conn, $sql);
        oci_bind_by_name($statement, ":Studio_ID", $Studio_ID);
        if (!oci_execute($statement)) {
            $errorcode = 1;
        }elseif(oci_num_rows($statement)==0){
            $errorcode = 2;
        }
        oci_commit($this->conn);
        oci_free_statement($statement);
        return $errorcode;
    }

    public function updateFitnessstudio($column, $value, $rowId)
    {
        $fit_id = $rowId[0];

        $columns = ['STUDIO_ID', 'F_NAME', 'PLZ', 'STRASSE'];
        $sql = "UPDATE Fitnessstudio SET {$columns[$column]} = :value WHERE STUDIO_ID = :id";

        if (!isset($columns[$column])) {
            echo "Invalid column index";
            return;
        }

        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':value', $value);
        oci_bind_by_name($stmt, ':id', $fit_id);

        if (oci_execute($stmt)) {
            echo "Update 2 successful";
        } else {
            $e = oci_error($stmt);
            echo "Update failed " . $e['message'];
        }

        oci_free_statement($stmt);
    }



    public function insertIntoMitarbeiter($Mitarbeiter_ID, $Studio_ID, $Vorname, $Nachname){
        $sql = "INSERT INTO Mitarbeiter (Mitarbeiter_ID, Studio_ID, Vorname, Nachname) VALUES ('{$Mitarbeiter_ID}','{$Studio_ID}','{$Vorname}','{$Nachname}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function deleteMitarbeiter_($Mitarbeiter_ID){
        $errorcode = 0;


        $sql = "DELETE FROM Mitarbeiter WHERE Mitarbeiter_ID = '{$Mitarbeiter_ID}'";

        $statement = oci_parse($this->conn, $sql);
        oci_bind_by_name($statement, ":Mitarbeiter_ID", $Studio_ID);
        if (!oci_execute($statement)) {
            $errorcode = 1;
        }elseif(oci_num_rows($statement)==0){
            $errorcode = 2;
        }
        oci_commit($this->conn);
        oci_free_statement($statement);
        return $errorcode;
    }

    public function updateMitarbeiter_($column, $value, $rowId){
        $columns = ['Mitarbeiter_ID', 'Studio_ID', 'Vorname', 'Nachname'];

        $mit_id = $rowId[0];

        $sql = "UPDATE MITARBEITER SET {$columns[$column]} = :value WHERE MITARBEITER_ID = :id";

        if (!isset($columns[$column])) {
            echo "Invalid column index";
            return;
        }

        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':value', $value);
        oci_bind_by_name($stmt, ':id', $mit_id);

        if (oci_execute($stmt)) {
            echo "Update 2 successful";
        } else {
            $e = oci_error($stmt);
            echo "Update failed " . $e['message'];
        }

        oci_free_statement($stmt);
    }

    public function insertIntoPersonalTrainer($Mitarbeiter_ID,$Geschlecht, $Spezialisierung){
        $sql = "INSERT INTO PERSONAL_TRAINER (Mitarbeiter_ID, GESCHLECHT, SPEZIALISIERUNG) VALUES ('{$Mitarbeiter_ID}','{$Geschlecht}','{$Spezialisierung}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function updatePersonalTrainer_($column, $value, $rowId){
        $columns = ['Mitarbeiter_ID', 'Geschlecht', 'Spezialisierung'];

        $mit_id = $rowId[0];

        $sql = "UPDATE PERSONAL_TRAINER SET {$columns[$column]} = :value WHERE MITARBEITER_ID = :id";

        if (!isset($columns[$column])) {
            echo "Invalid column index";
            return;
        }

        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':value', $value);
        oci_bind_by_name($stmt, ':id', $mit_id);

        if (oci_execute($stmt)) {
            echo "Update 2 successful";
        } else {
            $e = oci_error($stmt);
            echo "Update failed " . $e['message'];
        }

        oci_free_statement($stmt);
    }

    public function insertIntoRezeptionist($Mitarbeiter_ID,$Arbeitszeiten, $SprichtEnglisch){
        $sql = "INSERT INTO REZEPTIONIST (MITARBEITER_ID,ARBEITSZEITEN,SPRICHTENGLISCH) VALUES ('{$Mitarbeiter_ID}','{$Arbeitszeiten}','{$SprichtEnglisch}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function updateRezeptionist_($column, $value, $rowId)
    {
        $columns = ['Mitarbeiter_ID', 'Arbeitszeiten', 'SprichtEnglisch'];

        $mit_id = $rowId[0];

        $sql = "UPDATE REZEPTIONIST SET {$columns[$column]} = :value WHERE MITARBEITER_ID = :id";

        if (!isset($columns[$column])) {
            echo "Invalid column index";
            return;
        }

        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':value', $value);
        oci_bind_by_name($stmt, ':id', $mit_id);

        if (oci_execute($stmt)) {
            echo "Update 2 successful";
        } else {
            $e = oci_error($stmt);
            echo "Update failed " . $e['message'];
        }

        oci_free_statement($stmt);
    }

    public function insertIntoKunde($Kundennummer, $Studio_ID, $Vorname, $Nachname, $Geschlecht){
        $sql = "INSERT INTO KUNDE (KUNDENNUMMER,STUDIO_ID,VORNAME, NACHNAME, GESCHLECHT) 
        VALUES ('{$Kundennummer}','{$Studio_ID}','{$Vorname}', '{$Nachname}','{$Geschlecht}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function deleteKunde_($Kundennummer){
        $errorcode = 0;

        $sql = "DELETE FROM KUNDE WHERE KUNDENNUMMER = '{$Kundennummer}'";

        $statement = oci_parse($this->conn, $sql);
        oci_bind_by_name($statement, ":Kundennummer", $Kundennummer);

        if (!oci_execute($statement)) {
            $errorcode = 1;
        }elseif(oci_num_rows($statement)==0){
            $errorcode = 2;
        }
        oci_commit($this->conn);
        oci_free_statement($statement);
        return $errorcode;
    }

    public function updateKunde_($column, $value, $rowId){
        $columns = ['Kundennummer', 'Studio_ID', 'Vorname', 'Nachname', 'Geschlecht'];

        $kund_id = $rowId[0];

        $sql = "UPDATE KUNDE SET {$columns[$column]} = :value WHERE KUNDENNUMMER = :id";

        if (!isset($columns[$column])) {
            echo "Invalid column index";
            return;
        }

        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':value', $value);
        oci_bind_by_name($stmt, ':id', $kund_id);

        if (oci_execute($stmt)) {
            echo "Update 2 successful";
        } else {
            $e = oci_error($stmt);
            echo "Update failed " . $e['message'];
        }

        oci_free_statement($stmt);
    }

    public function insertIntoCoacht($Mitarbeiter_ID, $Kundennummer, $Beginnzeit, $Endzeit)
    {
        $f_beginnzeit = date('Y-m-d H:i:s', strtotime($Beginnzeit));
        $f_endzeit = date('Y-m-d H:i:s', strtotime($Endzeit));


        $str_beg = strval($f_beginnzeit);
        $str_end = strval($f_endzeit);


        $sql = "INSERT INTO COACHT (MITARBEITER_ID, KUNDENNUMMER, BEGINNZEIT, ENDZEIT) 
        VALUES ('{$Mitarbeiter_ID}',
               '{$Kundennummer}',
        TO_TIMESTAMP('{$str_beg}', 'YYYY-MM-DD HH24:MI:SS'),
        TO_TIMESTAMP('{$str_end}', 'YYYY-MM-DD HH24:MI:SS'))";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function deleteCoacht_($Mitarbeiter_ID, $Kundennummer, $Beginnzeit, $Endzeit){

        $f_beginnzeit = date('Y-m-d H:i:s', strtotime($Beginnzeit));
        $f_endzeit = date('Y-m-d H:i:s', strtotime($Endzeit));

        $str_beg = strval($f_beginnzeit);
        $str_end = strval($f_endzeit);

        $errorcode = 0;

        $sql = "DELETE FROM COACHT 
       WHERE MITARBEITER_ID  = '{$Mitarbeiter_ID}'
       AND KUNDENNUMMER = '{$Kundennummer}'
       AND BEGINNZEIT = TO_TIMESTAMP('{$str_beg}', 'YYYY-MM-DD HH24:MI:SS')
       AND ENDZEIT = TO_TIMESTAMP('{$str_end}', 'YYYY-MM-DD HH24:MI:SS')";

        $statement = oci_parse($this->conn, $sql);
        if (!oci_execute($statement)) {
            $errorcode = 1;
        }elseif(oci_num_rows($statement)==0){
            $errorcode = 2;
        }
        oci_commit($this->conn);
        oci_free_statement($statement);
        return $errorcode;


    }


    public function updateCoacht_($column, $value, $rowId){
        $columns = ['MITARBEITER_ID', 'KUNDENNUMMER', 'BEGINNZEIT', 'ENDZEIT'];

        $mit_id = $rowId[0];
        $kund_id = $rowId[1];
        $beginnzeit = $rowId[2];
        $endzeit= $rowId[3];

        echo $mit_id;
        echo $beginnzeit;
        echo $endzeit;

        if (!isset($columns[$column])) {
            echo "Invalid column index";
            return;
        }

        $isDateColumn = in_array($columns[$column], ['BEGINNZEIT', 'ENDZEIT']);

        if ($isDateColumn) {
            $dateFormat = ($columns[$column] === 'TRAININGSDATUM') ? date('Y-m-d', strtotime($value)) : date('Y-m-d H:i:s', strtotime($value));
            $str_dat = strval($dateFormat);
            $endValue =  ($columns[$column] === 'TRAININGSDATUM') ?  "TO_DATE('{$str_dat}', 'YYYY-MM-DD')" : "TO_TIMESTAMP('{$str_dat}', 'YYYY-MM-DD HH24:MI:SS')";
        } else {
            $endValue = $value;
        }

        echo $endValue;

        $sql = "UPDATE COACHT SET {$columns[$column]} = {$endValue} WHERE MITARBEITER_ID = '{$mit_id}'";

        if('KUNDENNUMMER' !== $columns[$column]) {
            $sql .= " AND KUNDENNUMMER = '{$kund_id}'";
        }

        if ('BEGINNZEIT' !== $columns[$column]) {
            $f_beginnzeit = date('Y-m-d H:i:s', strtotime($beginnzeit));
            echo "beginnzeit" . $f_beginnzeit .'\n';
            $str_beg = strval($f_beginnzeit);
            echo "endzeit" . $str_beg .'\n';
            $sql .= " AND BEGINNZEIT = TO_TIMESTAMP('{$str_beg}', 'YYYY-MM-DD HH24:MI:SS')";
        }

        if ('ENDZEIT' !== $columns[$column]) {
            $f_endzeit = date('Y-m-d H:i:s', strtotime($endzeit));
            $str_end = strval($f_endzeit);
            $sql .= " AND ENDZEIT = TO_TIMESTAMP('{$str_end}', 'YYYY-MM-DD HH24:MI:SS')";
        }

        echo $sql;

        $stmt = oci_parse($this->conn, $sql);


        if (oci_execute($stmt)) {
            echo "Update successful";
        } else {
            $e = oci_error($stmt);
            echo "Update failed: " . $e['message'];
        }

        oci_free_statement($stmt);
    }


    public function insertIntoBetreut($Mitarbeiter_ID, $Kundennummer, $Zeitpunkt)
    {
        $f_beginnzeit = date('Y-m-d H:i:s', strtotime($Zeitpunkt));

        $str_beg = strval($f_beginnzeit);

        $sql = "INSERT INTO BETREUT (MITARBEITER_ID, KUNDENNUMMER, ZEITPUNKT) 
        VALUES ('{$Mitarbeiter_ID}','{$Kundennummer}', TO_TIMESTAMP('{$str_beg}', 'YYYY-MM-DD HH24:MI:SS'))";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function deleteBetreut_($Mitarbeiter_ID, $Kundennummer, $Zeitpunkt){
        $errorcode = 0;

        $f_beginnzeit = date('Y-m-d H:i:s', strtotime($Zeitpunkt));
        $str_beg = strval($f_beginnzeit);

        $sql = "DELETE FROM BETREUT
        WHERE MITARBEITER_ID  = '{$Mitarbeiter_ID}'
        AND KUNDENNUMMER = '{$Kundennummer}'
        AND ZEITPUNKT = TO_TIMESTAMP('{$str_beg}', 'YYYY-MM-DD HH24:MI:SS')";

        $statement = oci_parse($this->conn, $sql);
        if (!oci_execute($statement)) {
            $errorcode = 1;
        }elseif(oci_num_rows($statement)==0){
            $errorcode = 2;
        }
        oci_commit($this->conn);
        oci_free_statement($statement);
        return $errorcode;
    }

    public function updateBetreut_($column, $value, $rowId, $originalValue){


        $columns = ['MITARBEITER_ID', 'KUNDENNUMMER', 'ZEITPUNKT'];

        $mit_id = $rowId[0];
        $kund_id = $rowId[1];
        $zeit = $rowId[2];

        echo "value:" . $value;


        if (!isset($columns[$column])) {
            echo "Invalid column index";
            return;
        }

        $isDateColumn = $columns[$column] == 'ZEITPUNKT';

        if ($isDateColumn) {
            $dateFormat = date('Y-m-d H:i:s', strtotime($value));
            echo $dateFormat;
            $str_dat = strval($dateFormat);
            $endValue = "TO_TIMESTAMP('{$str_dat}', 'YYYY-MM-DD HH24:MI:SS')";
        } else {
            $endValue = $value;
        }

        $sql = "UPDATE BETREUT SET {$columns[$column]} = {$endValue} WHERE MITARBEITER_ID = '{$mit_id}'";


        if('KUNDENNUMMER' !== $columns[$column]) {
            $sql .= " AND KUNDENNUMMER = '{$kund_id}'";
        }


        if ('ZEITPUNKT' !== $columns[$column]) {
            $frmt = date('Y-m-d H:i:s', strtotime($zeit));
            $str_dat = strval($frmt);
            $sql .= " AND ZEITPUNKT = TO_TIMESTAMP('{$str_dat}', 'YYYY-MM-DD HH24:MI:SS')";
        }
        else{
            $frmt = date('Y-m-d H:i:s', strtotime($originalValue));
            $str_dat = strval($frmt);
            $sql .= " AND ZEITPUNKT = TO_TIMESTAMP('{$str_dat}', 'YYYY-MM-DD HH24:MI:SS')";
        }


        echo $sql;

        $stmt = oci_parse($this->conn, $sql);

        if (oci_execute($stmt)) {
            echo "Update successful";
        } else {
            $e = oci_error($stmt);
            echo "Update failed: " . $e['message'];
        }

        oci_free_statement($stmt);
    }

    public function insertIntoMGS($Mitgliedschafts_Stufe, $Monatskosten)
    {


        $sql = "INSERT INTO MITGLIEDSCHAFTS_STUFE (STUFE, MONATSKOSTEN) 
        VALUES ('{$Mitgliedschafts_Stufe}',
                '{$Monatskosten}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }


    public function insertIntoMG($Kundennummer, $Mitgliedschaftsnummer, $Stufe,
    $Gueltigkeit, $Erstellungsdatum)
    {

        $f_erstellungsdatum = date('Y-m-d', strtotime($Erstellungsdatum));

        $str_dat = strval($f_erstellungsdatum);

        $sql = "INSERT INTO MITGLIEDSCHAFT (KUNDENNUMMER, MITGLIEDSCHAFTSNUMMER, STUFE, GUELTIGKEIT, ERSTELLUNGSDATUM) 
        VALUES ('{$Kundennummer}',
                '{$Mitgliedschaftsnummer}',
                '{$Stufe}',
                '{$Gueltigkeit}',
        TO_DATE('{$str_dat}', 'YYYY-MM-DD'))";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function deleteMG_($Kundennummer, $Mitgliedschaftsnummer){
        $errorcode = 0;

        $sql = "DELETE FROM MITGLIEDSCHAFT
       WHERE KUNDENNUMMER  = '{$Kundennummer}'
       AND MITGLIEDSCHAFTSNUMMER = '{$Mitgliedschaftsnummer}'";

        $statement = oci_parse($this->conn, $sql);
        if (!oci_execute($statement)) {
            $errorcode = 1;
        }elseif(oci_num_rows($statement)==0){
            $errorcode = 2;
        }
        oci_commit($this->conn);
        oci_free_statement($statement);
        return $errorcode;
    }

    public function updateMG_($column, $value, $rowId){
        $columns = ['KUNDENNUMMER' , 'MITGLIEDSCHAFTSNUMMER', 'STUFE',
            'GUELTIGKEIT', 'ERSTELLUNGSDATUM'];

        $kund_id = $rowId[0];
        $mg_id = $rowId[1];



        if (!isset($columns[$column])) {
            echo "Invalid column index";
            return;
        }

        $isDateColumn = $columns[$column] == 'ERSTELLUNGSDATUM';

        if ($isDateColumn) {
            $dateFormat = date('Y-m-d', strtotime($value));
            $str_dat = strval($dateFormat);
            $endValue = "TO_DATE('{$str_dat}', 'YYYY-MM-DD')";
        } else {
            $endValue = "'$value'";
        }


        $sql = "UPDATE MITGLIEDSCHAFT SET {$columns[$column]} = {$endValue} WHERE KUNDENNUMMER = :id";

        if('MITGLIEDSCHAFTSNUMMER' !== $columns[$column]) {
            $sql .= " AND MITGLIEDSCHAFTSNUMMER = '{$mg_id}'";
        }


        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':id', $kund_id);

        if (oci_execute($stmt)) {
            echo "Update 2 successful";
        } else {
            $e = oci_error($stmt);
            echo "Update failed " . $e['message'];
        }

        oci_free_statement($stmt);
    }

    public function insertIntoKon($Mitarbeiter_ID, $Kundennummer, $Mitgliedschaftsnummer, $Zeitpunkt)
    {
        $f_beginnzeit = date('Y-m-d H:i:s', strtotime($Zeitpunkt));

        $str_beg = strval($f_beginnzeit);


        $sql = "INSERT INTO KONTROLLIERT (MITARBEITER_ID, KUNDENNUMMER, MITGLIEDSCHAFTSNUMMER, ZEITPUNKT) 
        VALUES ('{$Mitarbeiter_ID}',
                '{$Kundennummer}',
                '{$Mitgliedschaftsnummer}',
                TO_TIMESTAMP('{$str_beg}', 'YYYY-MM-DD HH24:MI:SS'))";

        $statement = oci_parse($this->conn, $sql);
        if (oci_execute($statement)) {
            echo "Update successful";
        } else {
            $e = oci_error($statement);
            echo "Update failed: " . $e['message'];
        }
        oci_free_statement($statement);
    }

    public function deleteKon_($Mitarbeiter_ID, $Kundennummer, $Mitgliedschaftsnummer, $Zeitpunkt){
        $errorcode = 0;

        $f_beginnzeit = date('Y-m-d H:i:s', strtotime($Zeitpunkt));

        $str_beg = strval($f_beginnzeit);

        $sql = "DELETE FROM KONTROLLIERT
        WHERE MITARBEITER_ID = '{$Mitarbeiter_ID}'
        AND KUNDENNUMMER  = '{$Kundennummer}'
        AND MITGLIEDSCHAFTSNUMMER = '{$Mitgliedschaftsnummer}'
        AND ZEITPUNKT = TO_TIMESTAMP('{$str_beg}', 'YYYY-MM-DD HH24:MI:SS') 
        ";

        $statement = oci_parse($this->conn, $sql);
        if (!oci_execute($statement)) {
            $errorcode = 1;
        }elseif(oci_num_rows($statement)==0){
            $errorcode = 2;
        }
        oci_commit($this->conn);
        oci_free_statement($statement);
        return $errorcode;
    }

    public function updateKon_($column, $value, $rowId, $originalValue){
        $columns = ['MITARBEITER_ID', 'KUNDENNUMMER', 'MITGLIEDSCHAFTSNUMMER', 'ZEITPUNKT'];

        $mit_id = $rowId[0];
        $kund_id = $rowId[1];
        $mit_num = $rowId[2];
        $zeit = $rowId[3];

        if (!isset($columns[$column])) {
            echo "Invalid column index";
            return;
        }

        $isDateColumn = $columns[$column] == 'ZEITPUNKT';

        if ($isDateColumn) {
            $dateFormat = date('Y-m-d H:i:s', strtotime($value));
            $str_dat = strval($dateFormat);
            $endValue = "TO_TIMESTAMP('{$str_dat}', 'YYYY-MM-DD HH24:MI:SS')";
        } else {
            $endValue = "'$value'";
        }


        $sql = "UPDATE KONTROLLIERT SET {$columns[$column]} = {$endValue} WHERE MITARBEITER_ID = '{$mit_id}'";

        if('KUNDENNUMMER' !== $columns[$column]) {
            $sql .= " AND KUNDENNUMMER = '{$kund_id}'";
        }

        if('MITGLIEDSCHAFTSNUMMER' != $columns[$column]){
            $sql .= " AND MITGLIEDSCHAFTSNUMMER = '{$mit_num}'";
        }

        if ('ZEITPUNKT' !== $columns[$column]) {
            $frmt = date('Y-m-d H:i:s', strtotime($zeit));
            $str_dat = strval($frmt);
            $sql .= " AND ZEITPUNKT = TO_TIMESTAMP('{$str_dat}', 'YYYY-MM-DD HH24:MI:SS')";
        }
        else{
            $frmt = date('Y-m-d H:i:s', strtotime($originalValue));
            $str_dat = strval($frmt);
            $sql .= " AND ZEITPUNKT = TO_TIMESTAMP('{$str_dat}', 'YYYY-MM-DD HH24:MI:SS')";
        }




        $stmt = oci_parse($this->conn, $sql);

        if (oci_execute($stmt)) {
            echo "Update successful";
        } else {
            $e = oci_error($stmt);
            echo "Update failed: " . $e['message'];
        }

        oci_free_statement($stmt);
    }

    public function insertIntoTraining($Kundennummer1, $Kundennummer2, $Zeitpunkt)
    {
        $f_beginnzeit = date('Y-m-d H:i:s', strtotime($Zeitpunkt));

        $str_beg = strval($f_beginnzeit);

        $sql = "INSERT INTO Trainiert_mit (KUNDENNUMMER1, KUNDENNUMMER2, ZEITPUNKT) 
        VALUES ('{$Kundennummer1}','{$Kundennummer2}', TO_TIMESTAMP('{$str_beg}', 'YYYY-MM-DD HH24:MI:SS'))";

        $statement = oci_parse($this->conn, $sql);
        if (oci_execute($statement)) {
            echo "Update successful";
        } else {
            $e = oci_error($statement);
            echo "Update failed: " . $e['message'];
        }
        oci_free_statement($statement);
    }

    public function deleteTraining_($Kundennummer1, $Kundennummer2, $Zeitpunkt){
        $errorcode = 0;

        $f_beginnzeit = date('Y-m-d H:i:s', strtotime($Zeitpunkt));
        $str_beg = strval($f_beginnzeit);

        $sql = "DELETE FROM Trainiert_mit
        WHERE Kundennummer1 = '{$Kundennummer1}'
        AND Kundennummer2 = '{$Kundennummer2}'
        AND ZEITPUNKT = TO_TIMESTAMP('{$str_beg}', 'YYYY-MM-DD HH24:MI:SS')";


        $statement = oci_parse($this->conn, $sql);
        if (!oci_execute($statement)) {
            $errorcode = 1;
        }elseif(oci_num_rows($statement)==0){
            $errorcode = 2;
        }
        oci_commit($this->conn);
        oci_free_statement($statement);
        return $errorcode;
    }

    public function updateTraining_($column, $value, $rowId, $originalValue){
        $columns = ['KUNDENNUMMER1', 'KUNDENNUMMER2', 'ZEITPUNKT'];

        $kund_id1 = $rowId[0];
        $kund_id2 = $rowId[1];
        $zeit = $rowId[2];


        if (!isset($columns[$column])) {
            echo "Invalid column index";
            return;
        }

        $isDateColumn = $columns[$column] == 'ZEITPUNKT';

        if ($isDateColumn) {
            $dateFormat = date('Y-m-d H:i:s', strtotime($value));
            $str_dat = strval($dateFormat);
            $endValue = "TO_TIMESTAMP('{$str_dat}', 'YYYY-MM-DD HH24:MI:SS')";
        } else {
            $endValue = "'$value'";
        }


        $sql = "UPDATE TRAINIERT_MIT SET {$columns[$column]} = {$endValue} WHERE KUNDENNUMMER1 = '{$kund_id1}'";

        if('KUNDENNUMMER2' !== $columns[$column]){
            $sql .= " AND KUNDENNUMMER2 = '{$kund_id2}'";
        }

        if ('ZEITPUNKT' !== $columns[$column]) {
            $frmt = date('Y-m-d H:i:s', strtotime($zeit));
            $str_dat = strval($frmt);
            $sql .= " AND ZEITPUNKT = TO_TIMESTAMP('{$str_dat}', 'YYYY-MM-DD HH24:MI:SS')";
        }
        else{
            $frmt = date('Y-m-d H:i:s', strtotime($originalValue));
            $str_dat = strval($frmt);
            $sql .= " AND ZEITPUNKT = TO_TIMESTAMP('{$str_dat}', 'YYYY-MM-DD HH24:MI:SS')";
        }

        $stmt = oci_parse($this->conn, $sql);


        if (oci_execute($stmt)) {
            echo "Update successful";
        } else {
            $e = oci_error($stmt);
            echo "Update failed: " . $e['message'];
        }

        oci_free_statement($stmt);
    }
    public function GetMembershipDetails($Kundennummer) {

        $validity = '';

        $sql = 'BEGIN GetMembershipDetails(:customerID, :membership_number, :validity); END;';
        $stmt = oci_parse($this->conn, $sql);

        oci_bind_by_name($stmt, ':customerID', $Kundennummer);
        oci_bind_by_name($stmt, ':membership_number', $membershipNumber, -1, SQLT_INT);
        oci_bind_by_name($stmt, ':validity',$validity,50);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            echo "Error executing stored procedure: " . $e['message'];
        }

        // Fetch the results
        oci_free_statement($stmt);

        return [
            'MITGLIEDSCHAFTSNUMMER' => $membershipNumber,
            'GUELTIGKEIT' => $validity,
        ];


    }
}