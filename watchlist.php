<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/form.css">
    <link rel='stylesheet prefetch' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'>
    <script src="assets/jquery/jquery-3.3.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/bootbox/bootbox.min.js"></script>
    <style>
      .box {
        background-color: rgba(0, 0, 0, 0.7);
        max-width: 100%;
        max-height: 100%;
        border: 1px solid;
        padding: 15px;
        padding-top: 0px;
      }

      .title {
        font-size: 250%;
        color:#a09785;
        font-family:'helveticaneuemedium_italic';
        font-style:italic;
        text-shadow: 1px 1px 0 #856701;
        margin-bottom: 10px;
      }

      .text {
        color: white;
        margin: 5px;
        font-size: 16px;
      }
    </style>
  </head>
  <body>
    <!-- Including header based on user loggin status -->
    <!-- To include this section at the beginning of every file's body -->
    <?php
    $logged_in = $_SESSION['logged_in'];
    if ($logged_in == TRUE) {
      echo $logged_in;
        include 'header_member.php';
    } else {
      echo $logged_in;
        include 'header.php';
    }
    ?>
    <div class="col-md-12">
      <div class="col-md-2"></div>
      <div class="col-md-8 box">
        Your watchlist will be displayed in here.
      </div>
      <div class="col-md-2"></div>
    </div>
  </body>
</html>
