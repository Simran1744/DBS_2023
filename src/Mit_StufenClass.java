
import java.util.*;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.Map;

public class Mit_StufenClass {
    private Connection connection;

    public Mit_StufenClass(Connection connection){
        this.connection = connection;
    }

    public HashMap<String,Integer> insertMitStufen() {
        HashMap<String, Integer> stufen = new HashMap<>();

        stufen.put("Bronze", 30 );
        stufen.put("Silber", 40 );
        stufen.put("Gold", 50 );

        try {

            String rezInsert = "INSERT INTO MITGLIEDSCHAFTS_STUFE VALUES (?,?)";
            PreparedStatement stmt = connection.prepareStatement(rezInsert);
            connection.setAutoCommit(false);

            Calendar calendar = Calendar.getInstance();

            for(Map.Entry<String, Integer> entry1 : stufen.entrySet()){

                stmt.setString(1, entry1.getKey());
                stmt.setInt(2, entry1.getValue());
                stmt.addBatch();
            }

            int[] result = stmt.executeBatch();
            System.out.println("The number of rows inserted into Mitgliedschafts_Stufen: " + result.length);

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

        return stufen;
    }
}
