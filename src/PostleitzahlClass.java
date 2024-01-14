import com.opencsv.CSVReader;
import java.sql.*;
import java.util.Collections;
import java.util.Random;
import java.io.FileReader;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.ArrayList;

public class PostleitzahlClass {
    private Connection connection;

    public PostleitzahlClass(Connection connection) {
        this.connection = connection;
    }


    public ArrayList<Integer> insertPlzFromCSV(String csvFilePath) {
        ArrayList<Integer> plzs = getAllPlzIds();
        try {

            String sqlQuery = "INSERT INTO POSTLEITZAHL VALUES (?,?)";
            PreparedStatement stmt = connection.prepareStatement(sqlQuery);
            connection.setAutoCommit(false);

            try (CSVReader reader = new CSVReader(new FileReader(csvFilePath))) {
                String[] nextLine;

                //reader.readNext();

                int i;
                if(plzs.isEmpty()){
                    i = 1000;
                }else{
                    i = Collections.max(plzs) + 1;
                }

                while ((nextLine = reader.readNext()) != null) {

                    String ort = nextLine[0];

                    stmt.setInt(1, i++);
                    stmt.setString(2, ort);
                    stmt.addBatch();
                }

                int[] result = stmt.executeBatch();
                System.out.println("The number of rows inserted into Postleitzahl: " + result.length);

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

        return plzs;
    }


    public ArrayList<Integer> getAllPlzIds() {
        ArrayList<Integer> mitIDs = new ArrayList<>();

        try {
            connection.setAutoCommit(false);
            String sqlQuery = "SELECT PLZ FROM POSTLEITZAHL";
            try (PreparedStatement stmt = connection.prepareStatement(sqlQuery);
                 ResultSet rs = stmt.executeQuery()) {

                while (rs.next()) {
                    int mitID = rs.getInt("Plz");
                    mitIDs.add(mitID);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return mitIDs;
    }



}
