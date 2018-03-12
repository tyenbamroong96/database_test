<?php
session_start();
$_SESSION['message'] = '';
$connectionInfo = array("UID" => "auctora@auctora-server", "pwd" => "arotcua1!", "Database" => "auctoraDB");
$serverName = "tcp:auctora-server.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);

$query1 = "SELECT SUM(analogcount) AS anacount FROM dbo.filtercounts";
$query2 = "SELECT SUM(digitalcount) AS digicount FROM dbo.filtercounts";

$getMatches1= sqlsrv_query($conn, $query1);
$getMatches2= sqlsrv_query($conn, $query2);

$row1 = sqlsrv_fetch_array($getMatches1, SQLSRV_FETCH_ASSOC);
$row2 = sqlsrv_fetch_array($getMatches2, SQLSRV_FETCH_ASSOC);

$analogcount = $row1['anacount'];
$digitalcount = $row2['digicount'];

?>

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
  <script src="./js/Chart.js"></script>
  <script src="./js/Chart.min.js"></script>
</head>

<body>
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
          <li class="nav-item dropdown">
            <a class="nav-link js-scroll-trigger" href="">Analysis By <i class="fa fa-caret-down"></i></a>
            <div class="dropdown-content">
              <a href="byBrand.php">Brand</a>
              <a href="byType.php">Type</a>
              <a href="byCondition.php">Condition</a>
            </div>
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
  <br />
  <br />
  <br />

  <!-- Display Chart -->
  <canvas id="myChart" width="150" height="100"></canvas>
</body>
<script>
var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Analog", "Digital"],
        datasets: [{
            label: 'Number of views',
            data: [<?php echo $analogcount; ?>, <?php echo $digitalcount; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
