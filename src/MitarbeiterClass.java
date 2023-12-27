import com.opencsv.CSVReader;
import java.sql.*;
import java.util.Collections;
import java.util.Random;
import java.io.FileReader;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.ArrayList;

public class MitarbeiterClass {


    private Connection connection;

    public MitarbeiterClass(Connection connection) {
        this.connection = connection;
    }


    public ArrayList<Integer> insertIntoMitarbeiterFromCSV(String csvFilePath, ArrayList<Integer> fitIDs) {
        ArrayList<Integer> mitarbeiterIds = getAllMitarbeiterIds();
        try {
            String mitInsert = "INSERT INTO Mitarbeiter VALUES (?,?,?,?)";
            PreparedStatement mitstmt = connection.prepareStatement(mitInsert);
            connection.setAutoCommit(false);

            try (CSVReader reader = new CSVReader(new FileReader(csvFilePath))) {
                String[] nextLine;

                reader.readNext();

                int i;
                if(mitarbeiterIds.isEmpty()){
                    i = 1;
                }else{
                    i = Collections.max(mitarbeiterIds) + 1;
                }

                while ((nextLine = reader.readNext()) != null) {

                    String vorname = nextLine[1];
                    String nachname = nextLine[2];

                    Random r = new Random();

                    int studio_id = fitIDs.get(r.nextInt(fitIDs.size()));

                    mitstmt.setInt(1, i++);
                    mitstmt.setInt(2, studio_id);
                    mitstmt.setString(3, vorname);
                    mitstmt.setString(4, nachname);
                    mitstmt.addBatch();


                }

                int[] result = mitstmt.executeBatch();
                System.out.println("The number of rows inserted into Mitarbeiter: " + result.length);

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
        return mitarbeiterIds;
    }

    public ArrayList<Integer> getAllMitarbeiterIds() {
        ArrayList<Integer> mitIDs = new ArrayList<>();

        try {
            connection.setAutoCommit(false);
            String sqlQuery = "SELECT MITARBEITER_ID FROM MITARBEITER";
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
