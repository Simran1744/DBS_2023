import java.sql.*;
public class JavaSqlExample {
    public static void main(String args[]) {
        try {
            // Loads the class "oracle.jdbc.driver.OracleDriver" into the memory
            Class.forName("oracle.jdbc.driver.OracleDriver");

            // Connection details
            String database = "jdbc:oracle:thin:@oracle19.cs.univie.ac.at:1521:orclcdb";
            String user = "a12224337";
            String pass = "dbs77";

            // Establish a connection to the database
            Connection con = DriverManager.getConnection(database, user, pass);
            Statement stmt = con.createStatement();

            // Insert a single dataset into the table "person"
            try {
                String insertSql = "INSERT INTO Fitnessstudio VALUES (102,'Fitinn','Wien', 1100, 'Columbusplatz 7/8')";

//executeUpdate Method: Executes the SQL statement, which can be an INSERT, UPDATE, or DELETE statement
                int rowsAffected = stmt.executeUpdate(insertSql);
            } catch (Exception e) {
                System.err.println("Error while executing INSERT INTO statement: " + e.getMessage());
            }

// Check number of datasets in person table
            ResultSet rs = stmt.executeQuery("SELECT COUNT(*) FROM Fitnessstudio");
            if (rs.next()) {
                int count = rs.getInt(1);
                System.out.println("Number of datasets: " + count);
            }

            // Clean up connections
            rs.close();
            stmt.close();
            con.close();
        } catch (Exception e) {
            System.err.println(e.toString());
        }
    }
}
