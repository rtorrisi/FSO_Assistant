<?php
include 'PHP/login.php';
if ( (isset($_SESSION['username'])!='') ) header('Location: profile.php');
?>

<!DOCTYPE html>
<html >
  <head>

    <meta charset="UTF-8">
    <title>Sign-Up/Login Form</title>
    <link rel='stylesheet' href="css/font.css" type='text/css'>
    <link rel="stylesheet" href="css/style.css">

  </head>

  <body>

    <div class="form">

      <ul class="tab-group">
        <li class="tab active"><a href="#login">Log In</a></li>
        <li class="tab"><a href="#signup">Sign Up</a></li>
      </ul>

      <div class="tab-content">

        <!-- SIGN UP ################################### -->
        <div id="signup" style="display: none">
          <div id="sign_up_result"> <h1>Sign Up Now!</h1> </div>

          <form id="sign_up_form" action="" method="post">
            <input type="file" name="file_attach[]" id="file" onchange="readURL(this)" accept="image/gif, image/jpeg, image/png"/>
            <img id="profile_pic" src="Data/website_img/profile_default.png" onclick="loadFile()"/>
            <div class="top-row">
              <div class="field-wrap">
                <label>
                  Name<span class="req">*</span>
                </label>
                <input type="text" name="name" required autocomplete="off" />
              </div>
              <div class="field-wrap">
                <label>
                Surname<span class="req">*</span>
                </label>
                <input type="text" name="surname" required autocomplete="off"/>
              </div>
            </div>
            <div class="field-wrap">
              <label>
                Chat ID<span class="req">*</span>
              </label>
              <input type="chatid" name="chat_id" required autocomplete="off"/>
            </div>
            <div class="field-wrap">
              <label>
                Username<span class="req">*</span>
              </label>
              <input type="username" name="username" required autocomplete="off"/>
            </div>
            <div class="field-wrap">
              <label>
                Password<span class="req">*</span>
              </label>
              <input type="password" name="password" required autocomplete="off"/>
            </div>
            <button type="submit" class="button button-block"/>Sign Up</button>
          </form>

        </div>

        <!-- LOG IN ################################### -->
        <div id="login">
          <div id="log_in_result"> <h1>Welcome Back!</h1> </div>

          <img id="show_pic" src="Data/website_img/profile_default.png">

          <form id="log_in" action="" method="post">
            <div class="field-wrap">
              <label>
                Username<span class="req">*</span>
              </label>
              <input type="username" name="username" required autocomplete="off" id="username" onkeyup="checkProfilePic()"/>
            </div>
            <div class="field-wrap">
              <label>
                Password<span class="req">*</span>
              </label>
              <input type="password" name="password" required autocomplete="off"/>
            </div>
            <button name="submit" type="submit" class="button button-block"/>Sign Up</button>
          </form>

        </div>

      </div><!-- tab-content -->

</div> <!-- /form -->
  </body>
</html>

<script src="js/form.js"></script>
<script src="js/index.js"></script>
<script src="js/login_signup.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
