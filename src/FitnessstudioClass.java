import java.sql.*;
import java.util.ArrayList;


public class FitnessstudioClass {

    private ArrayList<Integer> list_;

    private Connection connection;

    public FitnessstudioClass(Connection connection) {
        this.connection = connection;
    }

    public ArrayList<Integer> insertIntoFitnessstudio() {
        list_ = new ArrayList<>();
        try {
            String sqlQuery = "INSERT INTO Fitnessstudio VALUES (?,?,?,?,?)";
            PreparedStatement pstmt = connection.prepareStatement(sqlQuery);
            connection.setAutoCommit(false);
            try {
                for (int i = 601; i <= 700; i++) {
                    pstmt.setInt(1, i);
                    pstmt.setString(2, "Fitinn");
                    pstmt.setString(3, "Wien");
                    pstmt.setInt(4, 1230);
                    pstmt.setString(5, "Columbusplatz 7/8");
                    pstmt.addBatch();
                    list_.add(i);
                }
                    int[] result = pstmt.executeBatch();
                    System.out.println("The number of rows inserted into Mitarbeiter: " + result.length);
                    connection.commit();


            } catch (Exception e) {
                e.printStackTrace();
            }
        }catch(Exception e){
            System.err.println(e.toString());
        }

        return list_;

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

    public ArrayList<Integer> getList(){
        return list_;
    }

}
