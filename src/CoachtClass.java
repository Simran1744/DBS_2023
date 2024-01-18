import java.util.*;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.util.Map;

public class CoachtClass {
    private Connection connection;

    public CoachtClass(Connection connection){
        this.connection = connection;
    }

    public HashMap<Integer,Integer> insertCoacht(HashMap<Integer, Integer> persID, HashMap<Integer, Integer> kundID) {
        HashMap<Integer, Integer> persMap = persID;
        HashMap<Integer, Integer> kundMap = kundID;

        HashMap<Integer, Integer> ids = new HashMap<>();

        for (Map.Entry<Integer, Integer> entry1 : persMap.entrySet()) {
            Integer key1 = entry1.getKey();
            Integer value1 = entry1.getValue();

            for (Map.Entry<Integer, Integer> entry2 : kundMap.entrySet()) {
                Integer key2 = entry2.getKey();
                Integer value2 = entry2.getValue();

                if (value1.equals(value2)) {
                    ids.put(key1, key2);
                    break;
                }
            }
        }

        try {

            String rezInsert = "INSERT INTO COACHT VALUES (?,?,?,?)";
            PreparedStatement stmt = connection.prepareStatement(rezInsert);
            connection.setAutoCommit(false);

            Calendar calendar = Calendar.getInstance();

            for(Map.Entry<Integer, Integer> entry1 : ids.entrySet()){

                stmt.setInt(1, entry1.getKey());
                stmt.setInt(2, entry1.getValue());
                stmt.setTimestamp(3, new java.sql.Timestamp(calendar.getTimeInMillis()));
                calendar.add(Calendar.HOUR_OF_DAY, 1);
                stmt.setTimestamp(4, new java.sql.Timestamp(calendar.getTimeInMillis()));
                stmt.addBatch();

                calendar.add(Calendar.DAY_OF_MONTH, 1);
            }

            int[] result = stmt.executeBatch();
            System.out.println("The number of rows inserted into Coacht: " + result.length);

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

        return ids;
    }
}
