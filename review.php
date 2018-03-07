<?php
session_start();
$_SESSION['message'] = '';
$connectionInfo = array("UID" => "auctora@auctora-server", "pwd" => "arotcua1!", "Database" => "auctoraDB");
$serverName = "tcp:auctora-server.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);



if (isset($_POST['comment_posted'])){

  $comment = $_POST['reviewBody'];
  $currentUserId = $_SESSION['userID'];
  $vall = $_POST['comment_posted'];
  $rating = 0;

  $query = "SELECT * FROM auction.product_searches WHERE ID = '$vall'";
  $getMatches= sqlsrv_query($conn, $query);

  $row = sqlsrv_fetch_array($getMatches, SQLSRV_FETCH_ASSOC);
  $ebayIdent = $row['ebayID'];

  if (isset($_POST['star']))
  {
    $rating = $_POST['star'];
  }


  // echo "the value" . $vall;

  //echo "product";
  //echo $prod_id;

  $tsql_query_to_avoid_duplicates = "SELECT * FROM auction.product_reviews WHERE product_id = '$vall' AND user_id = '$currentUserId'";
  $getResults= sqlsrv_query($conn, $tsql_query_to_avoid_duplicates, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
  if ($getResults == FALSE)
      die(FormatErrors(sqlsrv_errors()));
  $num_of_rows = sqlsrv_num_rows($getResults);

  if($num_of_rows <= 0)
  {
    //push review to database
  $tsql2= "INSERT INTO auction.product_reviews (product_id, user_id, comment, rating) VALUES (?,?,?,?);";

  $params2 = array($vall,$currentUserId,$comment,$rating);
  $getResults2= sqlsrv_query($conn, $tsql2, $params2);
  $rowsAffected2 = sqlsrv_rows_affected($getResults2);
  if ($getResults2 == FALSE or $rowsAffected2 == FALSE){
    die(FormatErrors(sqlsrv_errors()));
  }
  // $_SESSION['ebayItem'] = $ebayItemId;
  $value=0;
  $_SESSION['reviewBool'] =$value ;
  //$ebayItemIds = $_POST['ebayID'];
  //$some=	202241977639;
  $_SESSION['ebayItem'] = $ebayIdent;
  header("Location: review_show.php");

  }
  else{
    $value=0;
    $_SESSION['reviewBool'] = $value;
    //$some=	202241977639;
    $_SESSION['ebayItem'] = $ebayIdent;
    // $_SESSION['ebayItem'] = $ebayItemId;
    header("Location: review_show.php");
  }



}


echo "<br><br><br><br><br>";

echo "<h1 align='center'>eBay Watch Review</h1>";
echo "</br>";
//ebayItem id from product_searches page
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


$query = "SELECT * FROM auction.product_searches WHERE ebayID = '$ebayItemId'";
$getMatches= sqlsrv_query($conn, $query);

$row = sqlsrv_fetch_array($getMatches, SQLSRV_FETCH_ASSOC);
$count = $row['view_count'];
//obtain below product primary key id from database
$prod_id = $row['ID'];
$_SESSION['productID'] = $row['ID'];


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



// if (isset($_POST['comment_posted'])){

//   $comment = $_POST['reviewBody'];
//   $currentUserId = $_SESSION['userID'];
//   $vall = $_POST['comment_posted'];

//   // echo "the value" . $vall;

//   //echo "product";
//   //echo $prod_id;

//   $rating =5;
//   $tsql_query_to_avoid_duplicates = "SELECT * FROM auction.product_reviews WHERE product_id = '$vall' AND user_id = '$currentUserId'";
//   $getResults= sqlsrv_query($conn, $tsql_query_to_avoid_duplicates);
//   if ($getResults == FALSE)
//       die(FormatErrors(sqlsrv_errors()));
//   $num_of_rows = sqlsrv_num_rows($getResults);

//   if($num_of_rows == 0)
//   {
//     //push review to database
//   $tsql2= "INSERT INTO auction.product_reviews (product_id, user_id, comment, rating) VALUES (?,?,?,?);";

//   $params2 = array($vall,$currentUserId,$comment,$rating);
//   $getResults2= sqlsrv_query($conn, $tsql2, $params2);
//   $rowsAffected2 = sqlsrv_rows_affected($getResults2);
//   if ($getResults2 == FALSE or $rowsAffected2 == FALSE){
//     die(FormatErrors(sqlsrv_errors()));
//   }

//   header("Location: products.php");
//   exit;
//   }
//   else{
//     $_SESSION['message'] = 'You have already reviewed this item.';
//   }



// }

// $tsql2= "INSERT INTO auction.product_reviews (comment, rating, user_id, product_id ) VALUES (?,?,?,?);";
// $params2 = array($title,$rating,$currentId,$id);
// $getResults2= sqlsrv_query($conn, $tsql2, $params2);
// $rowsAffected2 = sqlsrv_rows_affected($getResults2);
// if ($getResults2 == FALSE or $rowsAffected2 == FALSE){
//   die(FormatErrors(sqlsrv_errors()));
// }


// $currentUserId = $_SESSION['userID'];
//
// $productid = $row['ID'];
// $comment = $_POST['reviewBody'];
// echo "product";
// echo $productid;
//
// $rating =5;
//
//
//
// $tsql2= "INSERT INTO auction.product_reviews (comment, rating, user_id, product_id ) VALUES (?,?,?,?);";
// $params2 = array($comment,$rating,$currentUserId,$productid);
// $getResults2= sqlsrv_query($conn, $tsql2, $params2);
// $rowsAffected2 = sqlsrv_rows_affected($getResults2);
// if ($getResults2 == FALSE or $rowsAffected2 == FALSE){
//   die(FormatErrors(sqlsrv_errors()));
// }
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

    <style>
      body {
        overflow-x: hidden;
        margin-bottom: 15px;
      }

      textarea {
        resize: none;
      }

      .forum-full {
        background-color: rgba(158, 158, 158, 0.7);
        max-width: 100%;
        max-height: 100%;
        border: 1px solid;
        padding: 15px;
        margin: .4%;
      }

      .topic {
        margin-top: 5px;
        color: rgba(215, 169, 60, 0.8);
      }

      .body-full {
        color: white;
        margin-bottom: 1%;
      }

      .comment {
        position: absolute;
        right: 35px;
        bottom: 10px;
        font-size: 40px
      }

      .comment-box {
        width: 100%;
        row: 9;
      }

      .post-by {
        position: absolute;
        color: rgba(255, 255, 255, 0.4);
        bottom: 1px;
        right: 84%;
        font-size: 12px;
      }

      hr {
        border: 0;
        height: 0;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
      }

      div.stars {
      width: 270px;
      display: inline-block;
      margin-left: 560px;
    }

      input.star { display: none; }

      label.star {
        float: right;
        padding: 10px;
        font-size: 36px;
        color: #444;
        transition: all .2s;
      }

      input.star:checked ~ label.star:before {
        content: '\f005';
        color: #FD4;
        transition: all .25s;
      }

      input.star-5:checked ~ label.star:before {
        color: #FE7;
        text-shadow: 0 0 20px #952;
      }

      input.star-1:checked ~ label.star:before { color: #F62; }

      label.star:hover { transform: rotate(-15deg) scale(1.3); }

      label.star:before {
        content: '\f006';
        font-family: FontAwesome;
      }

    </style>

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

    <!-- <a href="#writereview">Write a review</a> -->
    <!-- Write review -->
    <!-- <div class="stars">
  <form action="" method="post">
    <input class="star star-5" id="star-5" type="radio" name="star" value="5"/>
    <label class="star star-5" for="star-5"></label>
    <input class="star star-4" id="star-4" type="radio" name="star" value="4"/>
    <label class="star star-4" for="star-4"></label>
    <input class="star star-3" id="star-3" type="radio" name="star" value="3"/>
    <label class="star star-3" for="star-3"></label>
    <input class="star star-2" id="star-2" type="radio" name="star" value="2"/>
    <label class="star star-2" for="star-2"></label>
    <input class="star star-1" id="star-1" type="radio" name="star" value="1"/>
    <label class="star star-1" for="star-1"></label>
  </form>
</div> -->


    <div class="col-md-2"></div>
    <div class="col-md-8 container forum-full">

        <form method="post" id="writereviews">
          <h2 align='center'> Please rate the item </h2>
          <div class="stars">
          <input class="star star-5" id="star-5" type="radio" name="star" value="5"/>
    <label class="star star-5" for="star-5"></label>
    <input class="star star-4" id="star-4" type="radio" name="star" value="4"/>
    <label class="star star-4" for="star-4"></label>
    <input class="star star-3" id="star-3" type="radio" name="star" value="3"/>
    <label class="star star-3" for="star-3"></label>
    <input class="star star-2" id="star-2" type="radio" name="star" value="2"/>
    <label class="star star-2" for="star-2"></label>
    <input class="star star-1" id="star-1" type="radio" name="star" value="1"/>
    <label class="star star-1" for="star-1"></label>
  </div>


          <textarea placeholder="Write your review..." class="col-md-12 ckeditor" name="reviewBody" rows="8"></textarea>
          <!-- <input type="submit" value="Post" style="background:green;color:white;margin-top:10px;"> -->
          <button type="submit" class="btn btn-warning" name="comment_posted" value="<?php echo $_SESSION['productID']; ?>">Post</button>
        </form>
    </div>
    <div class="col-md-2"></div>


  <!--<?php
  $comment = $_POST['reviewBody'];
  $currentUserId = $_SESSION['userID'];


  //echo "product";
  //echo $prod_id;

  $rating =5;

  //push review to database
  $tsql2= "INSERT INTO auction.product_reviews (product_id, user_id, comment, rating) VALUES (?,?,?,?);";
  $prod_id = $_SESSION['productID'];
  echo "HERE IS THE PROD ID" . $prod_id;
  $params2 = array($prod_id,$currentUserId,$comment,$rating);
  $getResults2= sqlsrv_query($conn, $tsql2, $params2);
  $rowsAffected2 = sqlsrv_rows_affected($getResults2);
  if ($getResults2 == FALSE or $rowsAffected2 == FALSE){
    die(FormatErrors(sqlsrv_errors()));
  }
  ?> -->
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
