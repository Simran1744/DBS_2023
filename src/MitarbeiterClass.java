import com.opencsv.CSVReader;
import java.sql.*;

import java.io.FileReader;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.ArrayList;

public class MitarbeiterClass {

    private ArrayList<Integer> list_;

    private Connection connection;

    public MitarbeiterClass(Connection connection) {
        this.connection = connection;
    }


    public ArrayList<Integer> insertIntoMitarbeiterFromCSV(String csvFilePath, ArrayList<Integer> fitIDs) {
        ArrayList<Integer> mitarbeiterIds = new ArrayList<>();
        try {
            String mitInsert = "INSERT INTO Mitarbeiter VALUES (?,?,?,?)";
            PreparedStatement mitstmt = connection.prepareStatement(mitInsert);
            connection.setAutoCommit(false);

            try (CSVReader reader = new CSVReader(new FileReader(csvFilePath))) {
                String[] nextLine;

                reader.readNext();
                reader.readNext();

                //TODO: Change Code here
                while ((nextLine = reader.readNext()) != null) {
                    int m_id = Integer.parseInt(nextLine[0]); // Assuming id is the first column
                    String vorname = nextLine[1];
                    String nachname = nextLine[2];

                    for (Integer studio_id : fitIDs) {
                        mitstmt.setInt(1, m_id);
                        mitstmt.setInt(2, studio_id);
                        mitstmt.setString(3, vorname);
                        mitstmt.setString(4, nachname);
                        mitstmt.addBatch();
                        break;
                    }
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
}
