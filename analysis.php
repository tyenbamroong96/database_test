<?php
session_start();
$_SESSION['message'] = '';
$connectionInfo = array("UID" => "auctora@auctora-server", "pwd" => "arotcua1!", "Database" => "auctoraDB");
$serverName = "tcp:auctora-server.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);

$query1 = "SELECT SUM(rolexcount) AS rlcount FROM dbo.filtercounts";
$query2 = "SELECT SUM(casiocount) AS cacount FROM dbo.filtercounts";
$query3 = "SELECT SUM(seikocount) AS skcount FROM dbo.filtercounts";

$getMatches1= sqlsrv_query($conn, $query1);
$getMatches2= sqlsrv_query($conn, $query2);
$getMatches3= sqlsrv_query($conn, $query3);

$row1 = sqlsrv_fetch_array($getMatches1, SQLSRV_FETCH_ASSOC);
$row2 = sqlsrv_fetch_array($getMatches2, SQLSRV_FETCH_ASSOC);
$row3 = sqlsrv_fetch_array($getMatches3, SQLSRV_FETCH_ASSOC);

$rolexcount = $row1['rlcount'];
$casiocount = $row2['cacount'];
$seikocount = $row3['skcount'];

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
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="analysis.php">Analysis</a>
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
  <div class="col-md-12" id="page-wrapper">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <canvas id="myChart" width="450" height="50"></canvas>
    </div>
    <div class="col-md-2"></div>
  </div>
</body>
<script>
var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Rolex", "Casio", "Seiko"],
        datasets: [{
            label: 'Number of views',
            data: [<?php echo $rolexcount; ?>, <?php echo $casiocount; ?>, <?php echo $seikocount ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 0.5
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
