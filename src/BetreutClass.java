import com.opencsv.CSVReader;
import java.sql.*;
import java.text.SimpleDateFormat;
import java.util.*;
import java.io.FileReader;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;

public class BetreutClass {
    private Connection connection;

    public BetreutClass(Connection connection){
        this.connection = connection;
    }

    public ArrayList<Integer> insertBetreut(ArrayList<Integer> mitIDs, ArrayList<Integer> kundIDs) {
        ArrayList<Integer> newMitIDs = mitIDs;
        ArrayList<Integer> newKundIDs = kundIDs;

        try {

            String rezInsert = "INSERT INTO BETREUT VALUES (?,?,?)";
            PreparedStatement stmt = connection.prepareStatement(rezInsert);
            connection.setAutoCommit(false);

            Calendar calendar = Calendar.getInstance();

            for(int i = 0; i < 100; i++){

                Random r = new Random();

                int randomIndex = r.nextInt(newMitIDs.size());
                int mitID = newMitIDs.get(randomIndex);

                int randomIndex_ = r.nextInt(newKundIDs.size());
                int kundID = newKundIDs.get(randomIndex_);



                    stmt.setInt(1, mitID);
                    stmt.setInt(2, kundID);
                    stmt.setTimestamp(3, new java.sql.Timestamp(calendar.getTimeInMillis()));
                    //stmt.setTimestamp(3, new java.sql.Timestamp(new java.util.Date().getTime()));
                    stmt.addBatch();

                    calendar.add(Calendar.DAY_OF_MONTH, 1);
            }

            int[] result = stmt.executeBatch();
            System.out.println("The number of rows inserted into Betreut: " + result.length);

            connection.commit();
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

        return newKundIDs;
    }
}
