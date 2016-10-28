<!DOCTYPE html>
<?php
  error_reporting(E_ALL); ini_set('display_errors', 1);

  $db_name = "FSO_Database";
  $user = "root";
  $password = "admin";

  $conn = mysqli_connect("localhost", $user, $password, $db_name);
  if (!$conn) { exit; }
?>

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
        <li class="tab active"><a style="width: 20%" href="#prove">Prove</a></li>
        <li class="tab"><a style="width: 20%" href="#news">News</a></li>
        <li class="tab"><a style="width: 20%" href="#basi">Basi</a></li>
        <li class="tab"><a style="width: 20%" href="#concerti">Concerti</a></li>
        <li class="tab"><a style="width: 20%" href="#rubrica" onclick="loadRubrica()">Rubrica</a></li>
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

        <div id="news" style="display: none">
          <h1>News!</h1>
        </div>

        <div id="basi" style="display: none">
          <h1>Basi!</h1>
        </div>

        <div id="concerti" style="display: none">
          <ul class="tab2-group">
            <li class="tab2" id="concertit1"><a style="width: 33%" href="#concerti1">A</a></li>
            <li class="tab2" id="concertit2"><a style="width: 34%" href="#concerti2">B</a></li>
            <li class="tab2" id="concertit3"><a style="width: 33%" href="#concerti3">C</a></li>
          </ul>

          <div class="tab2-content">

            <div id="concerti1" style="display: none">
              <h1>A</h1>
            </div>

            <div id="concerti2" style="display: none">
              <h1>B</h1>
            </div>

            <div id="concerti3" style="display: none">
              <h1>C</h1>
            </div>

          </div> <!-- /tab2-content-->
        </div> <!-- /concerti-->

        <div id="rubrica" style="display: none"><br>
          <div class="field-wrap">
            <label> Search </label> <input type="text" id="search_rubrica" required autocomplete="off"/>
          </div>

          <div id="rubrica_results"></div>

        </div> <!-- /rubrica-->

      </div> <!-- /tab-content -->
    </div> <!-- /form -->

    <script src="js/form.js"></script>
    <script src="js/index.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript">
      function loadRubrica() {
        $.post('db_search.php',{value:"rub"}, function(data){
          $("#rubrica_results").html(data);
        });
      }
      function loadAssenze() {
        $.post('db_search.php',{value:"ass"}, function(data){
          $("#assenze_results").html(data);
        });
      }

      $(function() {
        $("#search_rubrica").keyup(function(){
          var value = $("#search_rubrica").val();
          $.post('db_search.php',{value:"rub"+value}, function(data){
            $("#rubrica_results").html(data);
          });
        });

        $("#search_assenze").keyup(function(){
          var value = $("#search_assenze").val();
          $.post('db_search.php',{value:"ass"+value}, function(data){
            $("#assenze_results").html(data);
          });
        });
      });
    </script>

  </body>
</html>
