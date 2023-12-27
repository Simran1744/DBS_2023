import com.opencsv.CSVReader;

import java.io.FileReader;
import java.sql.*;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Random;


public class FitnessstudioClass {


    private Connection connection;

    public FitnessstudioClass(Connection connection) {
        this.connection = connection;
    }

    public ArrayList<Integer> insertIntoFitnessstudio(String csvFilePath) {
        ArrayList<Integer> fit_IDs = getAllFitnessstudioIds();

        try {
            String sqlQuery = "INSERT INTO Fitnessstudio VALUES (?,?,?,?,?)";
            PreparedStatement pstmt = connection.prepareStatement(sqlQuery);
            connection.setAutoCommit(false);

            try (CSVReader reader = new CSVReader(new FileReader(csvFilePath))) {
                String[] nextLine;

                reader.readNext();

                int i;
                if(fit_IDs.isEmpty()){
                    i = 1;
                }else{
                    i = Collections.max(fit_IDs) + 1;
                }

                while ((nextLine = reader.readNext()) != null) {

                    String f_Name = nextLine[0];
                    String ort = "Wien";
                    int platz = Integer.parseInt(nextLine[1]);
                    String strasse = nextLine[2];


                    pstmt.setInt(1, i++);
                    pstmt.setString(2, f_Name);
                    pstmt.setString(3, ort);
                    pstmt.setInt(4, platz);
                    pstmt.setString(5, strasse);
                    pstmt.addBatch();


                }


                int[] result = pstmt.executeBatch();
                System.out.println("The number of rows inserted into Fitnessstudio: " + result.length);

                connection.commit();
            } catch (Exception e) {
                e.printStackTrace();
            }
        }catch(Exception e){
            System.err.println(e.toString());
        }

        return fit_IDs;

    }

    public ArrayList<Integer> getAllFitnessstudioIds() {
        ArrayList<Integer> fitnessstudioIds = new ArrayList<>();

        try {
            connection.setAutoCommit(false);
            String sqlQuery = "SELECT Studio_ID FROM Fitnessstudio";
            try (PreparedStatement pstmt = connection.prepareStatement(sqlQuery);
                 ResultSet rs = pstmt.executeQuery()) {

                while (rs.next()) {
                    int studioId = rs.getInt("Studio_ID");
                    fitnessstudioIds.add(studioId);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return fitnessstudioIds;
    }


}
