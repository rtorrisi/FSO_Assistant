function addAssenza(but, id) {
  var search = $(but).attr('id');
  $.post('PHP/db_updateAbsences.php',{value:"addA,"+search+","+id}, function(data){
    $("#assenze_results").html(data);
  });
}
function subAssenza(but, id) {
  var search = $(but).attr('id');
  $.post('PHP/db_updateAbsences.php',{value:"subA,"+search+","+id}, function(data){
    $("#assenze_results").html(data);
  });
}

function loadRubrica() {
        $("#search_rubrica").val('');
        $.post('PHP/db_search.php',{value:"rub"}, function(data){
          $("#rubrica_results").html(data);
        });
}
function loadBrani() {
        $("#search_brani").val('');
        $.post('PHP/db_search.php',{value:"bra"}, function(data){
          $("#brani_results").html(data);
        });
}
function loadAssenze() {
        $("#search_assenze").val('');
        $.post('PHP/db_search.php',{value:"ass"}, function(data){
          $("#assenze_results").html(data);
        });
}
function loadNews() {
        $.post('PHP/showNewsOption.php',{}, function(data){
          $("#newsOption_result").html(data);
        });

        $.post('PHP/showNews.php',{estensione:'all', admin:'all', data_start:'today', data_end:'today'}, function(data){
          $("#showNews_result").html(data);
        });
}

function loadConcerti() {
        $.post('PHP/showConcertOption.php',{}, function(data){
          $("#ConcertiOption_result").html(data);
        });

        $.post('PHP/showConcert.php',{city:'all', admin:'all', data_start:'start', data_end:'today'}, function(data){
          $("#showConcert_result").html(data);
        });
}

function newsOptionChanged() {
    var var_type = $("#opt_type").find(":selected").val();
    var var_admin = $("#opt_admin").find(":selected").val();
    var var_start = $("#opt_dataStart").find(":selected").val();
    var var_end = $("#opt_dataEnd").find(":selected").val();
    $.post('PHP/showNews.php',{estensione:var_type, admin:var_admin, data_start:var_start, data_end:var_end}, function(data){
      $("#showNews_result").html(data);
    });
}

function ConcertOptionChanged() {
    var var_city = $("#opt_city").find(":selected").val();
    var var_admin = $("#opt_admin").find(":selected").val();
    var var_start = $("#opt_dataStart").find(":selected").val();
    var var_end = $("#opt_dataEnd").find(":selected").val();
    $.post('PHP/showConcert.php',{city:var_city, admin:var_admin, data_start:var_start, data_end:var_end}, function(data){
      $("#showConcert_result").html(data);
    });
}

function searchNewsId(row) {
  window.open(
  "PHP/newsInfo.php?idNews="+$(row).attr("id"),
  "_blank"
  );
}

function searchConcertId(row) {
  window.open(
  "PHP/concertInfo.php?idConcert="+$(row).attr("id"),
  "_blank"
  );
}

function searchBranoId(row) {
  window.open(
  "PHP/braniInfo.php?idBrano="+$(row).attr("id"),
  "_blank"
  );
}

$(function() {

        $("#search_brani").keyup(function(){
          var value = $("#search_brani").val();
          $.post('PHP/db_search.php',{value:"bra"+value}, function(data){
            $("#brani_results").html(data);
          });
        });

        $("#search_rubrica").keyup(function(){
          var value = $("#search_rubrica").val();
          $.post('PHP/db_search.php',{value:"rub"+value}, function(data){
            $("#rubrica_results").html(data);
          });
        });

        $("#search_assenze").keyup(function(){
          var value = $("#search_assenze").val();
          $.post('PHP/db_search.php',{value:"ass"+value}, function(data){
            $("#assenze_results").html(data);
          });
        });
});
