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


        // oci_parse(...) prepares the Oracle statement for execution
        // notice the reference to the class variable $this->conn (set in the constructor)
        $statement = oci_parse($this->conn, $sql);

        // Executes the statement
        oci_execute($statement);

        // Fetches multiple rows from a query into a two-dimensional array
        // Parameters of oci_fetch_all:
        //   $statement: must be executed before
        //   $res: will hold the result after the execution of oci_fetch_all
        //   $skip: it's null because we don't need to skip rows
        //   $maxrows: it's null because we want to fetch all rows
        //   $flag: defines how the result is structured: 'by rows' or 'by columns'
        //      OCI_FETCHSTATEMENT_BY_ROW (The outer array will contain one sub-array per query row)
        //      OCI_FETCHSTATEMENT_BY_COLUMN (The outer array will contain one sub-array per query column. This is the default.)
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

    // Using a Procedure
    // This function uses a SQL procedure to delete a person and returns an errorcode (&errorcode == 1 : OK)
    public function deleteFitnessstudio($Studio_ID)
    {

        $errorcode = 0;

        // In our case the procedure P_DELETE_PERSON takes two parameters:
        //  1. person_id (IN parameter)
        //  2. error_code (OUT parameter)
        /*
        // The SQL string
        $sql = 'BEGIN P_DELETE_Fitnessstudio(:Studio_ID, :errorcode); END;';
        $statement = oci_parse($this->conn, $sql);

        //  Bind the parameters
        oci_bind_by_name($statement, ':Studio_ID', $Studio_ID);
        oci_bind_by_name($statement, ':errorcode', $errorcode);

        // Execute Statement
        oci_execute($statement);

        //Note: Since we execute COMMIT in our procedure, we don't need to commit it here.
        //@oci_commit($statement); //not necessary

        //Clean Up
        oci_free_statement($statement);
        */

        //$errorcode == 1 => success
        //$errorcode != 1 => Oracle SQL related errorcode;

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
}