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
      $('#news_pic').attr('src', "img/file_image.png");
  }

  $("#file_info").html(input.files[0].name);
  setTimeout(function() {
    $("#file_info").html("&nbsp &nbsp &nbsp &nbsp File/Foto");
  }, 2000);
  }
}

$("#news_form").submit(function(e){
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
      $("#news_result").html('<div class="success">'+ res.text +"</div>");
      setTimeout(function() {
        $("#news_result").html('<h1>Invia News!</h1>');
      }, 3000);
    }
    else if(res.type == "error"){
      $("#news_result").html('<div class="error">'+ res.text +"</div>");
      setTimeout(function() {
        $("#news_result").html('<h1>Invia News!</h1>');
      }, 3000);
	  }
  });
  });
