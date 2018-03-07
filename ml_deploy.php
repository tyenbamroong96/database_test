<?php

/*
                              WARNING!!!!!!!!!!!!!!!!!!!!
INCLUDING THIS FILE MIGHT INCUR API COSTS BY USING AZURE ML WEB API.

*/

session_start();
$connectionInfo = array("UID" => "auctora@auctora-server", "pwd" => "arotcua1!", "Database" => "auctoraDB");
$serverName = "tcp:auctora-server.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);

$current_user_id = $_SESSION['userID'];
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// echo $current_user_id;

$url = 'https://ussouthcentral.services.azureml.net/workspaces/45d13efe64304c83a3f2be965bc966ad/services/997d3d17b0db4dabb79aa441e12f848b/execute?api-version=2.0&format=swagger';

$api_key = '2VlbDIQkxO3CyLQ6orryzB5IpIZg0dNgpTJpjtVJEOuc+m3yIB0H/AcnceE7HjhELCvP0FE4NmCHG9vUgaQAWA=='; 

// $query = "SELECT * FROM auction.product_searches";
// $getMatches= sqlsrv_query($conn, $query);
// $row = sqlsrv_fetch_array($getMatches, SQLSRV_FETCH_ASSOC);
// $starting_id = $row['ID'];

// echo $starting_id;

// $query2 = "SELECT * FROM auction.product_searches";
// $getMatches= sqlsrv_query($conn, $query2, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
// $count = sqlsrv_num_rows($getMatches);
// $row = sqlsrv_fetch_array($getMatches, SQLSRV_FETCH_ASSOC);

// echo $count;






$data = array(
    'Inputs'=> array(
        'input1'=> array(
             array('user_id' => (string)$current_user_id)
        ),
    ),
        'GlobalParameters' => null,
);

$body = json_encode($data);
// echo $body;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$api_key, 'Accept: application/json'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response  = json_decode(curl_exec($ch), true);
// //echo 'Curl error: ' . curl_error($ch);
$array_of_products = array();
// print_r($response['Results']['output1'][0]['Item 1']);
$my_array = $response;
$buffer_val = $my_array['Results']['output1'][0]['Item 1'];

curl_close($ch);
if (in_array("error", $response) or !$buffer_val) {
    echo "Got error";
    for ($x = 0; $x <= 4; $x++) {
    array_push($array_of_products, rand(5, 150));
    } 

}
else
{

  
  // $val = $my_array['Results']['output1'][0]['Item 1'];
  for($x=1; $x <= 5; $x++)
  {
    $buffer = $my_array['Results']['output1'][0]['Item ' . (string) $x];
    // echo "> " . $buffer;
    if($buffer)
    {
      array_push($array_of_products, (int) $buffer);
    }
  }

  // print_r($response['Results']['output1'][0]['Item 1']);
}

// echo "here" . $array_of_products[0];

// print_r($response);
// var_dump($response):

echo "
<br><br><br><br><br>
<h3 align='center'>Personalised Recommendations</h3>
<table border='1' align='center'>
<tr>
<th>Image</th>
<th>Title</th>
<th>Price</th>
<th>Service Cost</th>
<th>ebayID</th>
<th>Reviews</th>
</tr>";


for($x=0; $x < count($array_of_products); $x++)
{
  $current_item = $array_of_products[$x];
  $query = "SELECT * FROM auction.product_searches WHERE ID = '$current_item'";
  $getMatches= sqlsrv_query($conn, $query, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
  $row = sqlsrv_fetch_array($getMatches, SQLSRV_FETCH_ASSOC);
  $num_of_rows = sqlsrv_num_rows($getMatches);

  if($num_of_rows > 0)
  {
  $product_link = $row['product_link'];
  $title = $row['title'];
  $ebayidval = $row['ebayID'];
  $product_id = $row['ID'];
  $img_src = $row['image'];
  $value=1;
  $_SESSION['reviewBool'] = $value;

  echo "<tr>";

  echo "<td>" . "<a href=\"$product_link\"><img src=\"$img_src\"></a>" . "</td>";
  echo "<td>" . "<a href=\"$product_link\" target=\"_blank\">$title</a>" . "</td>";
  echo "<td>" . $row['price'] . "</td>";
  echo "<td>" . $row['serviceCost'] . "</td>";
  echo "<td>" . $row['ebayID'] . "</td>";
  echo "<td>" ;
  echo "<form method=\"POST\" action=\"review_show.php\">  <button type=\"submit\" class=\"btn btn-warning\" name=\"ebayIDShow\" value=\"$ebayidval\" >Show all Reviews</button></form>";
  echo "</td>";
  echo "</tr>";
  }


}

echo "</table>";



?>



<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Filter Search for Watches</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/scrolling-nav.css" rel="stylesheet">



    <link href="../../twitter-bootstrap/twitter-bootstrap-v2/docs/assets/css/bootstrap.css" rel="stylesheet">
<script src="./js/jQuery.js"></script>
<script src="./js/jQueryUI/ui.tablesorter.js"></script>

</head>

<body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#">A U C T O R A</a>
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



<script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom JavaScript for this theme -->
    <script src="js/scrolling-nav.js"></script>
</body>

</html>





