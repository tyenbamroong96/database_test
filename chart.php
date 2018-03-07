<?php
session_start();
$_SESSION['message'] = '';
$connectionInfo = array("UID" => "auctora@auctora-server", "pwd" => "arotcua1!", "Database" => "auctoraDB");
$serverName = "tcp:auctora-server.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);

$query1 = "SELECT SUM(rolexcount) AS rlcount FROM dbo.counts";
$query2 = "SELECT SUM(casiocount) AS cacount FROM dbo.counts";
$query3 = "SELECT SUM(seikocount) AS skcount FROM dbo.counts";

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
  <script src="./js/Chart.js"></script>
  <script src="./js/Chart.min.js"></script>
</head>

<body>
  <canvas id="myChart" width="400" height="400"></canvas>
</body>
<script>
var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Red", "Blue", "Yellow"],
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
