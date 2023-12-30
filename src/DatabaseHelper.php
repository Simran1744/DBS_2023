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
    public function selectAllFitnessstudio($Studio_ID, $F_Name, $Ort, $Platz, $Strasse)
    {
        // Define the sql statement string
        // Notice that the parameters $person_id, $surname, $name in the 'WHERE' clause
        $sql = "SELECT * FROM Fitnessstudio
            WHERE Studio_ID LIKE '%{$Studio_ID}%'
              AND upper(f_name) LIKE upper('%{$F_Name}%')
              AND upper(ort) LIKE upper('%{$Ort}%')
              AND upper(platz) LIKE upper('%{$Platz}%')
              AND upper(strasse) LIKE upper('%{$Strasse}%')";


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

    public function selectAllPersonalTrainer($Mitarbeiter_ID, $Geschlecht, $Sprachkenntnisse){

        $sql = "SELECT * FROM PERSONAL_TRAINER
            WHERE MITARBEITER_ID LIKE '%{$Mitarbeiter_ID}%'
              AND upper(GESCHLECHT) LIKE upper('%{$Geschlecht}%')
              AND upper(SPRACHKENNTNISSE) LIKE upper('%{$Sprachkenntnisse}%')";
        $statement = oci_parse($this->conn, $sql);

        // Executes the statement
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function selectAllRezeptionist($Mitarbeiter_ID, $Arbeitszeiten, $Sprachkenntnisse){

        $sql = "SELECT * FROM REZEPTIONIST
            WHERE MITARBEITER_ID LIKE '%{$Mitarbeiter_ID}%'
              AND upper(ARBEITSZEITEN) LIKE upper('%{$Arbeitszeiten}%')
              AND upper(SPRACHKENNTNISSE) LIKE upper('%{$Sprachkenntnisse}%')";
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
              AND upper(NACHNAME) LIKE upper('%{$Nachname}%')
              AND upper(GESCHLECHT) LIKE upper('%{$Geschlecht}%')";
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
            WHERE MITARBEITER_ID =  '{$Mitarbeiter_ID}'
            AND KUNDENNUMMER = '{$Kundennummer}'
            AND BEGINNZEIT = TO_TIMESTAMP('{$str_beg}', 'YYYY-MM-DD HH24:MI:SS')
            AND ENDZEIT = TO_TIMESTAMP('{$str_end}', 'YYYY-MM-DD HH24:MI:SS')";
        $statement = oci_parse($this->conn, $sql);

        // Executes the statements
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function selectAllBetreut($Mitarbeiter_ID, $Kundennummer){


    $sql = "SELECT * FROM BETREUT
            WHERE upper(MITARBEITER_ID) LIKE upper('%{$Mitarbeiter_ID}%')
            AND upper(KUNDENNUMMER) LIKE upper('%{$Kundennummer}%')";

    $statement = oci_parse($this->conn, $sql);

    // Executes the statements
    oci_execute($statement);

    oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    //clean up;
    oci_free_statement($statement);

    return $res;
    }

    public function selectAllMGs($Kundennummer, $Mitgliedschaftsnummer, $Mitgliedschafts_Stufe, $Monatskosten, $Gueltigkeit, $Erstellungsdatum){

        $f_erstellungsdatum = date('Y-m-d', strtotime($Erstellungsdatum));

        $str_dat = strval($f_erstellungsdatum);

        $sql = "SELECT * FROM MITGLIEDSCHAFT
            WHERE upper(KUNDENNUMMER) LIKE upper('%{$Kundennummer}%')
            AND upper(MITGLIEDSCHAFTSNUMMER) LIKE upper('%{$Mitgliedschaftsnummer}%')
            AND upper(MITGLIEDSCHAFTS_STUFE) LIKE upper('%{$Mitgliedschafts_Stufe}%')
            AND upper(MONATSKOSTEN) LIKE upper('%{$Monatskosten}%')
            AND upper(GUELTIGKEIT) LIKE upper('{$Gueltigkeit}')
            AND ERSTELLUNGSDATUM LIKE upper(TO_DATE(('{$str_dat}'), 'YYYY-MM-DD'))
            
            ";

        $statement = oci_parse($this->conn, $sql);

        // Executes the statements
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }
    public function selectAllKons($Mitarbeiter_ID, $Kundennummer, $Mitgliedschaftsnummer){


        $sql = "SELECT * FROM KONTROLLIERT
            WHERE upper(MITARBEITER_ID) LIKE upper('{$Mitarbeiter_ID}')
            AND upper(KUNDENNUMMER) LIKE upper('{$Kundennummer}')
            AND upper(MITGLIEDSCHAFTSNUMMER) LIKE upper('{$Mitgliedschaftsnummer}')       
            ";

        $statement = oci_parse($this->conn, $sql);

        // Executes the statements
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }





    // This function creates and executes a SQL insert statement and returns true or false
    public function insertIntoFitnessstudio($Studio_ID, $F_Name, $Ort, $Platz, $Strasse)
    {
        $sql = "INSERT INTO Fitnessstudio (Studio_ID, F_Name, Ort, Platz, Strasse) VALUES ('{$Studio_ID}','{$F_Name}','{$Ort}','{$Platz}','{$Strasse}')";

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
        $columns = ['STUDIO_ID', 'F_NAME', 'ORT', 'PLATZ', 'STRASSE'];
        $sql = "UPDATE Fitnessstudio SET {$columns[$column]} = :value WHERE STUDIO_ID = :id";

        if (!isset($columns[$column])) {
            echo "Invalid column index";
            return;
        }

        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':value', $value);
        oci_bind_by_name($stmt, ':id', $rowId);

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

    public function deleteMitarbeiter($Mitarbeiter_ID){
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

    public function updateMitarbeiter($Mitarbeiter_ID, $New_Mitarbeiter_ID, $Studio_ID, $Vorname, $Nachname){
        $setClauses = [];

        // Check each parameter and add it to the SET clause if it's provided
        if($New_Mitarbeiter_ID != null){
            $setClauses[] = "Mitarbeiter_ID = '{$New_Mitarbeiter_ID}'";
        }
        if ($Studio_ID != null) {
            $setClauses[] = "Studio_ID = '{$Studio_ID}'";
        }
        if ($Vorname != null) {
            $setClauses[] = "Vorname = '{$Vorname}'";
        }
        if ($Nachname != null) {
            $setClauses[] = "Nachname = '{$Nachname}'";
        }

        // If no parameters were provided, return false as nothing to update
        if (empty($setClauses)) {
            return false;
        }

        // Construct the SQL query with the dynamic SET clause
        $setClause = implode(', ', $setClauses);
        $sql = "UPDATE MITARBEITER SET {$setClause} WHERE MITARBEITER_ID = '{$Mitarbeiter_ID}'";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);

        return $success;
    }

    public function insertIntoPersonalTrainer($Mitarbeiter_ID,$Geschlecht, $Sprachkenntnisse){
        $sql = "INSERT INTO PERSONAL_TRAINER (Mitarbeiter_ID, GESCHLECHT, SPRACHKENNTNISSE) VALUES ('{$Mitarbeiter_ID}','{$Geschlecht}','{$Sprachkenntnisse}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function updatePersonalTrainer($Mitarbeiter_ID, $New_Mitarbeiter_ID, $Geschlecht, $Sprachkenntnisse){

        if($New_Mitarbeiter_ID != null){
            $setClauses[] = "Mitarbeiter_ID = '{$New_Mitarbeiter_ID}'";
        }
        if ($Geschlecht != null) {
            $setClauses[] = "Geschlecht = '{$Geschlecht}'";
        }
        if ($Sprachkenntnisse != null) {
            $setClauses[] = "Sprachkenntnisse = '{$Sprachkenntnisse}'";
        }

        // If no parameters were provided, return false as nothing to update
        if (empty($setClauses)) {
            return false;
        }

        // Construct the SQL query with the dynamic SET clause
        $setClause = implode(', ', $setClauses);
        $sql = "UPDATE PERSONAL_TRAINER SET {$setClause} WHERE MITARBEITER_ID = '{$Mitarbeiter_ID}'";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);

        return $success;
    }

    public function insertIntoRezeptionist($Mitarbeiter_ID,$Arbeitszeiten, $Sprachkenntnisse){
        $sql = "INSERT INTO REZEPTIONIST (MITARBEITER_ID,ARBEITSZEITEN,SPRACHKENNTNISSE) VALUES ('{$Mitarbeiter_ID}','{$Arbeitszeiten}','{$Sprachkenntnisse}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function updateRezeptionist_($Mitarbeiter_ID, $New_Mitarbeiter_ID, $Arbeitszeiten, $Sprachkenntnisse)
    {
        if($New_Mitarbeiter_ID != null){
            $setClauses[] = "Mitarbeiter_ID = '{$New_Mitarbeiter_ID}'";
        }
        if ($Arbeitszeiten != null) {
            $setClauses[] = "Arbeitszeiten = '{$Arbeitszeiten}'";
        }
        if ($Sprachkenntnisse != null) {
            $setClauses[] = "Sprachkenntnisse = '{$Sprachkenntnisse}'";
        }

        // If no parameters were provided, return false as nothing to update
        if (empty($setClauses)) {
            return false;
        }

        // Construct the SQL query with the dynamic SET clause
        $setClause = implode(', ', $setClauses);
        $sql = "UPDATE REZEPTIONIST SET {$setClause} WHERE MITARBEITER_ID = '{$Mitarbeiter_ID}'";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);

        return $success;
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

    public function updateKunde_($Kundennummer, $new_Kundennummer, $Studio_ID, $Vorname, $Nachname, $Geschlecht){
        if($new_Kundennummer != null){
            $setClauses[] = "Kundennummer = '{$new_Kundennummer}'";
        }
        if ($Studio_ID != null) {
            $setClauses[] = "Studio_ID = '{$Studio_ID}'";
        }
        if ($Vorname != null) {
            $setClauses[] = "Vorname = '{$Vorname}'";
        }

        if ($Nachname != null) {
            $setClauses[] = "Nachname = '{$Nachname}'";
        }

        if ($Geschlecht != null) {
            $setClauses[] = "Geschlecht = '{$Geschlecht}'";
        }

        // If no parameters were provided, return false as nothing to update
        if (empty($setClauses)) {
            return false;
        }

        // Construct the SQL query with the dynamic SET clause
        $setClause = implode(', ', $setClauses);
        $sql = "UPDATE KUNDE SET {$setClause} WHERE KUNDENNUMMER = '{$Kundennummer}'";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);

        return $success;
    }

    public function insertIntoCoacht($Mitarbeiter_ID, $Kundennummer, $Beginnzeit, $Endzeit, $Trainingsdatum)
    {
        $f_beginnzeit = date('Y-m-d H:i:s', strtotime($Beginnzeit));
        $f_endzeit = date('Y-m-d H:i:s', strtotime($Endzeit));
        $f_trainingsdatum = date('Y-m-d', strtotime($Trainingsdatum));

        $str_beg = strval($f_beginnzeit);
        $str_end = strval($f_endzeit);
        $str_dat = strval($f_trainingsdatum);

        $sql = "INSERT INTO COACHT (MITARBEITER_ID, KUNDENNUMMER, BEGINNZEIT, ENDZEIT, TRAININGSDATUM) 
        VALUES ('{$Mitarbeiter_ID}',
               '{$Kundennummer}',
        TO_TIMESTAMP('{$str_beg}', 'YYYY-MM-DD HH24:MI:SS'),
        TO_TIMESTAMP('{$str_end}', 'YYYY-MM-DD HH24:MI:SS'),
        TO_DATE('{$str_dat}', 'YYYY-MM-DD'))";

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

    public function insertIntoBetreut($Mitarbeiter_ID, $Kundennummer)
    {
        $sql = "INSERT INTO BETREUT (MITARBEITER_ID, KUNDENNUMMER) 
        VALUES ('{$Mitarbeiter_ID}','{$Kundennummer}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function deleteBetreut_($Mitarbeiter_ID, $Kundennummer){
        $errorcode = 0;

        $sql = "DELETE FROM BETREUT
       WHERE MITARBEITER_ID  = '{$Mitarbeiter_ID}'
       AND KUNDENNUMMER = '{$Kundennummer}'";

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

    public function insertIntoMG($Kundennummer, $Mitgliedschaftsnummer, $Mitgliedschafts_Stufe, $Monatskosten,
    $Gueltigkeit, $Erstellungsdatum)
    {

        $f_erstellungsdatum = date('Y-m-d', strtotime($Erstellungsdatum));

        $str_dat = strval($f_erstellungsdatum);

        $sql = "INSERT INTO MITGLIEDSCHAFT (KUNDENNUMMER, MITGLIEDSCHAFTSNUMMER, MITGLIEDSCHAFTS_STUFE, MONATSKOSTEN, GUELTIGKEIT, ERSTELLUNGSDATUM) 
        VALUES ('{$Kundennummer}',
                '{$Mitgliedschaftsnummer}',
                '{$Mitgliedschafts_Stufe}',
                '{$Monatskosten}',
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

    public function insertIntoKon($Mitarbeiter_ID, $Kundennummer, $Mitgliedschaftsnummer)
    {

        $sql = "INSERT INTO KONTROLLIERT (MITARBEITER_ID, KUNDENNUMMER, MITGLIEDSCHAFTSNUMMER) 
        VALUES ('{$Mitarbeiter_ID}',
                '{$Kundennummer}',
                '{$Mitgliedschaftsnummer}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function deleteKon_($Mitarbeiter_ID, $Kundennummer, $Mitgliedschaftsnummer){

        $errorcode = 0;

        $sql = "DELETE FROM KONTROLLIERT
        WHERE MITARBEITER_ID = '{$Mitarbeiter_ID}'
        AND KUNDENNUMMER  = '{$Kundennummer}'
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
}