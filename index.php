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
    
    //two passwords are equal to each other
    if ($_POST['userPass'] == $_POST['password']) {
        //set all the post variables
        $first_name = $_POST['firstName'];
        $last_name = $_POST['lastName'];
        $email = $_POST['userEmail'];
        $password = md5($_POST['userPass']); //md5 has password for security

        $query = "SELECT * FROM auctionUsers.users WHERE Email = '$email'";
        $getMatches= sqlsrv_query($conn, $query);

        if($getMatches){
        $row = sqlsrv_fetch_array($getMatches, SQLSRV_FETCH_ASSOC);
        if($row){
          header("Location: index.php");
          sqlsrv_free_stmt($getResults);
          exit;
        }
      }

        // echo ("Inserting a new row into table" . PHP_EOL);
        $tsql= "INSERT INTO auctionUsers.users (Email, Password, FirstName, LastName) VALUES (?,?,?,?);";
        $params = array($email,$password,$first_name,$last_name);
        $getResults= sqlsrv_query($conn, $tsql, $params);
        $rowsAffected = sqlsrv_rows_affected($getResults);
        if ($getResults == FALSE or $rowsAffected == FALSE)
            die(FormatErrors(sqlsrv_errors()));
        // echo ($rowsAffected. " row(s) inserted: " . PHP_EOL);
        header("Location: login.php");
        sqlsrv_free_stmt($getResults);
        exit;

    }
    else {
        $_SESSION['message'] = 'Two passwords do not match!';
    }
}
?>



<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
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
      height: 100%;
      padding: 13px;
      border: 1px solid;
    }

    .text {
      color: orange;
    }

    input[type="text"],  input[type="password"], input[type="email"] {
      color: black;
      width: 100%;
    }

    td {
      border-top: none !important;
    }
    </style>
  </head>
  <body>
    <!-- include header -->
    <?php include 'header.php'; ?>
    <div class="col-md-12">
      <div class="col-md-2"></div>
      <div class="col-md-8 container-fluid box">
        <div style="color: grey; margin-bottom: 5px;">
          Please fill in the form below.
        </div>
        <table class="table text">
          <tbody>
            <form action="" id="signup" method="post">
              <tr>
                <td class="col-md-2"><label for="userTitle">Title: </label></td>
                <td class="col-md-10"><input type="text" name="userTitle" id="userTitle" placeholder="Title"></td>
              </tr>
              <tr>
                <td><label for="firstName">First name: </label></td>
                <td><input type="text" name="firstName" id="firstName" placeholder="First name" class="info"></td>
              </tr>
              <tr>
                <td><label for="lastName">Last name: </label></td>
                <td><input type="text" name="lastName" id="lastName" placeholder="Last name" class="info"></td>
              </tr>
              <tr>
                <td><label for="userEmail">Email: </label></td>
                <td><input required type="email" name="userEmail" id="userEmail" placeholder="Email" class="info"></td>
              </tr>
              <tr>
                <td><label for="userPass">Password: </label></td>
                <td><input required type="password" name="userPass" id="pass" placeholder="Password" class="info"></td>
              </tr>
              <tr>
                <td><label for="password">Re-enter password: </label></td>
                <td><input required type="password" name="password" id="pass2" placeholder="Re-enter password" class="info"></td>
              </tr>
              <tr>
                <td></td>
                <td><input type="submit" value="Sign Up" class="btn btn-primary"></td>
              </tr>
            </form>
          </tbody>
        </table>
      </div>
      <div class="col-md-2"></div>
    </div>
  </body>
</html>
