package fsobot;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

public class Database {
    
    private Connection connection;
    
    public Database() {
	try {
            Class.forName("com.mysql.jdbc.Driver");
            connection = DriverManager.getConnection("jdbc:mysql://localhost:3306/FSO_Database", "root", "admin");
        }
        catch (SQLException ex) {} catch (ClassNotFoundException ex2) {}
    }
    
    public Connection getConnection() { return connection; }
    
    public ResultSet getQueryResult(String query) {
        try {
            Statement stat = connection.createStatement();
            return stat.executeQuery(query); 
        } catch (SQLException ex) {}
        
        return null;
    }
    
    public boolean isRegistered(String n, String c, String u, String chatID) {
            if(u.equals("@null") || c==null) return false;
            PreparedStatement ps;
            
            try {
                ps = connection.prepareStatement("SELECT * FROM Utenti WHERE nome=? AND cognome=? AND username=?");
                ps.setString(1, n);
	    	ps.setString(2, c);
                ps.setString(3, u);
	    	
	    	ResultSet result = ps.executeQuery();
                if(result.next()) {
                    if(result.getString("chat_id").equals("0")) updateChatId(n, c, u, chatID);
                    return true;
                }
                
            } catch (SQLException ex) {}
            return false;
    }
    
    private void updateChatId(String n, String c, String u, String chatID) {
        PreparedStatement ps;
            
            try {
                ps = connection.prepareStatement("UPDATE Utenti SET chat_id=? WHERE nome=? AND cognome=? AND username=?");
                ps.setString(1, chatID);
	    	ps.setString(2, n);
                ps.setString(3, c);
                ps.setString(4, u);
                ps.executeUpdate();
                
            } catch (SQLException ex) {}
    }
}
