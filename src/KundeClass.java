import com.opencsv.CSVReader;
import java.sql.*;
import java.util.Collection;
import java.util.Collections;
import java.util.Random;
import java.io.FileReader;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.ArrayList;

public class KundeClass {
    private Connection connection;

    public KundeClass(Connection connection){
        this.connection = connection;
    }

    public ArrayList<Integer> insertKundeCSV(String csvFilePath, ArrayList<Integer> fitIDs) {
        ArrayList<Integer> newIDs = fitIDs;
        ArrayList<Integer> usedIDs = getAllKundenIds();
        try {

        String rezInsert = "INSERT INTO KUNDE VALUES (?,?,?,?,?)";
        PreparedStatement stmt = connection.prepareStatement(rezInsert);
        connection.setAutoCommit(false);

        try (CSVReader reader = new CSVReader(new FileReader(csvFilePath))) {
            String[] nextLine;

            reader.readNext();

            int i;

            if(usedIDs.isEmpty()){
                i = 1;
            }else{
                i = Collections.max(usedIDs) + 1;
            }
            while ((nextLine = reader.readNext()) != null) {

                String vorname = nextLine[0];
                String nachname = nextLine[1];
                String gender = nextLine[2];

                Random r = new Random();

                int randomIndex = r.nextInt(newIDs.size());
                int fit_ID = newIDs.get(randomIndex);

                stmt.setInt(1, i++);
                stmt.setInt(2, fit_ID);
                stmt.setString(3, vorname);
                stmt.setString(4, nachname);
                stmt.setString(5, gender);
                stmt.addBatch();

            }

            int[] result = stmt.executeBatch();
            System.out.println("The number of rows inserted into Kunde: " + result.length);

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

    public ArrayList<Integer> getAllKundenIds() {
        ArrayList<Integer> kundIDs = new ArrayList<>();

        try {
            connection.setAutoCommit(false);
            String sqlQuery = "SELECT KUNDENNUMMER FROM KUNDE";
            try (PreparedStatement stmt = connection.prepareStatement(sqlQuery);
                 ResultSet rs = stmt.executeQuery()) {

                while (rs.next()) {
                    int kundID = rs.getInt("Kundennummer");
                    kundIDs.add(kundID);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return kundIDs;
    }
}
