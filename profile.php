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
    <title>FSO Assistant Manager</title>
    <link rel='stylesheet' href="css/font.css" type='text/css'>
    <link rel="stylesheet" href="css/style.css">
  </head>

  <body>
    <div class="form" style="max-width: 600px">

      <ul class="tab-group">
        <li class="tab active"><a style="width: 16.66%" href="#news">News</a></li>
        <li class="tab"><a style="width: 16.66%" href="#assenze"  onclick="loadAssenze()">Assenze</a></li>
        <li class="tab"><a style="width: 16.66%" href="#brani" onclick="loadBrani()">Brani</a></li>
        <li class="tab"><a style="width: 16.66%" href="#concerti">Concerti</a></li>
        <li class="tab"><a style="width: 16.66%" href="#rubrica" onclick="loadRubrica()">Rubrica</a></li>
        <li class="tab"><a style="width: 16.66%" href="#profilo">Profilo</a></li>
      </ul>

      <div class="tab-content">



<!-- NEWS -->
  <div id="news">

      <ul class="tab2-group">
        <li class="tab2 active" id="newst1"><a style="width: 50%" href="#news1">Invia news</a></li>
        <li class="tab2" id="newst2"><a style="width: 50%" href="#news2" onclick="loadNews()">Visualizza news</a></li>
      </ul>

    <div class="tab2-content">

<!-- INVIA NEWS -->
      <div id="news1">

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

<!-- ASSENZE -->
  <div id="assenze" style="display: none"><br>

        <div class="field-wrap">
          <label> Search </label> <input type="text" id="search_assenze" required autocomplete="off"/>
        </div>
        <div id="assenze_results"></div>

  </div><!-- /prove-->

<!-- BRANI -->
<div id="brani" style="display: none">

      <ul class="tab2-group">
        <li class="tab2 active" id="branit1"><a style="width: 33%" href="#brani1" onclick="loadBrani()"> Visualizza Brani</a></li>
        <li class="tab2" id="branit2"><a style="width: 34%" href="#brani2"> Inserisci Brani</a></li>
        <li class="tab2" id="branit3"><a style="width: 33%" href="#brani3" onclick="resetBasiField()"> Inserisci Base</a></li>
      </ul>

  <div class="tab2-content">

    <!-- VISUALIZZA BRANI -->
    <div id="brani1"><br>

      <div class="field-wrap">
        <label> Search </label> <input type="text" id="search_brani" required autocomplete="off"/>
      </div>
      <div id="brani_results"></div>

    </div> <!-- /BRANI1 -->

    <!-- INSERISCI BRANI -->
    <div id="brani2" style="display: none">

      <div id="newBrano_result"> <h1> Inserisci nuovo brano! </h1> </div>
      <form id="brani_form" action="addBrano.php" method="post">
        <div class="field-wrap">
          <label> Titolo <span class="req">*</span></label>
          <input type="text" name="titolo" required autocomplete="off"/>
        </div>
        <div class="field-wrap">
          <label> Autore </label>
          <input type="text" name="autore"/>
        </div>
        <button type="submit" class="button button-block"/> Aggiungi Brano </button>
      </form>

    </div> <!-- /BRANI2 -->

    <!-- INSERISCI BASE -->
    <div id="brani3" style="display: none">
      <div id="newBase_result"> <h1> Inserisci nuova base! </h1> </div>
        <form id="basi_form" action="addBase.php" method="post">
          <div class="top-row">
          <div class="field-wrap">
            <label> idBrano  <span class="req">*</span></label>
            <input id="idBrano" type="text" name="idBrano" required autocomplete="off"/>
          </div>
          <div class="field-wrap">
            <select id="tipologia" name="type">
              <option value="completa"> Completa </option>
              <option value="ritmica"> Ritmica </option>
              <option value="archi"> Archi </option>
              <option value="voci"> Voci </option>
            </select>
          </div>
        </div>
        <div class="field-wrap">
          <input  id="audio" type="file" name="file_attach[]"/>
        </div>
        <button type="submit" class="button button-block"/> INSERISCI BASE </button>
      </form>
    </div><!-- /BRANI3 -->

  </div>

</div><!-- /BRANI-->

<!-- CONCERTI -->
<div id="concerti" style="display: none">
  <h1> Concerti! </h1>
</div> <!-- /concerti-->

<!-- RUBRICA -->
<div id="rubrica" style="display: none"><br>

  <div class="field-wrap">
    <label> Search </label> <input type="text" id="search_rubrica" required autocomplete="off"/>
  </div>

  <div id="rubrica_results"></div>

</div>

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
    <script src="js/ajax_insert_sendNews.js"></script>
    <script src="js/ajax_search_update.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
  </body>
</html>
