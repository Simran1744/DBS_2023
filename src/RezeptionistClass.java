import com.opencsv.CSVReader;
import java.sql.*;
import java.util.Random;
import java.io.FileReader;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.ArrayList;

public class RezeptionistClass {

    private Connection connection;

    public RezeptionistClass(Connection connection) {
        this.connection = connection;
    }
    public ArrayList<Integer> insertIntoRezeptionistCSV(String csvFilePath, ArrayList<Integer> mitIDs) {

        ArrayList<Integer> newIDs = new ArrayList<>();
        newIDs = mitIDs;



        try {
            String rezInsert = "INSERT INTO REZEPTIONIST VALUES (?,?,?)";
            PreparedStatement stmt = connection.prepareStatement(rezInsert);
            connection.setAutoCommit(false);

            try (CSVReader reader = new CSVReader(new FileReader(csvFilePath))) {
                String[] nextLine;

                reader.readNext();

                while ((nextLine = reader.readNext()) != null) {
                    String lang = nextLine[0];
                    String time = nextLine[1];

                    Random r = new Random();

                    int randomIndex = r.nextInt(newIDs.size());
                    int mit_ID = newIDs.get(randomIndex);

                    stmt.setInt(1, mit_ID);
                    stmt.setString(2, time);
                    stmt.setString(3, lang);
                    stmt.addBatch();

                    newIDs.remove(randomIndex);

                }

                int[] result = stmt.executeBatch();
                System.out.println("The number of rows inserted into Rezeptionist: " + result.length);

                connection.commit();
            }
        } catch (SQLException | NumberFormatException e) {
            e.printStackTrace();
            try {
                connection.rollback();
            } catch (SQLException ex) {
                ex.printStackTrace();
            }
        } catch (Exception e) {
            e.printStackTrace();
        }

        return newIDs;
    }

    public ArrayList<Integer> getAllRezeptionistIds() {
        ArrayList<Integer> mitIDs = new ArrayList<>();

        try {
            connection.setAutoCommit(false);
            String sqlQuery = "SELECT MITARBEITER_ID FROM REZEPTIONIST";
            try (PreparedStatement stmt = connection.prepareStatement(sqlQuery);
                 ResultSet rs = stmt.executeQuery()) {

                while (rs.next()) {
                    int mitID = rs.getInt("Mitarbeiter_ID");
                    mitIDs.add(mitID);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return mitIDs;
    }


}
