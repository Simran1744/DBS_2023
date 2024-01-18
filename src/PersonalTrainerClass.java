import com.opencsv.CSVReader;
import java.sql.*;
import java.util.HashMap;
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


    public ArrayList<Integer> insertIntoTrainerFromCSV(String csvFilePath, ArrayList<Integer> mitIDs, ArrayList<Integer> rezIDs) {

        ArrayList<Integer> newIDs = mitIDs;

        ArrayList<Integer> persIDs = getAllTrainerIds();
        ArrayList<Integer> rezIDscopy = rezIDs;

        persIDs.addAll(rezIDscopy);
        newIDs.removeAll(persIDs);




        try {

            String trainInsert = "INSERT INTO Personal_Trainer VALUES (?,?,?)";
            PreparedStatement stmt = connection.prepareStatement(trainInsert);
            connection.setAutoCommit(false);

            try (CSVReader reader = new CSVReader(new FileReader(csvFilePath))) {
                String[] nextLine;

                reader.readNext();

                while ((nextLine = reader.readNext()) != null) {
                    String gender = nextLine[0];
                    String spez = nextLine[1];

                    Random r = new Random();

                    int randomIndex = r.nextInt(newIDs.size());
                    int mit_ID = newIDs.get(randomIndex);

                    stmt.setInt(1, mit_ID);
                    stmt.setString(2, gender);
                    stmt.setString(3, spez);
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


    public ArrayList<Integer> getAllTrainerIds() {
        ArrayList<Integer> mitIDs = new ArrayList<>();

        try {
            connection.setAutoCommit(false);
            String sqlQuery = "SELECT MITARBEITER_ID FROM PERSONAL_TRAINER";
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

    public HashMap<Integer, Integer> getAllTrainerFitIds(){
        HashMap<Integer, Integer> fitMap = new HashMap<>();

        try {
            connection.setAutoCommit(false);
            String sqlQuery = "SELECT MITARBEITER.MITARBEITER_ID, MITARBEITER.STUDIO_ID " +
                    "FROM PERSONAL_TRAINER " +
                    "JOIN MITARBEITER ON PERSONAL_TRAINER.MITARBEITER_ID = MITARBEITER.MITARBEITER_ID";

            try (PreparedStatement stmt = connection.prepareStatement(sqlQuery);
                 ResultSet rs = stmt.executeQuery()) {

                while (rs.next()) {
                    int persID = rs.getInt("Mitarbeiter_ID");
                    int fitID = rs.getInt("Studio_ID");
                    fitMap.put(persID,fitID);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return fitMap;
    }



}
