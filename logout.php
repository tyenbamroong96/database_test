<?php
session_start();
$_SESSION['message'] = '';
$connectionInfo = array("UID" => "auctora@auctora-server", "pwd" => "arotcua1!", "Database" => "auctoraDB");
$serverName = "tcp:auctora-server.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);
// echo $_SESSION;

// echo '<pre>';
// var_dump($_SESSION);
// echo '</pre>';




if($_SESSION['logged_in_val'] == true){
	$_SESSION['logged_in_val'] = false;
    session_destroy();
}
//redirect the user to the home page
header("Location: index.php");

// function redirect()
// {
//    header('Location: watchlist.php');
//    die();
// }
?>
