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
    if ($_POST['password'] == $_POST['confirmpassword']) {
        //set all the post variables
        $first_name = $_POST['firstname'];
        $last_name = $_POST['lastname'];
        $email = $_POST['email'];
        $password = md5($_POST['password']); //md5 has password for security

        $query = "SELECT * FROM auction.users WHERE Email = '$email'";
        $getMatches= sqlsrv_query($conn, $query);

        $row = sqlsrv_fetch_array($getMatches, SQLSRV_FETCH_ASSOC);
        if($row){
          $_SESSION['message'] = 'User already exists';
          // exit;
        }
      else
      {
        // echo ("Inserting a new row into table" . PHP_EOL);
        $tsql= "INSERT INTO auction.users (FirstName, LastName, Email, Password) VALUES (?,?,?,?);";
        $params = array($first_name, $last_name, $email, $password);
        $getResults= sqlsrv_query($conn, $tsql, $params);
        $rowsAffected = sqlsrv_rows_affected($getResults);
        if ($getResults == FALSE or $rowsAffected == FALSE)
            die(FormatErrors(sqlsrv_errors()));
        // echo ($rowsAffected. " row(s) inserted: " . PHP_EOL);
        $_SESSION['username'] = $first_name;
        header("Location: index.php");
        sqlsrv_free_stmt($getResults);
        exit;
      }

        

    }
    else {
        $_SESSION['message'] = 'Two passwords do not match!';
    }
}
?>


<html>
<link href="https://db.onlinewebfonts.com/c/a4e256ed67403c6ad5d43937ed48a77b?family=Core+Sans+N+W01+35+Light" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="form.css" type="text/css">
<div class="body-content">
  <div class="module">
    <h1 align="center">AUCTORA</h1>
    <h1 align="center">Create an account</h1>
    <form class="form" action="register.php" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="alert alert-error"><?= $_SESSION['message'] ?></div>
      <input type="text" placeholder="First Name" name="firstname" required />
      <input type="text" placeholder="Last Name" name="lastname" required />
      <input type="email" placeholder="Email" name="email" required />
      <input type="password" placeholder="Password" name="password" autocomplete="new-password" required />
      <input type="password" placeholder="Confirm Password" name="confirmpassword" autocomplete="new-password" required />
      <input type="submit" value="Register" name="register" class="btn btn-block btn-primary" />

    </form>
    <br>
    <center><button type="button" class="btn btn-warning" onclick="location.href = 'index.php';">Login</button></center>
  </div>
</div>
</html>









