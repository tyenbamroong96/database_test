<?php
session_start();

$_SESSION['message'] = '';
$_SESSION['firstname'] = '';
$connectionInfo = array("UID" => "auctora@auctora-server", "pwd" => "arotcua1!", "Database" => "auctoraDB");
$serverName = "tcp:auctora-server.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);
//Establishes the connection




//the form has been submitted with post
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tsql= "SELECT Email, Password FROM auction.users;";
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

    $query = "SELECT * FROM auction.users WHERE Email = '$email' AND Password = '$pswd'";
    $getMatches= sqlsrv_query($conn, $query);

    $row = sqlsrv_fetch_array($getMatches, SQLSRV_FETCH_ASSOC);
    if($row){
      // $_SESSION['logged_in'] = true;
      $_SESSION['logged_in_val'] = true;
      $_SESSION['firstname'] = $row['FirstName'];
      $_SESSION['user_id'] = $row['Email'];
      header("Location: products.php");
      sqlsrv_free_stmt($getResults);
      exit;
    }
    else {
      $_SESSION['message'] = 'Incorrect Email/Password';
    }
}
?>


<html>
<link href="https://db.onlinewebfonts.com/c/a4e256ed67403c6ad5d43937ed48a77b?family=Core+Sans+N+W01+35+Light" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="form.css" type="text/css">
<div class="body-content">






  <div class="module">
    <h1 align="center">AUCTORA</h1>
    <h1 align="center">Login</h1>
    <form class="form" action="index.php" method="post" enctype="multipart/form-data" autocomplete="off">

      <div class="alert alert-error"><?= $_SESSION['message'] ?></div>
      <input type="email" placeholder="Email" name="email" required />
      <input type="password" placeholder="Password" name="password" autocomplete="new-password" required />
      <input type="submit" value="Login" name="Register" class="btn btn-block btn-primary" />

    </form>

    <br>
    <center><button type="button" class="btn btn-warning" onclick="location.href = 'register.php';">Register</button></center>
  </div>


</div>
</html>
