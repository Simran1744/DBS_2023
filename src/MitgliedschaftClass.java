import java.sql.*;
import java.sql.Date;
import java.util.*;

public class MitgliedschaftClass {
    private Connection connection;

    public MitgliedschaftClass(Connection connection){
        this.connection = connection;
    }

    public ArrayList<Integer> insertMitgliedschaft(ArrayList<Integer> kundIds) {
        ArrayList<Integer> mg_ids = kundIds;
        ArrayList<Integer> usedIds = this.getAllMGIds();

        mg_ids.removeAll(usedIds);


        try {

            String rezInsert = "INSERT INTO MITGLIEDSCHAFT VALUES (?,?,?,?,?)";
            PreparedStatement stmt = connection.prepareStatement(rezInsert);
            connection.setAutoCommit(false);

            Calendar calendar = Calendar.getInstance();

            ArrayList<String> gu = new ArrayList<>();
            gu.add("gueltig");
            gu.add("ungueltig");


            ArrayList<String>  st = new ArrayList<>();
            st.add("Silber");
            st.add("Gold");
            st.add("Bronze");

            int i=1;


           for(int j = 0; j < 500; j++){
               Random r = new Random();

               String stufe = st.get(r.nextInt(st.size()));
               String valid = gu.get(r.nextInt(gu.size()));

               stmt.setInt(1, mg_ids.get(j));
               stmt.setInt(2, i++);
               stmt.setString(3, stufe);
               stmt.setString(4, valid);
               stmt.setDate(5, new java.sql.Date(calendar.getTimeInMillis()));
               stmt.addBatch();

               calendar.add(Calendar.DAY_OF_MONTH, 1);
           }

            int[] result = stmt.executeBatch();
            System.out.println("The number of rows inserted into Mitgliedschaft: " + result.length);

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

        return mg_ids;
    }

    public ArrayList<Integer> getAllMGIds(){
        ArrayList<Integer> list =  new ArrayList<>();

        try {
            connection.setAutoCommit(false);
            String sqlQuery = "SELECT KUNDENNUMMER FROM MITGLIEDSCHAFT";
            try (PreparedStatement stmt = connection.prepareStatement(sqlQuery);
                 ResultSet rs = stmt.executeQuery()) {

                while (rs.next()) {
                    int kundID = rs.getInt("Kundennummer");
                    list.add(kundID);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return list;
    }

}
