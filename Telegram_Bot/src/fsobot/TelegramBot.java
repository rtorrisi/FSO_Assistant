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
import org.telegram.telegrambots.api.methods.send.SendDocument;
import org.telegram.telegrambots.api.methods.send.SendMessage;
import org.telegram.telegrambots.api.methods.send.SendPhoto;
import org.telegram.telegrambots.api.objects.File;
import org.telegram.telegrambots.api.objects.Message;
import org.telegram.telegrambots.api.objects.PhotoSize;
import org.telegram.telegrambots.api.objects.Update;
import org.telegram.telegrambots.api.objects.replykeyboard.InlineKeyboardMarkup;
import org.telegram.telegrambots.api.objects.replykeyboard.ReplyKeyboardMarkup;
import org.telegram.telegrambots.api.objects.replykeyboard.buttons.InlineKeyboardButton;
import org.telegram.telegrambots.api.objects.replykeyboard.buttons.KeyboardRow;
import org.telegram.telegrambots.bots.TelegramLongPollingBot;
import org.telegram.telegrambots.exceptions.TelegramApiException;
import org.telegram.telegrambots.exceptions.TelegramApiRequestException;

public class TelegramBot extends TelegramLongPollingBot {

    public TelegramBot() { start(); }

    private void start() {
        TelegramBotsApi telegramBotsApi = new TelegramBotsApi();
	try {
            telegramBotsApi.registerBot(this);
            System.out.println(BOT_USERNAME+" avviato.");
        } catch (TelegramApiRequestException e) {
            System.out.println(BOT_USERNAME+" non è riuscito ad avviarsi.");
        }
    }

    public void onUpdateReceived(Update update)
    {

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

                if(message.hasText())
                {
                    String mess = message.getText().toLowerCase();

                    if(mess.contains("menu")) sendMessage("Menù principale:", getMainMenuKeyboard(), chatID);
                    else if(mess.contains("profilo")) sendProfile(username, chatID);
                    // INFO #################################################
                    else if(mess.contains("info")) sendMessage("Menù informazioni:", getInfoKeyboard(), chatID);

                    else if(mess.contains("concerti")) sendMessage("Menù concerti:", getConcertsKeyboard(), chatID);
                    //else if(mess.contains("tutti gli eventi"))
                    //else if(mess.contains("prossimi eventi"))
                    //else if(mess.contains("brani prova"))

                    else if(mess.contains("rubrica")) sendRubrica(chatID);
                    else if(mess.contains("numero")) sendUsersNumber(mess, chatID);
                    else if(mess.contains("regolamento")) sendDocumentById(chatID, "BQADBAADTwADiz5kChEOy4qfimUdAg");
                    else if(mess.contains("link esterni")) sendMessage("Ecco qui i link utili", linkInline(),chatID);
                    //##########################################################

                    // ASSENZA E RITARDO #######################################
                    else if(mess.contains("assenza")) {
                        sendAbsence(username, chatID);
                        sendMessageToAdmin("🚨 "+nome+" "+cognome+" ha segnalato che mancherà alle prove! 🚨");
                    }
                    else if(mess.contains("annulla_segnalazione")) {
                        removeAbsence(username, chatID);
                        sendMessageToAdmin("🚨 "+nome+" "+cognome+" ha rimosso la segnalazione di assenza! 🚨");
                    }
                    else if(mess.contains("ritardo")) sendMessage("Desideri aggiungere l'orario previsto di arrivo?", getRitardoKeyboard(), chatID);
                    else if(mess.contains("non aggiungere orario")) {
                        sendMessageToAdmin("🚨 "+nome+" "+cognome+" ha segnalato che ritarderà alle prove! 🚨");
                        sendMessage("Ritardo segnalato! 👍", getMainMenuKeyboard(), chatID);
                    }
                    else if(mess.contains("aggiungi orario")) sendMessage("Inserisci l'orario previsto di arrivo", getOraRitardoKeyboard(),chatID);
                    else if(mess.contains("ore")) {
                        sendMessageToAdmin("🚨 "+nome+" "+cognome+" ha segnalato che arriverà alle prove alle "+mess+"! 🚨");
                        sendMessage("Orario ritardo segnalato con successo! 👍\n\nHo avvisato che arriverai alle "+mess, getMainMenuKeyboard(), chatID);
                    }
                    //##########################################################

                    else sendMessage("Comando non attivo", getMainMenuKeyboard(), chatID);
                } //hasText()

            } else sendNotRegisteredMessage(chatID);

        } //hasMessage
    }


// METHODS #############################################################

    private void sendNotRegisteredMessage(String chat_id) { sendMessage("Non sei abilitato a usare questo servizio!\n\nControlla nelle impostazioni di Telegram di aver impostato Nome, Cognome e Username correttamente!\n\nContatta la segreteria per maggiori informazioni!", chat_id); }

    public void sendMessage(String text, String chat_id) {
            SendMessage sendMessageRequest = new SendMessage();
            sendMessageRequest.setChatId(chat_id);
            sendMessageRequest.setText(text);
            try { sendMessage(sendMessageRequest);
            } catch (TelegramApiException ex) {}
    }
    public void sendMessage(String text,  InlineKeyboardMarkup keyboard, String chat_id) {
            SendMessage sendMessageRequest = new SendMessage();
            sendMessageRequest.setChatId(chat_id);
            sendMessageRequest.setText(text);
            sendMessageRequest.setReplyMarkup(keyboard);
            try { sendMessage(sendMessageRequest);
            } catch (TelegramApiException ex) {}
    }
    public void sendMessage(String text, ReplyKeyboardMarkup keyboard, String chat_id) {
            SendMessage sendMessageRequest = new SendMessage();
            sendMessageRequest.setChatId(chat_id);
            sendMessageRequest.setText(text);
            sendMessageRequest.setReplyMarkup(keyboard);
            try { sendMessage(sendMessageRequest);
            } catch (TelegramApiException ex) {}
    }
    public void sendMessageToAdmin(String text) {

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

    public void sendProfile(String username, String chatID) {
        String ans="Profilo di ";

        PreparedStatement ps;
        try {
            ps = database.getConnection().prepareStatement("SELECT nome, cognome, assenze FROM Utenti WHERE username=? AND chat_id=?");
            ps.setString(1, username);
	    ps.setString(2, chatID);

	    ResultSet rs = ps.executeQuery();
            rs.next();
            ans += rs.getString("nome") + " "+ rs.getString("cognome") + "\n\n";
            ans += "Assenze fatte: " + rs.getInt("assenze") + "\n";
            ans += "Assenze rimanenti: " + (MAX_ASSENZE - rs.getInt("assenze"));
        } catch(SQLException ex) {}

        sendMessage(ans, getMainMenuKeyboard(), chatID);
    }
    public void sendAbsence(String username, String chatID) {
        String ans="Assenza";

        PreparedStatement ps;
        try {
            ps = database.getConnection().prepareStatement("UPDATE Utenti SET assenze=assenze+1 WHERE username=? AND chat_id=?");
            ps.setString(1, username);
	    ps.setString(2, chatID);

	    if(ps.executeUpdate() == 1) ans+=" inviata con successo! 👍\n\n/annulla_segnalazione per annullare l'assenza segnalata!";
            ps.close();

        } catch(SQLException e) { ans+= " NON inviata con successo! 👎";}

        sendMessage(ans, getMainMenuKeyboard(), chatID);
    }
    public void removeAbsence(String username, String chatID) {
        String ans="Assenza";

        PreparedStatement ps;
        try {
            ps = database.getConnection().prepareStatement("UPDATE Utenti SET assenze=assenze-1 WHERE username=? AND chat_id=?");
            ps.setString(1, username);
	    ps.setString(2, chatID);

	    if(ps.executeUpdate() == 1) ans+=" rimossa con successo! 👍";
            ps.close();

        } catch(SQLException e) { ans+= " NON rimossa con successo! 👎";}

        sendMessage(ans, getMainMenuKeyboard(), chatID);
    }

    public void saveImage(List<PhotoSize> photos) {

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
    public void sendNewImage(String chat_id, String imagePath) throws TelegramApiException, IOException {
            SendPhoto sendPhotoRequest = new SendPhoto();
            sendPhotoRequest.setChatId(chat_id);
            InputStream is = new BufferedInputStream(new FileInputStream(imagePath));
            sendPhotoRequest.setNewPhoto("botimage.jpg", is);
            sendPhoto(sendPhotoRequest);
            is.close();
	}
    public void sendImageById(String chat_id, String image_id) throws TelegramApiException {
            SendPhoto sendPhotoRequest = new SendPhoto();
            sendPhotoRequest.setChatId(chat_id);
            sendPhotoRequest.setPhoto(image_id);
            sendPhoto(sendPhotoRequest);
	}
    public void sendDocumentById(String chat_id, String file_id) {
            SendDocument sendDocumentRequest = new SendDocument();
            sendDocumentRequest.setChatId(chat_id);
            sendDocumentRequest.setDocument(file_id);
            try { sendDocument(sendDocumentRequest);
            } catch (TelegramApiException ex) {}
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

    private static InlineKeyboardMarkup linkInline() {
            InlineKeyboardMarkup inlineKeyboardMarkup = new InlineKeyboardMarkup();
            
            List<List<InlineKeyboardButton>> keyboard = new ArrayList();
            
                List<InlineKeyboardButton> line1 = new ArrayList();
                    InlineKeyboardButton a = new InlineKeyboardButton(); a.setText("👥 Facebook"); a.setUrl("www.facebook.com/FreeSoundStudiesMusicAcademy");
                    line1.add(a); keyboard.add(line1);

                List<InlineKeyboardButton> line2 = new ArrayList();    
                    InlineKeyboardButton b = new InlineKeyboardButton(); b.setText("🎥 Youtube"); b.setUrl("www.youtube.com/user/FreeSoundStudies");
                    line2.add(b); keyboard.add(line2);
                    
                List<InlineKeyboardButton> line3 = new ArrayList();
                    InlineKeyboardButton c = new InlineKeyboardButton(); c.setText("🌐 Sito Web"); c.setUrl("www.freesoundstudies.it");
                    line3.add(c); keyboard.add(line3);
            
            inlineKeyboardMarkup.setKeyboard(keyboard);
            return inlineKeyboardMarkup;
    }
    private static ReplyKeyboardMarkup getMainMenuKeyboard() {
            ReplyKeyboardMarkup replyKeyboardMarkup = new ReplyKeyboardMarkup();
            replyKeyboardMarkup.setSelective(true);
            replyKeyboardMarkup.setResizeKeyboard(true);
            replyKeyboardMarkup.setOneTimeKeyboad(false);

            List<KeyboardRow> keyboard = new ArrayList();

                KeyboardRow keyboard1Row = new KeyboardRow();
                    keyboard1Row.add("👤 Profilo");
                KeyboardRow keyboard2Row = new KeyboardRow();
                    keyboard2Row.add("📰 News");
                KeyboardRow keyboard3Row = new KeyboardRow();
                    keyboard3Row.add("🅰 Segnala Assenza alle prove");
                KeyboardRow keyboard4Row = new KeyboardRow();
                    keyboard4Row.add("🕗 Segnala Ritardo alle prove");
                KeyboardRow keyboard5Row = new KeyboardRow();
                    keyboard5Row.add("🎧 Basi");
                KeyboardRow keyboard6Row = new KeyboardRow();
                    keyboard6Row.add("📎 Info");

            keyboard.add(keyboard1Row);
            keyboard.add(keyboard2Row);
            keyboard.add(keyboard3Row);
            keyboard.add(keyboard4Row);
            keyboard.add(keyboard5Row);
            keyboard.add(keyboard6Row);
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
                keyboard1Row.add("📃 Brani Prova");
            KeyboardRow keyboard2Row = new KeyboardRow();
                keyboard2Row.add("📅 Concerti");
            KeyboardRow keyboard3Row = new KeyboardRow();
                keyboard3Row.add("☎ Rubrica");
            KeyboardRow keyboard4Row = new KeyboardRow();
                keyboard4Row.add("📜 Regolamento");
            KeyboardRow keyboard5Row = new KeyboardRow();
                keyboard5Row.add("🌐 Link Esterni");

            keyboard.add(keyboardMenuRow);
            keyboard.add(keyboard1Row);
            keyboard.add(keyboard2Row);
            keyboard.add(keyboard3Row);
            keyboard.add(keyboard4Row);
            keyboard.add(keyboard5Row);

            replyKeyboardMarkup.setKeyboard(keyboard);

            return replyKeyboardMarkup;
        }
    private static ReplyKeyboardMarkup getConcertsKeyboard() {
            ReplyKeyboardMarkup replyKeyboardMarkup = new ReplyKeyboardMarkup();
            replyKeyboardMarkup.setSelective(true);
            replyKeyboardMarkup.setResizeKeyboard(true);
            replyKeyboardMarkup.setOneTimeKeyboad(false);

            List<KeyboardRow> keyboard = new ArrayList();

            KeyboardRow keyboardMenuRow = new KeyboardRow(); keyboardMenuRow.add("📱 Menu"); keyboard.add(keyboardMenuRow);
            KeyboardRow keyboard1Row = new KeyboardRow(); keyboard1Row.add("📅 Tutti gli eventi"); keyboard.add(keyboard1Row);
            KeyboardRow keyboard2Row = new KeyboardRow(); keyboard2Row.add("📅 Prossimi eventi"); keyboard.add(keyboard2Row);

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
