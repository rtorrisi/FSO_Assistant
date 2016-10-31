function loadFile() { $("input[id='file']").click(); };
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#news_pic').attr('src', e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
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