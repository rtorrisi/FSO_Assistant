  function loadFile() { $("input[id='file']").click(); };
  function checkProfilePic() {
    alert("qui controllare se username è presente nel db e mostrare l'immagine");
  }
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#profile_pic').attr('src', e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#sign_up_form").submit(function(e){
  e.preventDefault();
  $.ajax({
    url : "PHP/sign_up.php",
		type: "post",
		data : new FormData(this),
		dataType : "json",
		contentType: false,
		cache: false,
		processData:false
	})
  .done(function(res){
    if(res.type == "done"){
      $("#sign_up_result").html('<div class="success">'+ res.text +"</div>");
      setTimeout(function() {
        $("#sign_up_result").html('<h1>Sign Up Now!</h1>');
      }, 3000);
    }
    else if(res.type == "error"){
      $("#sign_up_result").html('<div class="error">'+ res.text +"</div>");
      setTimeout(function() {
        $("#sign_up_result").html('<h1>Sign Up Now!</h1>');
      }, 3000);
	  }
  });
  });
