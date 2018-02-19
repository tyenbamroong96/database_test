<?php
session_start();
$_SESSION['message'] = '';
$serverName = "tcp:ragnasvr.database.windows.net, 1433";
$connectionOptions = array(
    "Database" => "ragnaDB",
    "Uid" => "ragnarok@ragnasvr",
    "PWD" => "Korangar2"
);
// echo $_SESSION;

// echo '<pre>';
// var_dump($_SESSION);
// echo '</pre>';


//Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

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