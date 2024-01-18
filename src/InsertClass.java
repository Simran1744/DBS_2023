import java.sql.*;
import java.util.ArrayList;

public class InsertClass {
    public static void main(String args[]) {
        try {
            Class.forName("oracle.jdbc.driver.OracleDriver");

            // Connection details
            String database = "jdbc:oracle:thin:@oracle19.cs.univie.ac.at:1521:orclcdb";
            String user = "a12224337";
            String pass = "dbs77";

            // Establish a connection to the database
            Connection connection = DriverManager.getConnection(database, user, pass);

            try {

                PostleitzahlClass plzIns = new PostleitzahlClass(connection);
                MitarbeiterClass mitIns = new MitarbeiterClass(connection);
                FitnessstudioClass fitIns = new FitnessstudioClass(connection);
                PersonalTrainerClass persIns = new PersonalTrainerClass(connection);
                RezeptionistClass rezIns = new RezeptionistClass(connection);
                KundeClass kundIns = new KundeClass(connection);
                BetreutClass betreutIns = new BetreutClass(connection);
                CoachtClass coachtIns = new CoachtClass(connection);
                Mit_StufenClass mitStufenIns = new Mit_StufenClass(connection);


                //mitStufenIns.insertMitStufen(); //only run once!!!

                plzIns.insertPlzFromCSV("/Users/simra/Desktop/DBS_2023/PlzCSV.csv");

                fitIns.insertIntoFitnessstudio("/Users/simra/Desktop/DBS_2023/Fitness_DATA.csv", plzIns.getAllPlzIds());

                mitIns.insertIntoMitarbeiterFromCSV("/Users/simra/Desktop/DBS_2023/MOCK_DATA.csv", fitIns.getAllFitnessstudioIds());

                ArrayList<Integer> usedIds = new ArrayList<>();
                usedIds = persIns.insertIntoTrainerFromCSV("/Users/simra/Desktop/DBS_2023/Tr2.csv",
                        mitIns.getAllMitarbeiterIds(), rezIns.getAllRezeptionistIds());

                usedIds = rezIns.insertIntoRezeptionistCSV("/Users/simra/Desktop/DBS_2023/Rez4.csv", usedIds);

                kundIns.insertKundeCSV("/Users/simra/Desktop/DBS_2023/Kunde_DATA.csv", fitIns.getAllFitnessstudioIds());

                betreutIns.insertBetreut(rezIns.getAllRezeptionistIds(), mitIns.getAllMitarbeiterIds());

                coachtIns.insertCoacht(persIns.getAllTrainerFitIds(), kundIns.getAllKundFitIds());


                ArrayList<Integer> newList;


                connection.commit();

            } catch (Exception e) {

                e.printStackTrace();
                connection.rollback();

            } finally {
                if(connection != null){
                    connection.close();
                }
            }
        }catch(Exception e){
            System.err.println(e.toString());
        }
    }
}

