import java.sql.*;

public class InsertClass {
    public static void main(String args[]) {
        try {
            Class.forName("oracle.jdbc.driver.OracleDriver");

            // Connection details
            String database = "jdbc:oracle:thin:@oracle19.cs.univie.ac.at:1521:orclcdb";
            String user = "a12224337";
            String pass = "dbs77";

            // Establish a connection to the database
            Connection connection = DriverManager.getConnection(database, user, pass);

            String mitInsert = "INSERT INTO Mitarbeiter VALUES (?,?,?,?)";
            String sqlQuery = "INSERT INTO Fitnessstudio VALUES (?,?,?,?,?)";
            PreparedStatement pstmt = connection.prepareStatement(sqlQuery);
            PreparedStatement mitstmt = connection.prepareStatement(mitInsert);
            connection.setAutoCommit(false);

            try {

                for (int i = 210; i <= 220; i++) {
                    pstmt.setInt(1, i);
                    pstmt.setString(2, "Fitinn");
                    pstmt.setString(3, "Wien");
                    pstmt.setString(4, "1230");
                    pstmt.setString(5, "Columbusplatz 7/8");
                    pstmt.addBatch();
                }

                for (int i = 1; i <= 200; i++){
                    mitstmt.setInt(1, 0);
                    mitstmt.setInt(2,180);
                    mitstmt.setString(3,"Simran");
                    mitstmt.setString(4,"King");
                    mitstmt.addBatch();
                }

                int[] result = pstmt.executeBatch();
                int[] result2 = mitstmt.executeBatch();
                System.out.println("The number of rows inserted: " + result.length);
                System.out.println("The number of rows inserted into Mitarbeiter: " + result2.length);
                connection.commit();

            } catch (Exception e) {

                e.printStackTrace();
                connection.rollback();

            } finally {
                if (pstmt != null) {
                    pstmt.close();
                }
                if(mitstmt != null){
                    mitstmt.close();
                }
                if(connection != null){
                    connection.close();
                }
            }
        }catch(Exception e){
            System.err.println(e.toString());
        }
    }
}

