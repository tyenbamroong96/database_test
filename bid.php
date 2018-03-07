<?php
session_start();
$_SESSION['message'] = '';
$connectionInfo = array("UID" => "auctora@auctora-server", "pwd" => "arotcua1!", "Database" => "auctoraDB");
$serverName = "tcp:auctora-server.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);

$ebayItemId = $_POST['ebayID'];
//echo $ebayItemId;
//$some=	202241977639;
// $_SESSION['ebayItem'] = $ebayItemId;
//echo "it is";
//echo $_SESSION['ebayItem'];
$current_uid = $_SESSION['userID'];
// echo "Ebay item id is" . $ebayItemId;
//user primary key
$currentId = $_SESSION['userID'];

function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}

if(isset($_POST['bid_placed']))
{	
	// debug_to_console($prod_id);
	// debug_to_console($current_uid);
	$product_id = $_SESSION['productID'];
	$query = "SELECT * FROM auction.watch_list WHERE ebayID = '$product_id' AND user_id = '$current_uid'";
    $getMatches2= sqlsrv_query($conn, $query, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
    $row_2 = sqlsrv_fetch_array($getMatches2, SQLSRV_FETCH_ASSOC);
    $num_of_rows = sqlsrv_num_rows($getMatches2);
    $value = $_POST['bid'];
    if($num_of_rows>0)
    {	
    	$update_bid = "UPDATE auction.watch_list SET my_bid = '$value' WHERE ebayID = '$product_id' AND user_id = '$current_uid'";
    	$getMatches2= sqlsrv_query($conn, $update_bid);    	
		// debug_to_console($value);
		header("Location: watchlist.php");
		exit;

    }
    else{
    	$tsql= "INSERT INTO auction.watch_list (ebayID, user_id, my_bid) VALUES (?,?,?);";
	    $params = array($product_id, $_SESSION['userID'], $value);
	    $getResults= sqlsrv_query($conn, $tsql, $params);
	    header("Location: watchlist.php");
		exit;
    }

}

echo "<br><br><br><br><br>";

echo "<h1 align='center'>Place your bid</h1>";
echo "</br>";
//ebayItem id from product_searches page


$query = "SELECT * FROM auction.product_searches WHERE ebayID = '$ebayItemId'";
$getMatches= sqlsrv_query($conn, $query);

$row = sqlsrv_fetch_array($getMatches, SQLSRV_FETCH_ASSOC);
$count = $row['view_count'];
//obtain below product primary key id from database
$prod_id = $row['ID'];
$_SESSION['productID'] = $row['ID'];


// echo "here" . $prod_id;





// echo "product1_id ";
// echo $prod_id;


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
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <!-- Custom styles for this template -->
    <link href="css/scrolling-nav.css" rel="stylesheet">

    <script src="ckeditor/ckeditor.js"></script>

    

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

    <div align='center'>
    <form method="POST" action="bid.php">  
	  Your Bid:<br>
	  <input type="number" name="bid"><br>
	  <button type="submit" class="btn btn-danger" name="bid_placed" >Place bid</button>
	</form>
</div>
</br>
</br>


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