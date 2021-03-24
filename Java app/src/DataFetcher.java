import java.sql.*;

public class DataFetcher {
    private String connUrl = "jdbc:mysql://192.168.2.7:3306/spotify";
    private String query =
            "SELECT distinct s.name as songName, count(p.songID) as times FROM played p INNER JOIN song s ON s.songID = p.songID WHERE p.playedBy LIKE '111%' AND s.addedBy like '111%' GROUP BY s.songID ORDER BY times DESC LIMIT 10";

    public ResultSet getData() {
        try {
            Connection conn = DriverManager.getConnection(connUrl, "remote", "***REMOVED***");
            PreparedStatement ps = conn.prepareStatement(query, ResultSet.TYPE_SCROLL_INSENSITIVE, ResultSet.CONCUR_UPDATABLE);
            return ps.executeQuery();
        } catch (SQLException e) {
            System.out.println(e);
        }
        return null;
    }
}
