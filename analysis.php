<?php
session_start();
$_SESSION['message'] = '';
$connectionInfo = array("UID" => "auctora@auctora-server", "pwd" => "arotcua1!", "Database" => "auctoraDB");
$serverName = "tcp:auctora-server.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);

$query1 = "SELECT SUM(rolexcount) AS rlcount FROM dbo.filtercounts";
$query2 = "SELECT SUM(casiocount) AS cacount FROM dbo.filtercounts";
$query3 = "SELECT SUM(seikocount) AS skcount FROM dbo.filtercounts";
$query4 = "SELECT SUM(analogcount) AS anacount FROM dbo.filtercounts";
$query5 = "SELECT SUM(digitalcount) AS digicount FROM dbo.filtercounts";
$query6 = "SELECT SUM(newcount) AS ncount FROM dbo.filtercounts";
$query7 = "SELECT SUM(usedcount) AS ucount FROM dbo.filtercounts";

$getMatches1= sqlsrv_query($conn, $query1);
$getMatches2= sqlsrv_query($conn, $query2);
$getMatches3= sqlsrv_query($conn, $query3);
$getMatches4= sqlsrv_query($conn, $query4);
$getMatches5= sqlsrv_query($conn, $query5);
$getMatches6= sqlsrv_query($conn, $query6);
$getMatches7= sqlsrv_query($conn, $query7);

$row1 = sqlsrv_fetch_array($getMatches1, SQLSRV_FETCH_ASSOC);
$row2 = sqlsrv_fetch_array($getMatches2, SQLSRV_FETCH_ASSOC);
$row3 = sqlsrv_fetch_array($getMatches3, SQLSRV_FETCH_ASSOC);
$row4 = sqlsrv_fetch_array($getMatches4, SQLSRV_FETCH_ASSOC);
$row5 = sqlsrv_fetch_array($getMatches5, SQLSRV_FETCH_ASSOC);
$row6 = sqlsrv_fetch_array($getMatches6, SQLSRV_FETCH_ASSOC);
$row7 = sqlsrv_fetch_array($getMatches7, SQLSRV_FETCH_ASSOC);

$rolexcount = $row1['rlcount'];
$casiocount = $row2['cacount'];
$seikocount = $row3['skcount'];
$analogcount = $row4['anacount'];
$digitalcount = $row5['digicount'];
$newcount = $row6['ncount'];
$usedcount = $row7['ucount'];


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
  <style>
    .center {
    margin: auto;
    width: 60%;
    border: 1.5px solid black;
    padding: 10px;
    }
  </style>
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
  <div class="center">
      <canvas id="brand" width="150" height="80"></canvas>
    </label>
  </div>
  <br />
  <div class="center">
    <canvas id="type" width="150" height="80"></canvas>
  </div>
  <br />
  <div class="center">
    <canvas id="condition" width="150" height="80"></canvas>
  </div>
</body>
<script>
// Brand analysis
var ctx = document.getElementById("brand").getContext('2d');
var brand = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Rolex", "Casio", "Seiko"],
        datasets: [{
            label: ["New", "Used"],
            data: [<?php echo $rolexcount; ?>, <?php echo $casiocount; ?>, <?php echo $seikocount; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
      legend: {
          display: false
      },
      tooltips: {
          callbacks: {
             label: function(tooltipItem) {
                    return tooltipItem.yLabel;
             }
          }
      },
      scales: {
            yAxes: [{
              scaleLabel: {
                  display: true,
                  labelString: 'Number of views'
              },
              ticks: {
                  beginAtZero:true
              }
            }],
            xAxes: [{
              scaleLabel: {
                  display: true,
                  labelString: 'Number of views'
              },
              ticks: {
                  beginAtZero:true
              }
            }]
      },
      title: {
            display: true,
            text: 'User views based on brand'
      }
    }
});

// Type Analysis
var ctx2 = document.getElementById("type").getContext('2d');
var type = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ["Analog", "Digital"],
        datasets: [{
            labels: ["Analog", "Digital"],
            data: [<?php echo $analogcount; ?>, <?php echo $digitalcount; ?>],
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)'
            ],
            borderColor: [
                'rgba(75, 192, 192,1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
      legend: {
          display: false
      },
      tooltips: {
          callbacks: {
             label: function(tooltipItem) {
                    return tooltipItem.yLabel;
             }
          }
      },
      scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
      }
    }
});
// Condition Analysis
var ctx3 = document.getElementById("condition").getContext('2d');
var condition = new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: ["New", "Used"],
        datasets: [{
            label: ["New", "Used"],
            data: [<?php echo $newcount; ?>, <?php echo $usedcount; ?>],
            backgroundColor: [
                'rgba(255, 159, 64, 0.2)',
                'rgba(54, 162, 235, 0.2)',
            ],
            borderColor: [
                'rgba(255, 159, 64, 1)',
                'rgba(54, 162, 235, 1)',
            ],
            borderWidth: 1
        }]
    },
    options: {
      legend: {
          display: false
      },
      tooltips: {
          callbacks: {
             label: function(tooltipItem) {
                    return tooltipItem.yLabel;
             }
          }
      },
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
