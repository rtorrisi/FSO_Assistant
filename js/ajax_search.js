function loadRubrica() {
        $.post('PHP/db_search.php',{value:"rub"}, function(data){
          $("#rubrica_results").html(data);
        });
      }
      function loadAssenze() {
        $.post('PHP/db_search.php',{value:"ass"}, function(data){
          $("#assenze_results").html(data);
        });
      }

      $(function() {
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
