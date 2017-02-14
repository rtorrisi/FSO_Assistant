function loadFile() { $("input[id='file']").click(); };

function readURL(input) {
  if (input.files && input.files[0]) {
    if (input.files[0].name.match(/\.(jpg|jpeg|png)$/)) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#news_pic').attr('src', e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
  }
  else { //se non un immagine
      $('#news_pic').attr('src', "Data/website_img/file_default.png");
  }

  $("#file_name_loaded").html("File caricato: "+input.files[0].name);
  }
}

function resetBasiField() {
  $("#idBrano").val('');
  $("#tipologia").prop('selectedIndex',0);
  $("#audio").val("");
}

$("#news_form").submit(function(e){
  $("#news_result").html('<h1> Invio in corso... </h1>');
  e.preventDefault();
  $.ajax({
    url : "PHP/sendNews.php",
		type: "post",
		data : new FormData(this),
		dataType : "json",
		contentType: false,
		cache: false,
		processData:false
	})
  .done(function(res){
    if(res.type == "done"){
      $("#news_result").html('<div class="success">'+ res.text +'</div>');
      setTimeout(function() {
        $("#news_result").html('<h1>Invia News!</h1>');
      }, 3000);
    }
    else if(res.type == "error"){
      $("#news_result").html('<div class="error">'+ res.text +'</div>');
      setTimeout(function() {
        $("#news_result").html('<h1>Invia News!</h1>');
      }, 3000);
	  }
  });
  });

  $("#brani_form").submit(function(e){
    e.preventDefault();
    $.ajax({
      url : "PHP/addBrano.php",
  		type: "post",
  		data : new FormData(this),
  		dataType : "json",
  		contentType: false,
  		cache: false,
  		processData:false
  	})
    .done(function(res){
      if(res.type == "done"){
        $("#newBrano_result").html('<div class="success">'+ res.text +"</div>");
        setTimeout(function() {
          $("#newBrano_result").html('<h1>Inserisci nuovo brano!</h1>');
        }, 3000);
      }
      else if(res.type == "error"){
        $("#newBrano_result").html('<div class="error">'+ res.text +"</div>");
        setTimeout(function() {
          $("#newBrano_result").html('<h1>Inserisci nuovo brano!</h1>');
        }, 3000);
  	  }
    });
    });

    $("#basi_form").submit(function(e){
      $("#newBase_result").html('<h1> Inserimento base in corso... </h1>');
      e.preventDefault();
      $.ajax({
        url : "PHP/addBase.php",
    		type: "post",
    		data : new FormData(this),
    		dataType : "json",
    		contentType: false,
    		cache: false,
    		processData:false
    	})
      .done(function(res){
        if(res.type == "done"){
          $("#newBase_result").html('<div class="success">'+ res.text +"</div>");
          setTimeout(function() {
            $("#newBase_result").html('<h1> Inserisci nuova base! </h1>');
          }, 3000);
        }
        else if(res.type == "error"){
          $("#newBase_result").html('<div class="error">'+ res.text +"</div>");
          setTimeout(function() {
            $("#newBase_result").html('<h1> Inserisci nuova base! </h1>');
          }, 3000);
    	  }
      });
      });
