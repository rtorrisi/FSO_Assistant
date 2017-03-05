package fsobot;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.URL;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;
import org.telegram.telegrambots.TelegramBotsApi;
import org.telegram.telegrambots.api.methods.GetFile;
import org.telegram.telegrambots.api.methods.send.SendAudio;
import org.telegram.telegrambots.api.methods.send.SendDocument;
import org.telegram.telegrambots.api.methods.send.SendMessage;
import org.telegram.telegrambots.api.methods.send.SendPhoto;
import org.telegram.telegrambots.api.methods.updatingmessages.EditMessageText;
import org.telegram.telegrambots.api.objects.CallbackQuery;
import org.telegram.telegrambots.api.objects.Contact;
import org.telegram.telegrambots.api.objects.File;
import org.telegram.telegrambots.api.objects.Message;
import org.telegram.telegrambots.api.objects.PhotoSize;
import org.telegram.telegrambots.api.objects.Update;
import org.telegram.telegrambots.api.objects.replykeyboard.InlineKeyboardMarkup;
import org.telegram.telegrambots.api.objects.replykeyboard.ReplyKeyboardMarkup;
import org.telegram.telegrambots.api.objects.replykeyboard.buttons.InlineKeyboardButton;
import org.telegram.telegrambots.api.objects.replykeyboard.buttons.KeyboardButton;
import org.telegram.telegrambots.api.objects.replykeyboard.buttons.KeyboardRow;
import org.telegram.telegrambots.bots.TelegramLongPollingBot;
import org.telegram.telegrambots.exceptions.TelegramApiException;
import org.telegram.telegrambots.exceptions.TelegramApiRequestException;

public class TelegramBot extends TelegramLongPollingBot {

    public TelegramBot(Display window) { start(window); }

    private void start(Display window) {
        TelegramBotsApi telegramBotsApi = new TelegramBotsApi();
	try {
            telegramBotsApi.registerBot(this);
            window.getStatusLabel().setText("avviato.");
        } catch (TelegramApiRequestException e) {
            window.getStatusLabel().setText("errore durante l'avvio.");
        }
    }

    public void onUpdateReceived(Update update) {
        
        if(update.hasMessage())
        {
            Message message = update.getMessage();
            String chatID = message.getChatId().toString();
            String nome = message.getFrom().getFirstName();
            String cognome = message.getFrom().getLastName();
            String username = "@"+message.getFrom().getUserName();

            if(database.isRegistered(nome, cognome, username, chatID))
            {
                if (message.getPhoto()!=null) saveImage(message.getPhoto());
                else if(message.getContact()!=null) saveNumber(message.getContact());
                else if(message.hasText())
                {
                    String mess = message.getText().toLowerCase();
                    
                    // BASI #################################################
                    if(mess.contains("b_")) sendBase(mess, chatID);
                    else if(mess.contains("basi")) sendMessage("Menù Basi:", getBasiKeyboard(), chatID);
                    else if(mess.contains("complete")) sendMessage("Lista Basi COMPLETE", getListaBasiKeyboard("completa"), chatID);
                    else if(mess.contains("ritmiche")) sendMessage("Lista Basi RITMICHE", getListaBasiKeyboard("ritmica"), chatID);
                    else if(mess.contains("archi")) sendMessage("Lista Basi ARCHI", getListaBasiKeyboard("archi"), chatID);
                    else if(mess.contains("voci")) sendMessage("Lista Basi VOCI", getListaBasiKeyboard("voci"), chatID);
                    
                    // MENU #################################################
                    else if(mess.contains("menu")) sendMessage("Menù principale:", getMainMenuKeyboard(), chatID);
                    else if(mess.contains("start")) sendMessage("Salve "+nome, getStartMenuKeyboard(), chatID);
                    else if(mess.contains("profilo")) sendProfile(username, chatID);
                    else if(mess.contains("news")) sendLastNews(username, chatID);
                    
                    // INFO #################################################
                    else if(mess.contains("info")) sendMessage("Menù informazioni:", getInfoKeyboard(), chatID);

                    else if(mess.contains("concerti")) sendEvent(chatID);
                    
                    //else if(mess.contains("brani prova"))

                    else if(mess.contains("rubrica")) sendRubrica(chatID);
                    else if(mess.contains("numero")) sendUsersNumber(mess, chatID);
                    else if(mess.contains("regolamento")) sendDocumentById(chatID, "BQADBAADTwADiz5kChEOy4qfimUdAg");
                    else if(mess.contains("link esterni")) sendMessage("Ecco qui i link utili", linkInline(),chatID);
                    //##########################################################

                    // ASSENZA E RITARDO #######################################
                    else if(mess.contains("assenza")) {
                        boolean result = sendAbsence(username, chatID);
                        if(result) sendMessageToAdmin("🚨 "+nome+" "+cognome+" ha segnalato che mancherà alle prove!");

                    }
                    else if(mess.contains("cancella")) {
                        boolean result = removeAbsence(username, chatID);
                        if(result) sendMessageToAdmin("🚨 "+nome+" "+cognome+" ha rimosso la segnalazione di assenza!");
                        else sendMessageToAdmin("🚨 "+nome+" "+cognome+" ha provato senza successo a rimuovere la segnalazione di assenza!");
                    }
                    else if(mess.contains("ritardo")) sendMessage("Desideri aggiungere l'orario previsto di arrivo?", getRitardoKeyboard(), chatID);
                    else if(mess.contains("non aggiungere orario")) {
                        sendMessageToAdmin("🚨 "+nome+" "+cognome+" ha segnalato che ritarderà alle prove!");
                        sendMessage("Ritardo segnalato! 👍", getMainMenuKeyboard(), chatID);
                    }
                    else if(mess.contains("aggiungi orario")) sendMessage("Inserisci l'orario previsto di arrivo", getOraRitardoKeyboard(),chatID);
                    else if(mess.contains("ore")) {
                        sendMessageToAdmin("🚨 "+nome+" "+cognome+" ha segnalato che arriverà alle prove alle "+mess+"!");
                        sendMessage("Orario ritardo segnalato con successo! 👍\n\nHo avvisato che arriverai alle "+mess, getMainMenuKeyboard(), chatID);
                    }
                    //##########################################################

                    else sendMessage("Comando non attivo", getMainMenuKeyboard(), chatID);
                } //hasText()

            } else sendNotRegisteredMessage(chatID);

        } //hasMessage
        
        else if(update.hasCallbackQuery()){
            
            CallbackQuery callbackQuery = update.getCallbackQuery();
                String queryID = callbackQuery.getId();
                String queryMessage = callbackQuery.getMessage().getText();
                int queryMessageID = callbackQuery.getMessage().getMessageId();
                String queryData = callbackQuery.getData();
                String queryUsername = callbackQuery.getFrom().getUserName();
                String queryChatID = callbackQuery.getFrom().getId().toString();
                
            if(queryData.contains("next") || queryData.contains("prev")) scanNews(queryUsername, queryMessage, queryMessageID, queryData, queryChatID);
            
            else if(queryData.contains("news_received")) {
                String arr[] = queryMessage.split("\n", 2);
                String newsID = arr[0].substring(5);
                
                PreparedStatement ps;
                
                try {
                    ps = database.getConnection().prepareStatement("SELECT idUtente FROM Utenti WHERE username=?");
                    ps.setString(1, "@"+queryUsername);

                    ResultSet rs = ps.executeQuery();
                    rs.next();
                    int idUtente = rs.getInt("idUtente");
                
                    ps = database.getConnection().prepareStatement("INSERT INTO Visualizzazioni_News (News_idNews, Utenti_idUtente) values (?, ?)");
                    ps.setInt(1, Integer.parseInt(newsID));
                    ps.setInt(2, idUtente);
                    ps.execute();
                    
                    hideInlineButton(queryChatID, queryMessage, queryMessageID, idUtente, Integer.parseInt(newsID));
                }
                catch (SQLException ex) { System.out.println("Errore insert visualizzazione"); }
            }
        }
    }


// METHODS #############################################################

    private void sendNotRegisteredMessage(String chat_id) { sendMessage("Non sei abilitato a usare questo servizio!\n\nControlla nelle impostazioni di Telegram di aver impostato Nome, Cognome e Username correttamente!\n\nOppure contatta la segreteria per essere abilitato ad usare il servizio!", chat_id); }

    private void sendMessage(String text, String chat_id) {
            SendMessage sendMessageRequest = new SendMessage();
            sendMessageRequest.setChatId(chat_id);
            sendMessageRequest.setText(text);
            sendMessageRequest.enableMarkdown(true);
            try { sendMessage(sendMessageRequest);
            } catch (TelegramApiException ex) {}
    }
    private void sendMessage(String text,  InlineKeyboardMarkup keyboard, String chat_id) {
            SendMessage sendMessageRequest = new SendMessage();
            sendMessageRequest.setChatId(chat_id);
            sendMessageRequest.setText(text);
            sendMessageRequest.enableMarkdown(true);
            sendMessageRequest.setReplyMarkup(keyboard);
            try { sendMessage(sendMessageRequest);
            } catch (TelegramApiException ex) {}
    }
    private void sendMessage(String text, ReplyKeyboardMarkup keyboard, String chat_id) {
            SendMessage sendMessageRequest = new SendMessage();
            sendMessageRequest.setChatId(chat_id);
            sendMessageRequest.setText(text);
            sendMessageRequest.enableMarkdown(true);
            sendMessageRequest.setReplyMarkup(keyboard);
            try { sendMessage(sendMessageRequest);
            } catch (TelegramApiException ex) {}
    }
    private void sendMessageToAdmin(String text) {

            ResultSet rs = database.getQueryResult("SELECT chat_id from Admin");
            try {
                while(rs.next()) {
                    SendMessage sendMessageRequest = new SendMessage();
                    sendMessageRequest.setText(text);
                    sendMessageRequest.setChatId(rs.getString("chat_id"));
                    sendMessage(sendMessageRequest);
                }
            } catch(SQLException ex) {} catch (TelegramApiException ex2) {}
    }
    
    private void sendLastNews(String username, String chat_id) {
        ResultSet rs = database.getQueryResult("SELECT idNews, news FROM News WHERE idNews >= ALL (SELECT idNews FROM News)");
        
        try {
            if(rs!=null && rs.next()) {
                int idNews = rs.getInt("idNews");
                String header = "#news"+idNews+"\n\n";
                String news = rs.getString("news");
                
                PreparedStatement ps;
                ps = database.getConnection().prepareStatement("SELECT idUtente FROM Utenti WHERE username=?");
                    ps.setString(1, username);

                ResultSet rs2 = ps.executeQuery();
                rs2.next();
                int idUtente = rs2.getInt("idUtente");
                
                sendMessage(header+news, setNewsInline(idUtente, idNews), chat_id);
            }
            else sendMessage("Nessuna news", chat_id);
        } catch (SQLException ex) { System.out.println("Errore richiesta news"); }
    }
    private void scanNews(String username, String queryMessage, int queryMessageID, String queryData, String queryChatID) {
        String arr[] = queryMessage.split("\n", 2);
            String newsID = arr[0].substring(5);
            
            PreparedStatement ps;
            ResultSet rs = null;
                        
            if(queryData.contains("prev")) {
                try {
                    ps = database.getConnection().prepareStatement("SELECT idNews, news FROM News WHERE idNews = (SELECT max(idNews) FROM News WHERE idNews < ?)");
                    ps.setString(1, newsID);
                    rs = ps.executeQuery();
                } catch (SQLException ex) { System.out.println("Errore 1 scansione news"); }
            }
            else if(queryData.contains("next")) {
                try {
                    ps = database.getConnection().prepareStatement("SELECT idNews, news FROM News WHERE idNews = (SELECT min(idNews) FROM News WHERE idNews > ?)");
                    ps.setString(1, newsID);
                    rs = ps.executeQuery();
                } catch (SQLException ex) { System.out.println("Errore 2 scansione news"); }
            }
        
            try {
                if(rs!=null && rs.next()) {
                    int idNews = rs.getInt("idNews");
                    String header = "#news"+idNews;
                    String news = rs.getString("news");
                    
                    PreparedStatement ps2;
                    ps2 = database.getConnection().prepareStatement("SELECT idUtente FROM Utenti WHERE username=?");
                    ps2.setString(1, "@"+username);

                    ResultSet rs2 = ps2.executeQuery();
                    rs2.next();
                    int idUtente = rs2.getInt("idUtente");
                        
                    EditMessageText editMess = new EditMessageText();
                    editMess.setChatId(queryChatID);
                    editMess.setMessageId(queryMessageID);
                    editMess.setText(header+"\n\n"+news);
                    editMess.setReplyMarkup(setNewsInline(idUtente, idNews));
                    editMessageText(editMess);
                }
            }
            catch (SQLException ex) { System.out.println("Errore 3 scansione news"); }
            catch(TelegramApiException ex) {
                String text = "Che hanno fatto di male quei bottoni per meritarsi questo trattamento? 😢 \n\n";
                text += "Scorri le news più lentamente per ridurre il traffico dati!";
                sendMessage(text, queryChatID);
            }
    }
    private void hideInlineButton(String queryChatID, String queryMessage, int queryMessageID, int idUtente, int idNews) {
        try {
            EditMessageText editMess = new EditMessageText();
            editMess.setChatId(queryChatID);
            editMess.setMessageId(queryMessageID);
            editMess.setText(queryMessage + "\n\n"+"Conferma lettura inviata!");
            editMess.setReplyMarkup(setNewsInline(idUtente, idNews));
            
            editMessageText(editMess);
        } catch (TelegramApiException ex) { System.out.println("Errore hideInlineButton"); }
    }
    
    private void sendProfile(String username, String chatID) {
        String ans="Profilo di ";

        PreparedStatement ps;
        try {
            ps = database.getConnection().prepareStatement("SELECT nome, cognome, telefono, assenze, nome_strumento, nome_sezione FROM (Utenti JOIN Strumenti ON Strumenti_nome_strumento=nome_strumento) JOIN Sezioni ON Sezioni_nome_sezione=nome_sezione WHERE username=? AND chat_id=?");
            ps.setString(1, username);
	    ps.setString(2, chatID);

	    ResultSet rs = ps.executeQuery();
            rs.next();
            ans += rs.getString("nome") + " "+ rs.getString("cognome") + "\n\n";
            ans += "Assenze fatte: " + rs.getInt("assenze") + "\n";
            ans += "Assenze rimanenti: " + (MAX_ASSENZE - rs.getInt("assenze")) + "\n\n";
            ans += "Strumento: " + rs.getString("nome_strumento") + "\n";
            ans += "Sezione: " + rs.getString("nome_sezione") + "\n";
            ans += "Cellulare: " + rs.getString("telefono");
        } catch(SQLException ex) {}

        sendMessage(ans, getMainMenuKeyboard(), chatID);
    }
    private boolean sendAbsence(String username, String chatID) {
        PreparedStatement ps;
        try {
            ps = database.getConnection().prepareStatement("UPDATE Utenti SET assenze=assenze+1 WHERE username=? AND chat_id=?");
            ps.setString(1, username);
	    ps.setString(2, chatID);
            
            if(ps.executeUpdate() == 1) {
                sendMessage("Assenza inviata con successo! 👍\n\n/cancella per annullare l'assenza segnalata!\n(Verrà segnalato agli amministratori)", getMainMenuKeyboard(), chatID);
                ps.close();
                return true;
            }
            ps.close();
            return false;

        } catch(SQLException e) {
            sendMessage("Assenza NON inviata con successo! 👎", getMainMenuKeyboard(), chatID);
            return false;
        }
    }
    private boolean removeAbsence(String username, String chatID) {
        PreparedStatement ps;
        try {
            ps = database.getConnection().prepareStatement("UPDATE Utenti SET assenze=assenze-1 WHERE username=? AND chat_id=?");
            ps.setString(1, username);
	    ps.setString(2, chatID);

	    if(ps.executeUpdate() == 1) {
                sendMessage("Assenza rimossa con successo! 👍", getMainMenuKeyboard(), chatID);
                ps.close();
                return true;
            }
            ps.close();
            return false;

        } catch(SQLException e) {
            sendMessage("Assenza NON rimossa con successo! 👎", getMainMenuKeyboard(), chatID);
            return false;
        }
    }
        
    private void saveImage(List<PhotoSize> photos) {

            try {
            GetFile getFileRequest = new GetFile();
            getFileRequest.setFileId(photos.get(photos.size()-1).getFileId());
            File file = getFile(getFileRequest);
            String destinationFile = "../Data/File/Images/img" + file.getFilePath().substring("photo/file".length());
            URL url = new URL("https://api.telegram.org/file/bot"+BOT_TOKEN+"/"+file.getFilePath());
            InputStream is = url.openStream();
            OutputStream os = new FileOutputStream(destinationFile);

            byte[] b = new byte[2048];
            int length;

            while ((length = is.read(b)) != -1) os.write(b, 0, length);

            is.close();
            os.close();
            } catch (TelegramApiException e) {} catch (IOException ex) {}
	}
    private void sendNewImage(String chat_id, String imagePath) throws TelegramApiException, IOException {
            SendPhoto sendPhotoRequest = new SendPhoto();
            sendPhotoRequest.setChatId(chat_id);
            InputStream is = new BufferedInputStream(new FileInputStream(imagePath));
            sendPhotoRequest.setNewPhoto("botimage.jpg", is);
            sendPhoto(sendPhotoRequest);
            is.close();
	}
    private void sendImageById(String chat_id, String image_id) {
        try {
            SendPhoto sendPhotoRequest = new SendPhoto();
            sendPhotoRequest.setChatId(chat_id);
            sendPhotoRequest.setPhoto(image_id);
            sendPhoto(sendPhotoRequest);
        } catch(TelegramApiException ex) {}
    }
    private void sendAudioById(String chat_id, String audio_id) {
        try {
            SendAudio sendAudioRequest = new SendAudio();
            sendAudioRequest.setChatId(chat_id);
            sendAudioRequest.setAudio(audio_id);
            sendAudio(sendAudioRequest);
        } catch (TelegramApiException ex) {}
    }
    private void sendDocumentById(String chat_id, String file_id) {
        try {
            SendDocument sendDocumentRequest = new SendDocument();
            sendDocumentRequest.setChatId(chat_id);
            sendDocumentRequest.setDocument(file_id);
            sendDocument(sendDocumentRequest);
        } catch (TelegramApiException ex) {}
    }
    
    private void sendBase(String mess, String chat_id) {
        String idBase = mess.substring(mess.lastIndexOf("(")+3, mess.length()-1);
        
        PreparedStatement ps;
            try {
                ps = database.getConnection().prepareStatement("SELECT file_id FROM Basi WHERE idBasi = ?");
                ps.setString(1, idBase);

	    	ResultSet rs = ps.executeQuery();
                while(rs.next()) {
                    String file_id = rs.getString("file_id");
                    sendAudioById(chat_id, file_id);
                }
            } catch(SQLException e) { sendMessage("Errore durante l'invio, riprova", chat_id); }
    }
    
    private void saveNumber(Contact contact) {
        String number = contact.getPhoneNumber().substring(2);
        String chatID = contact.getUserID().toString();
        String ans="Numero ";
        
        PreparedStatement ps;
        try {
            ps = database.getConnection().prepareStatement("UPDATE Utenti SET telefono=? WHERE chat_id=?");
            ps.setString(1, number);
	    ps.setString(2, chatID);

	    if(ps.executeUpdate() == 1) ans+=" inviato con successo! 👍";
            ps.close();

        } catch(SQLException e) { ans+= " NON inviato con successo! 👎";}
        
        sendMessage(ans, getMainMenuKeyboard(), chatID);
    }
    private void sendEvent(String chatID) {
        String ans = "";
        ResultSet rs = database.getQueryResult("SELECT nome_concerto, data_concerto, info, nome_citta, Provincia FROM Concerti co JOIN Citta ci ON co.Citta_idCitta = ci.idCitta ORDER BY data_concerto");
        
        try{
            while(rs.next()) {
                ans += "*" + rs.getString("nome_concerto") + "*\n";
                String data = rs.getString("data_concerto");
                String day = data.substring(8, 10);
                String month = data.substring(5, 7);
                String year = data.substring(0, 4);
                ans += "_Data:_ " + day + "/" + month + "/" + year + "\n";
                ans += "_Luogo:_ " + rs.getString("nome_citta") + " (" + rs.getString("Provincia") + ")\n";
                ans += "_Info:_ " + rs.getString("info") + "\n\n";
            }
        } catch(SQLException e) { e.getMessage(); }
        
        sendMessage(ans, getInfoKeyboard(),chatID);
    }
    private void sendRubrica(String chatID) {
            String ans = "";
            ResultSet rs = database.getQueryResult("select * from Utenti order by nome");

            try { while(rs.next()) { ans += rs.getString("nome") + " "+ rs.getString("cognome") + " " + rs.getString("telefono") + "\n"; }
            } catch(SQLException e) {}
            sendMessage(ans, getInfoKeyboard(),chatID);
    }
    private void sendUsersNumber(String mess, String chatID) {
            String data = getLastWord(mess);
            String ans="";

            PreparedStatement ps;
            try {
                ps = database.getConnection().prepareStatement("SELECT * FROM Utenti WHERE nome LIKE ? OR cognome LIKE ?");
                ps.setString(1, "%"+data+"%");
	    	ps.setString(2, "%"+data+"%");

	    	ResultSet rs = ps.executeQuery();
                while(rs.next()) { ans += rs.getString("nome") + " "+ rs.getString("cognome") + " " + rs.getString("telefono") + "\n"; }
            } catch(SQLException e) {}

            sendMessage(ans, getMainMenuKeyboard(), chatID);
    }

    private String getLastWord(String str) {
        int i=str.length()-1;
        while(i>=0 && str.charAt(i)!=' ') i--;
        return i<str.length()-2?str.substring(i+1):"";
    }
    
    private static InlineKeyboardMarkup setNewsInline(int idUtente, int idNews) {
        try {
            ResultSet rs = database.getQueryResult("SELECT min(idNews) AS min_id, max(idNews) AS max_id FROM News");
            PreparedStatement ps = database.getConnection().prepareStatement("SELECT * FROM Visualizzazioni_News WHERE News_idNews=? AND Utenti_idUtente=?");
                ps.setInt(1, idNews);
                ps.setInt(2, idUtente);
            
            if(rs!=null && rs.next()) {
                int min_id = rs.getInt("min_id");
                int max_id = rs.getInt("max_id");
                
                boolean min = idNews>min_id;
                boolean max = idNews<max_id;
                boolean read = true;
                
                ResultSet rs2 = ps.executeQuery();
                    if(rs2!=null && rs2.next()) read=false;
                    
                return newsInline(read, min, max);
            }
        } catch (SQLException ex) { System.out.println("errore setNewsInline"); }
        return null;
    }
    private static InlineKeyboardMarkup newsInline(boolean read, boolean prev, boolean next) {
        InlineKeyboardMarkup inlineKeyboard = new InlineKeyboardMarkup();
        
        List<List<InlineKeyboardButton>> keyboard = new ArrayList();
            
            if(prev || next) {
                List<InlineKeyboardButton> line1 = new ArrayList();
                    if(prev) {
                        InlineKeyboardButton a = new InlineKeyboardButton(); a.setText("⬅️"); a.setCallbackData("prev");
                        line1.add(a);
                    }
                    if(next) {
                        InlineKeyboardButton b = new InlineKeyboardButton(); b.setText("➡️"); b.setCallbackData("next");
                        line1.add(b);
                    }
                keyboard.add(line1);
            }
            
            if(read) {
                List<InlineKeyboardButton> line2 = new ArrayList();
                    InlineKeyboardButton c = new InlineKeyboardButton(); c.setText("News letta 👍"); c.setCallbackData("news_received");
                    line2.add(c);
                    keyboard.add(line2);
            }
        return inlineKeyboard.setKeyboard(keyboard);
    }
    private static InlineKeyboardMarkup linkInline() {
        InlineKeyboardMarkup inlineKeyboard = new InlineKeyboardMarkup();
            
        List<List<InlineKeyboardButton>> keyboard = new ArrayList();
            List<InlineKeyboardButton> line1 = new ArrayList();
                InlineKeyboardButton a = new InlineKeyboardButton(); a.setText("👥 Facebook"); a.setUrl("www.facebook.com/FreeSoundStudiesMusicAcademy");
                InlineKeyboardButton b = new InlineKeyboardButton(); b.setText("🎥 Youtube"); b.setUrl("www.youtube.com/user/FreeSoundStudies");
                InlineKeyboardButton c = new InlineKeyboardButton(); c.setText("🌐 Sito"); c.setUrl("www.freesoundstudies.it");
            line1.add(a);
            line1.add(b);
            line1.add(c);
        keyboard.add(line1);
        return inlineKeyboard.setKeyboard(keyboard);
    }

    private static ReplyKeyboardMarkup getMainMenuKeyboard() {
            ReplyKeyboardMarkup replyKeyboardMarkup = new ReplyKeyboardMarkup();
            replyKeyboardMarkup.setSelective(true);
            replyKeyboardMarkup.setResizeKeyboard(true);
            replyKeyboardMarkup.setOneTimeKeyboad(false);

            List<KeyboardRow> keyboard = new ArrayList();

                KeyboardRow keyboard1Row = new KeyboardRow();
                    keyboard1Row.add("👤 Profilo");
                    keyboard1Row.add("📰 News");
                KeyboardRow keyboard2Row = new KeyboardRow();
                    keyboard2Row.add("🅰 Segnala Assenza alle prove");
                KeyboardRow keyboard3Row = new KeyboardRow();
                    keyboard3Row.add("🕗 Segnala Ritardo alle prove");
                KeyboardRow keyboard4Row = new KeyboardRow();
                    keyboard4Row.add("🎧 Basi");
                KeyboardRow keyboard5Row = new KeyboardRow();
                    keyboard5Row.add("📎 Info");

            keyboard.add(keyboard1Row);
            keyboard.add(keyboard2Row);
            keyboard.add(keyboard3Row);
            keyboard.add(keyboard4Row);
            keyboard.add(keyboard5Row);
            replyKeyboardMarkup.setKeyboard(keyboard);

            return replyKeyboardMarkup;
        }                
    private static ReplyKeyboardMarkup getStartMenuKeyboard() {
            ReplyKeyboardMarkup replyKeyboardMarkup = new ReplyKeyboardMarkup();
            replyKeyboardMarkup.setSelective(true);
            replyKeyboardMarkup.setResizeKeyboard(true);
            replyKeyboardMarkup.setOneTimeKeyboad(false);

            List<KeyboardRow> keyboard = new ArrayList();

                KeyboardRow keyboard1Row = new KeyboardRow();
                    keyboard1Row.add("📱 Menu");
                KeyboardRow keyboard2Row = new KeyboardRow();
                    KeyboardButton contact = new KeyboardButton("📞 Invia il mio numero");
                    contact.setRequestContact(true);
                    keyboard2Row.add(contact);

            keyboard.add(keyboard1Row);
            keyboard.add(keyboard2Row);
            replyKeyboardMarkup.setKeyboard(keyboard);

            return replyKeyboardMarkup;
        }
    private static ReplyKeyboardMarkup getBasiKeyboard() {
        ReplyKeyboardMarkup replyKeyboardMarkup = new ReplyKeyboardMarkup();
            replyKeyboardMarkup.setSelective(true);
            replyKeyboardMarkup.setResizeKeyboard(true);
            replyKeyboardMarkup.setOneTimeKeyboad(false);
            
        List<KeyboardRow> keyboard = new ArrayList();
            
            KeyboardRow keyboardMenuRow = new KeyboardRow(); keyboardMenuRow.add("📱 Menu");
            KeyboardRow keyboard1Row = new KeyboardRow();
                keyboard1Row.add("🎹 Complete");
            KeyboardRow keyboard2Row = new KeyboardRow();
                keyboard2Row.add("🎸 Ritmiche");
            KeyboardRow keyboard3Row = new KeyboardRow();
                keyboard3Row.add("🎻 Archi");
            KeyboardRow keyboard4Row = new KeyboardRow();
                keyboard4Row.add("🎤 Voci");
            
            keyboard.add(keyboardMenuRow);
            keyboard.add(keyboard1Row);
            keyboard.add(keyboard2Row);
            keyboard.add(keyboard3Row);
            keyboard.add(keyboard4Row);

            replyKeyboardMarkup.setKeyboard(keyboard);

            return replyKeyboardMarkup;
    }
    private static ReplyKeyboardMarkup getListaBasiKeyboard(String tipo) {
        ReplyKeyboardMarkup replyKeyboardMarkup = new ReplyKeyboardMarkup();
            replyKeyboardMarkup.setSelective(true);
            replyKeyboardMarkup.setResizeKeyboard(true);
            replyKeyboardMarkup.setOneTimeKeyboad(false);
            
            List<KeyboardRow> keyboard = new ArrayList();
            
            KeyboardRow keyboard1Row = new KeyboardRow();
                keyboard1Row.add("📱 Menu");
                keyboard1Row.add("🎧 Tipo Basi");
                keyboard.add(keyboard1Row);
            
            PreparedStatement ps;
            try {
                ps = database.getConnection().prepareStatement("SELECT * FROM Basi JOIN Brani ON Brani_idBrano = idBrano WHERE tipologia = ? ORDER BY titolo");
                ps.setString(1, tipo);

	    	ResultSet rs = ps.executeQuery();
                while(rs.next()) {
                    KeyboardRow keyboardRow = new KeyboardRow();
                    keyboardRow.add( rs.getString("titolo") + " (b_" + rs.getInt("idBasi") + ")");
                    keyboard.add(keyboardRow);                    
                }
            } catch(SQLException e) {}

            replyKeyboardMarkup.setKeyboard(keyboard);
            return replyKeyboardMarkup;
    }
    private static ReplyKeyboardMarkup getInfoKeyboard() {
            ReplyKeyboardMarkup replyKeyboardMarkup = new ReplyKeyboardMarkup();
            replyKeyboardMarkup.setSelective(true);
            replyKeyboardMarkup.setResizeKeyboard(true);
            replyKeyboardMarkup.setOneTimeKeyboad(false);

            List<KeyboardRow> keyboard = new ArrayList();

            KeyboardRow keyboardMenuRow = new KeyboardRow(); keyboardMenuRow.add("📱 Menu");
            KeyboardRow keyboard1Row = new KeyboardRow();
                keyboard1Row.add("📅 Concerti");
            KeyboardRow keyboard2Row = new KeyboardRow();
                keyboard2Row.add("☎ Rubrica");
            KeyboardRow keyboard3Row = new KeyboardRow();
                keyboard3Row.add("📜 Regolamento");
            KeyboardRow keyboard4Row = new KeyboardRow();
                keyboard4Row.add("🌐 Link Esterni");
            //KeyboardRow keyboard5Row = new KeyboardRow();
                //keyboard5Row.add("📃 Brani Prova");
            

            keyboard.add(keyboardMenuRow);
            keyboard.add(keyboard1Row);
            keyboard.add(keyboard2Row);
            keyboard.add(keyboard3Row);
            keyboard.add(keyboard4Row);
            //keyboard.add(keyboard5Row);

            replyKeyboardMarkup.setKeyboard(keyboard);

            return replyKeyboardMarkup;
        }
    private static ReplyKeyboardMarkup getRitardoKeyboard() {
            ReplyKeyboardMarkup replyKeyboardMarkup = new ReplyKeyboardMarkup();
            replyKeyboardMarkup.setSelective(true);
            replyKeyboardMarkup.setResizeKeyboard(true);
            replyKeyboardMarkup.setOneTimeKeyboad(false);

            List<KeyboardRow> keyboard = new ArrayList();

            KeyboardRow keyboardMenuRow = new KeyboardRow(); keyboardMenuRow.add("📱 Menu"); keyboard.add(keyboardMenuRow);
            KeyboardRow keyboard1Row = new KeyboardRow(); keyboard1Row.add("⌚ Aggiungi orario"); keyboard.add(keyboard1Row);
            KeyboardRow keyboard2Row = new KeyboardRow(); keyboard2Row.add("❌⌚ Non aggiungere orario"); keyboard.add(keyboard2Row);

            replyKeyboardMarkup.setKeyboard(keyboard);

            return replyKeyboardMarkup;
        }
    private static ReplyKeyboardMarkup getOraRitardoKeyboard() {
            ReplyKeyboardMarkup replyKeyboardMarkup = new ReplyKeyboardMarkup();
            replyKeyboardMarkup.setSelective(true);
            replyKeyboardMarkup.setResizeKeyboard(true);
            replyKeyboardMarkup.setOneTimeKeyboad(false);

            List<KeyboardRow> keyboard = new ArrayList();

            KeyboardRow keyboardMenuRow = new KeyboardRow(); keyboardMenuRow.add("📱 Menu"); keyboard.add(keyboardMenuRow);
            int ore=19, min=0;
            while(ore < 23) {
                KeyboardRow keyboardRow = new KeyboardRow();
                keyboardRow.add("ore "+ore+":"+(min<10?"0"+min:min)+" circa");
                keyboard.add(keyboardRow);

                min+=15;
                if(min>=60) {
                    min=0;
                    ore++;
                }
            }

            replyKeyboardMarkup.setKeyboard(keyboard);

            return replyKeyboardMarkup;
        }

    @Override
    public String getBotToken() { return TelegramBot.BOT_TOKEN; }
    public String getBotUsername() { return TelegramBot.BOT_USERNAME; }
    static private String getToken() {
        
        String fileName = "../token.conf";
        String line;

        try {
            FileReader fileReader = new FileReader(fileName);
            BufferedReader bufferedReader = new BufferedReader(fileReader);
            line = bufferedReader.readLine();
            String token = line.substring(0, 45);
            bufferedReader.close();       
            return token;  
        }
        catch(FileNotFoundException ex) {}
        catch(IOException ex) {}
        return null;
    }
    
    public static final String BOT_USERNAME = "freesoundorchestra_bot";
    public static final String BOT_TOKEN = getToken();
    private static final Database database = new Database();
    private static final int MAX_ASSENZE = 5;
}

//🎹🎷🎺🎸🎻
