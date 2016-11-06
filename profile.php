<?php
include 'PHP/db_connection.php';

session_start();
$user = $_SESSION['username'];
if(!isset($user)) header("Location: index.php");

$sql = mysqli_query($conn,"SELECT * FROM Admin WHERE username='$user' ");
$row=mysqli_fetch_array($sql);
$login_username = $row['username'];
$login_name = $row['nome'];
$login_surname = $row['cognome'];
$login_chatid = $row['chat_id'];
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Sign-Up/Login Form</title>
    <link rel='stylesheet' href="css/font.css" type='text/css'>
    <link rel="stylesheet" href="css/style.css">
  </head>

  <body>
    <div class="form" style="max-width: 600px">

      <ul class="tab-group">
        <li class="tab active"><a style="width: 16.66%" href="#prove">Prove</a></li>
        <li class="tab"><a style="width: 16.66%" href="#news">News</a></li>
        <li class="tab"><a style="width: 16.66%" href="#basi">Basi</a></li>
        <li class="tab"><a style="width: 16.66%" href="#concerti">Concerti</a></li>
        <li class="tab"><a style="width: 16.66%" href="#rubrica" onclick="loadRubrica()">Rubrica</a></li>
        <li class="tab"><a style="width: 16.66%" href="#profilo">Profilo</a></li>
      </ul>

      <div class="tab-content">

        <div id="prove">
          <ul class="tab2-group">
            <li class="tab2 active" id="provet1"><a style="width: 33%" href="#prove1">Calendario Prove</a></li>
            <li class="tab2" id="provet2"><a style="width: 34%" href="#prove2" onclick="loadAssenze()">Assenze</a></li>
            <li class="tab2" id="provet3"><a style="width: 33%" href="#prove3">Brani Prove</a></li>
          </ul>

          <div class="tab2-content">

            <div id="prove1">
              <h1>Calendario Prove</h1>
            </div>

            <div id="prove2" style="display: none"><br>

              <div class="field-wrap">
                <label> Search </label> <input type="text" id="search_assenze" required autocomplete="off"/>
              </div>

              <div id="assenze_results"></div>

            </div> <!-- /tab2 assenze-->

            <div id="prove3" style="display: none">
              <h1>Brani Prove</h1>
            </div>

          </div><!-- /tab2-content-->
        </div><!-- /prove-->

<!-- NEWS -->
  <div id="news" style="display: none">

      <ul class="tab2-group">
        <li class="tab2" id="newst1"><a style="width: 50%" href="#news1">Invia news</a></li>
        <li class="tab2" id="newst2"><a style="width: 50%" href="#news2" onclick="loadNews()">Visualizza news</a></li>
      </ul>

    <div class="tab2-content">

<!-- INVIA NEWS -->
      <div id="news1" style="display: none">

        <div id="news_result"> <h1>Invia News!</h1> </div>

        <form id="news_form" action="sendNews.php" method="post">
                  <div style="float: left">
                    <div class="field-wrap">
                      <label> News <span class="req">*</span> </label>
                      <textarea name="message"></textarea>
                    </div>
                  </div>
                  <div style="float: right">
                    <div class="field-wrap">
                      <label id="file_info" style="transform: translateY(-27px); color: white">&nbsp &nbsp &nbsp &nbsp File/Foto </label>
                      <input type="file" name="file_attach[]" id="file" onchange="readURL(this)"/>
                      <img id="news_pic" src="Data/website_img/file_default.png" onclick="loadFile()"/>
                    </div>
                  </div>
                  <div style="field-wrap">
                    <select name="type">
                      <option value="text">Testo</option>
                      <option value="image">Immagine</option>
                      <option value="file">File</option>
                  </select>
                </div><br>
                <div id="file_name_loaded">File caricato: nessuno </div><br>

                <button type="submit" class="button button-block"/>Invia News</button>
        </form>
      </div>
<!-- VISUALIZZA NEWS -->
      <div id="news2" style="display: none">
        <div id="newsOption_result"> </div>
        <div id="showNews_result"> </div>
      </div>


    </div>
  </div>
<!-- /NEWS -->

        <div id="basi" style="display: none">
          <h1>Basi!</h1>
        </div>

        <div id="concerti" style="display: none">
          <h1> Concerti! </h1>
        </div> <!-- /concerti-->

        <div id="rubrica" style="display: none"><br>
          <div class="field-wrap">
            <label> Search </label> <input type="text" id="search_rubrica" required autocomplete="off"/>
          </div>

          <div id="rubrica_results"></div>

        </div> <!-- /rubrica -->

        <div id="profilo" style="display: none">
            <?php
            echo "<table align='center' style='max-width:50%'>";
            echo "<tr> <th> Nome e Cognome </th><td>".$login_name." ".$login_surname."</td> </tr>";
            echo "<tr> <th> Username </th><td>".$login_username."</td> </tr>";
            echo "<tr> <th> Chat ID </th><td>".$login_chatid."</td> </tr>";
            echo "</table><br>";
            ?>
            <input type="button" onclick="location.href='../FSO_Assistant/PHP/logout.php';" value="Logout" />
        </div> <!-- /profilo -->

      </div> <!-- /tab-content -->
    </div> <!-- /form -->

    <script src="js/form.js"></script>
    <script src="js/index.js"></script>
    <script src="js/sendNews.js"></script>
    <script src="js/ajax_search_update.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
  </body>
</html>
