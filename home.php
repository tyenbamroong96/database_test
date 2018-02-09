<?php
session_start();
$_SESSION['message'] = '';
$serverName = "tcp:ragnasvr.database.windows.net, 1433";
$connectionOptions = array(
    "Database" => "ragnaDB",
    "Uid" => "ragnarok@ragnasvr",
    "PWD" => "Korangar2"
);
//Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);



//the form has been submitted with post
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tsql= "SELECT Email, Password FROM auctionUsers.users;";
    $getResults= sqlsrv_query($conn, $tsql);
    // echo ("Reading data from table" . PHP_EOL);
    if ($getResults == FALSE)
        die(FormatErrors(sqlsrv_errors()));
    // while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
    //     echo ($row['Email'] . " " . $row['Password'] . PHP_EOL);

    // }
    $pswd = md5($_POST['password']);
    $email = $_POST['email'];
    // printf($password . " " . $email);

    $query = "SELECT * FROM auctionUsers.users WHERE Email = '$email' AND Password = '$pswd'";
    $getMatches= sqlsrv_query($conn, $query);

    if($getMatches){
    $row = sqlsrv_fetch_array($getMatches, SQLSRV_FETCH_ASSOC);
    if($row){
      header("Location: welcome.php");
      sqlsrv_free_stmt($getResults);
      exit;
    }

    }
    else {
      echo "Incorrect Password or Username";
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
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
    // echo $logged_in;
    if ($logged_in == TRUE) {
      // echo "true";
        include 'header_member.php';
    } else {
      // echo "false";
        include 'header.php';
    }
    ?>
    <div class="col-md-12">
      <div class="col-md-2"></div>
      <div class="col-md-8 box">
        <!-- Include from Pius' file  -->
        <?php include(''); ?>
      </div>
      <div class="col-md-2"></div>
    </div>
  </body>
</html>
