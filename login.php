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
      $_SESSION['logged_in'] = 'TRUE';
      $_SESSION['firstname'] = $row['FirstName'];
      header("Location: home.php");
      sqlsrv_free_stmt($getResults);
    }

    }
    else {
      echo "Incorrect Password or Username";
    }

    // while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
    // // echo ($row['Email'] . " " . $row['Password'] . PHP_EOL);

    //   if($row['Password'] == $pswd){
    //   header("Location: register.php");
    //   sqlsrv_free_stmt($getResults);
    //   exit;
    // }

    // }

    // $_SESSION['message'] = 'Incorrect Password or Username';

    //two passwords are equal to each other
    // if ($_POST['userPass'] == $_POST['password']) {
    //     //set all the post variables
    //     $first_name = $_POST['firstName'];
    //     $last_name = $_POST['lastName'];
    //     $email = $_POST['userEmail'];
    //     $password = md5($_POST['userPass']); //md5 has password for security

    //     // echo ("Inserting a new row into table" . PHP_EOL);
    //     $tsql= "INSERT INTO auctionUsers.users (Email, Password, FirstName, LastName) VALUES (?,?,?,?);";
    //     $params = array($email,$password,$first_name,$last_name);
    //     $getResults= sqlsrv_query($conn, $tsql, $params);
    //     $rowsAffected = sqlsrv_rows_affected($getResults);
    //     if ($getResults == FALSE or $rowsAffected == FALSE)
    //         die(FormatErrors(sqlsrv_errors()));
    //     // echo ($rowsAffected. " row(s) inserted: " . PHP_EOL);
    //     header("Location: login.php");
    //     sqlsrv_free_stmt($getResults);
    //     exit;

    // }
    // else {
    //     $_SESSION['message'] = 'Two passwords do not match!';
    // }
}
?>



<!DOCTYPE html>
<html >
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
    .login-card input[type=email], input[type=password] {
      height: 44px;
      font-size: 16px;
      width: 100%;
      margin-bottom: 10px;
      -webkit-appearance: none;
      background: #fff;
      border: 1px solid #d9d9d9;
      border-top: 1px solid #c0c0c0;
      /* border-radius: 2px; */
      padding: 0 8px;
      box-sizing: border-box;
      -moz-box-sizing: border-box;
    }

    .login-card input[type=email]:hover, input[type=password]:hover {
      border: 1px solid #b9b9b9;
      border-top: 1px solid #a0a0a0;
      -moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
      -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
      box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
    }
    </style>
  </head>

  <body>
    <!-- include header -->
    <?php include 'header.php'; ?>
    <div style="padding: 70px 0;">
      <div class="login-card" >
        <h1>Log-in</h1><br>
        <form action="" id="login_form" method="post">
          <input type="email" name="email" placeholder="Email" id="email">
          <input type="password" name="password" placeholder="Password">

          <!-- Displayed when enter incorrect user credential -->
          <!-- <div class="hidden alert alert-danger" style="padding:5px;margin-bottom:5px;" id="hidden_text" >Please try again. Incorrect username or password.</div> -->

          <input type="submit" class="login login-submit" style="margin:auto;" value="login"></br>
        </form>

        <div class="login-help">
          <a href="index.php">Register</a> • <a href="#">Forgot Password</a>
        </div>
      </div>
    </div>

  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>

  </body>
</html>
