<!-- Check log-in status -->
<?php 
// if($_SESSION["$logged_in"] == false) header("Location: login.php");
// echo "hereeee";

?>

<div>
  <div class="col-md-1"></div>
  <div class="col-md-10">
    <div>
      <div class="col-md-2">
        <!-- where logo will be -->
        <a href="#"><img style="margin:13px;margin-left:45px;" width="105px" height="90px" src="http://cdn.shopify.com/s/files/1/0377/2037/products/Mens37.Front_e0435337-82ea-4472-86aa-0e34e1b2c3e8_grande.jpg?v=1510684726"></a>
      </div>
      <div class="col-md-10" style="margin-top:31px;">
        <a href="#"><span class="ccs-text">WatchReview.com</span></a>
      </div>
    </div>
    <!-- Navigation bar -->
    <div class="col-md-12 center-block">
      <nav class="navbar navbar-inverse navigation-bar">
        <div class="container-fluid nav-content">
          <div class="container-fluid nav-content">
            <ul class="nav navbar-nav">
              <li><a href="#">Home</a></li>
              <li><a href="#">Reviews</a></li>
              <li><a href="#">Watch Brands</a>
              <li><a href="#">FAQ</a></li>
            </ul>

          <!-- Create and Log out -->
          <ul class="nav navbar-nav navbar-right">
            <li><a href="watchlist.php"><i class="fas fa-heart"></i></a></li>
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href=""><i class="fa fa-fw fa-user"></i> <?= $_SESSION["firstname"] . ' ' . substr($_SESSION["lastname"], 0, 1) . '.' ?> <span class="caret"></span></a>
              <ul class="dropdown-menu user">
                <li id="logout" style="float: right;" onclick="logout()"><a href="javascript:void(0)">Log out</a></li>
              </ul>
            </li>
          </ul>

          <!-- Search bar -->
          <form class="navbar-form navbar-right">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search forums">
              <div class="input-group-btn">
                <button class="btn btn-default" type="submit">
                  <i class="glyphicon glyphicon-search"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </nav>
    </div>
  </div>
  <div class="col-md-1"></div>
</div>


<script>
  $(function(){
  });
  var logout = function(){
      bootbox.confirm('Are you sure you want to log out?',
                      function(result) {
                        if (result) {
                          //Destroy session before logging out
                          <? session_destroy() ?>
                          window.location.href="home.php";
                        }
                      });
  };
</script>
