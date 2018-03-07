<?php

session_start();
$_SESSION['message'] = '';
$connectionInfo = array("UID" => "auctora@auctora-server", "pwd" => "arotcua1!", "Database" => "auctoraDB");
$serverName = "tcp:auctora-server.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);

 echo "<br><br><br><br><br>";

echo "<h1 align='center'>Reviews for this Watch</h1>";
echo "</br>";
//ebayItem id from product_searches page
$review=$_SESSION['reviewBool'];
if($review == 1){
$ebayItemId = $_POST['ebayIDShow'];
}
else if ($review == 0){
  $ebayItemId = $_SESSION['ebayItem'];
}

$current_uid = $_SESSION['userID'];
//echo "Ebay item id is" . $ebayItemId;
//user primary key
$currentId = $_SESSION['userID'];

echo "</br>";

$query = "SELECT * FROM auction.product_searches WHERE ebayID = '$ebayItemId'";
$getMatches= sqlsrv_query($conn, $query);

$row = sqlsrv_fetch_array($getMatches, SQLSRV_FETCH_ASSOC);
$count = $row['view_count'];
//obtain below product primary key id from database
$prod_id = $row['ID'];
$_SESSION['productID'] = $row['ID'];
//echo "product1_id ";
//echo $prod_id;


echo "
<table border='1' align='center'>
<tr>
<th>Image</th>
<th>Title</th>
<th>Price</th>
<th>Service Cost</th>
<th>ebayID</th>
</tr>";


$product_link = $row['product_link'];
$title = $row['title'];
$img_src = $row['image'];
echo "<tr>";
echo "<td>" . "<a href=\"$product_link\"><img src=\"$img_src\"></a>" . "</td>";
echo "<td>" . "<a href=\"$product_link\" target=\"_blank\">$title</a>" . "</td>";
echo "<td>" . $row['price'] . "</td>";
echo "<td>" . $row['serviceCost'] . "</td>";
echo "<td>" . $row['ebayID'] . "</td>";
echo "</tr>";
echo "</table>";

echo "</br>";
//users review for the product
echo "<h4 align='center'>My Reviews for this watch</h4>";
$queryC = "SELECT * FROM auction.product_reviews WHERE user_id = '$currentId' AND product_id = '$prod_id'";
$getMatchesC= sqlsrv_query($conn, $queryC);

$rowC = sqlsrv_fetch_array($getMatchesC, SQLSRV_FETCH_ASSOC);
if($rowC)
{
  $reviewID= $rowC['ID'];
    echo "<br>";
    echo "
      <table border='1' align='center'>
      <tr>
      <th>Review</th>
      <th>Rating</th>
      <th></th>
      </tr>";


    echo "<tr>";
    echo "<td>" . $rowC['comment'] . "</td>";
    echo "<td>" . $rowC['rating'] . "</td>";
    echo "<td>" . "<form id= \"delete_item\" method=\"post\">  <button type=\"submit\" class=\"btn btn-warning\" name=\"delete_item\" onclick=\"return confirm('Remove item?');\" value=\"$reviewID\">Delete Review</button></form> </td>";
    echo "</tr>";
    echo "</table>";
}
else{
  echo "<br>
  <p align='center'>You have not given a Review. Why not add a review ?</p>";

  echo " <div style=\"text-align:center\"> <form method=\"POST\" action=\"review.php\" >  <button type=\"submit\" class=\"btn btn-danger\" name=\"ebayID\" value=\"$ebayItemId\" >Add Your Review</button></form> </div>";
}




//Display all reviews
echo "</br>";
echo "<h4 align='center'>All user Reviews for this Watch</h4>";
echo "</br>";
$tsql = "SELECT * FROM auction.product_reviews WHERE product_id = '$prod_id' AND user_id != '$currentId'";
$getResults= sqlsrv_query($conn, $tsql, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
if ($getResults == FALSE)
    die(FormatErrors(sqlsrv_errors()));

// $row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC);
$num_of_rows = sqlsrv_num_rows($getResults);
// echo "<br><br><br>num of rows: " . $num_of_rows;

if($num_of_rows > 0)
{

  echo "
    <table border='1' align='center'>
    <tr>
    <th>User</th>
    <th>Review</th>
    <th>Rating</th>
    </tr>";
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        $currnetUser = $row['user_id'];

        $queryB = "SELECT * FROM auction.users WHERE Id = '$currnetUser'";
        $getMatchesB= sqlsrv_query($conn, $queryB);

        $rowB = sqlsrv_fetch_array($getMatchesB, SQLSRV_FETCH_ASSOC);
        $first = $rowB['FirstName'];
        $second = $rowB['LastName'];
        echo "<tr>";

        echo "<td>" . $first . "</td>";
        echo "<td>" . $row['comment'] . "</td>";
        echo "<td>" . $row['rating'] . "</td>";
        echo "</tr>";

  }

echo "</table>";
}

else{
  echo "
  <p align='center'>No reviews have been added by other users so far. </p>";
}


if (isset($_POST['delete_item'])){
  $review_id = $_POST['delete_item'];
  $current_user_id = $_SESSION['userID'];

  $queryB = "SELECT * FROM auction.product_reviews WHERE ID = '$review_id' AND user_id = '$current_user_id' ";
  $getMatchesB= sqlsrv_query($conn, $queryB);
  $rowB = sqlsrv_fetch_array($getMatchesB, SQLSRV_FETCH_ASSOC);
  $ebayProductid = $rowB['product_id'];

  $tsql= "DELETE FROM auction.product_reviews WHERE ID = '$review_id' AND user_id = '$current_user_id'";
  $getResults= sqlsrv_query($conn, $tsql);

  $rowsAffected = sqlsrv_rows_affected($getResults);
  if ($getResults == FALSE or $rowsAffected == FALSE)
      die(FormatErrors(sqlsrv_errors()));
    //echo "<meta http-equiv='refresh' content='0'>";
    $value=0;
    $_SESSION['reviewBool'] = $value;





    $query = "SELECT * FROM auction.product_searches WHERE ID = '$ebayProductid'";
    $getMatches= sqlsrv_query($conn, $query);

    $row = sqlsrv_fetch_array($getMatches, SQLSRV_FETCH_ASSOC);
    $ebayIdent = $row['ebayID'];

    $_SESSION['ebayItem'] = $ebayIdent;
    header("Location: review_show.php");
}
?>



<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Scrolling Nav - Start Bootstrap Template</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/scrolling-nav.css" rel="stylesheet">

  </head>

  <body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">A U C T O R A</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="ml.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="my_watches.php">My Watches</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="watchlist.php">My Watchlist</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="products.php">Products</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

<!-- <section>
Welcome HERREE <span class="user"><?= $_SESSION['firstname'] ?></span>
</section> -->



    <!-- Footer -->


    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom JavaScript for this theme -->
    <script src="js/scrolling-nav.js"></script>

  </body>

</html>
