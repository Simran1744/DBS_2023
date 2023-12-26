import com.opencsv.CSVReader;
import java.sql.*;
import java.util.Random;
import java.io.FileReader;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.ArrayList;

public class PersonalTrainerClass {
    private Connection connection;

    public PersonalTrainerClass(Connection connection) {
        this.connection = connection;
    }


    public ArrayList<Integer> insertIntoTrainerFromCSV(String csvFilePath, ArrayList<Integer> mitIDs) {

        ArrayList<Integer> newIDs = new ArrayList<>();
        newIDs = mitIDs;

        try {

            String trainInsert = "INSERT INTO Personal_Trainer VALUES (?,?,?)";
            PreparedStatement stmt = connection.prepareStatement(trainInsert);
            connection.setAutoCommit(false);

            try (CSVReader reader = new CSVReader(new FileReader(csvFilePath))) {
                String[] nextLine;

                reader.readNext();

                while ((nextLine = reader.readNext()) != null) {
                    String gender = nextLine[0];
                    String lang = nextLine[1];

                    Random r = new Random();

                    int randomIndex = r.nextInt(newIDs.size());
                    int mit_ID = newIDs.get(randomIndex);

                    stmt.setInt(1, mit_ID);
                    stmt.setString(2, gender);
                    stmt.setString(3, lang);
                    stmt.addBatch();

                    newIDs.remove(randomIndex);

                }

                int[] result = stmt.executeBatch();
                System.out.println("The number of rows inserted into PersonalTrainer: " + result.length);

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

}
