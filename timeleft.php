<?php

require_once('DisplayUtils.php');  // functions to aid with display of information
error_reporting(E_ALL);  // turn on all errors, warnings and notices for easier debugging

$results = '';

//checking for non-empty and non-negative integer


if(isset($_POST['Query']))
{
  $endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1';  // URL to call
  $responseEncoding = 'XML';   // Format of the response

  $safeQuery = urlencode (utf8_encode($_POST['Query']));
  $brand = $_POST['Query'];
  $brand1 = (string)$brand;

  $site  = $_POST['GlobalID'];
  //$format  = $_POST['BuyingFormat'];
  $disp  = $_POST['Display'];
  $cond  = $_POST['Condition'];
  $gend  = $_POST['Gender'];
  $year  = $_POST['Year_Manu'];
  $priceRangeMin = $_POST['MinPrice'];
  $priceRangeMax = $_POST['MaxPrice'];
  $min = $_POST['MinPrice'];
  $max = $_POST['MaxPrice'];
  if(!($max>0) || !($min>=0) ||!($max>$min) ) {
    $rest2 = die("Error: Please input valid price");
}
  /*$query2 = "SELECT * FROM auction.filters WHERE brand = '$brand' AND min_price = '$min' AND max_price = '$max' AND display ='$disp' AND condition = '$cond' AND gender = '$gend' AND year_manufacture = '$year' "

  $getMatchesB= sqlsrv_query($conn, $queryB);

  $rowB = sqlsrv_fetch_array($getMatchesB, SQLSRV_FETCH_ASSOC);
  if(!$rowB){*/

    $current_uid = $_SESSION['user_id'];
    $gend_for_filters = str_replace ("'s","",$gend);
    $query = "SELECT * FROM auction.filters
    WHERE brand = '$brand' AND min_price = '$min' AND max_price = '$max'
    AND display ='$disp' AND condition = '$cond' AND year_manufacture = '$year' AND gender = '$gend_for_filters' AND user_id = '$current_uid' ";
    $getMatches= sqlsrv_query($conn, $query);

    $row = sqlsrv_fetch_array($getMatches, SQLSRV_FETCH_ASSOC);
    // echo "HERE -> " . $row;

    if(!$row){

      $tsql2= "INSERT INTO auction.filters (brand, min_price, max_price, display, condition, gender, year_manufacture, user_id) VALUES (?,?,?,?,?,?,?,?);";
      $params2 = array($brand,$min,$max,$disp,$cond,$gend_for_filters,$year,$current_uid);
      $getResults2= sqlsrv_query($conn, $tsql2, $params2);
      $rowsAffected2 = sqlsrv_rows_affected($getResults2);
      if ($getResults2 == FALSE or $rowsAffected2 == FALSE){
        die(FormatErrors(sqlsrv_errors()));
      }


    }




  $results .= 'Click <a href="#Low-Range">here</a> to see Low-Range.'. "<br />\n";
  $results .= 'Click <a href="#Mid-Range">here</a> to see Mid-Range.'. "<br />\n";
  $results .= 'Click <a href="#High-Range">here</a> to see High-Range.'. "<br />\n";



  $itemsPerRange = 100;
  $pageNumber=1; //0-100

  // $debug = (boolean) $_POST['Debug'];

  $rangeArr = array('Low-Range', 'Mid-Range', 'High-Range');

  $priceRange = ($priceRangeMax - $priceRangeMin) / 3;  // find price ranges for three tables
  $priceRangeMin =  sprintf("%01.2f",$priceRangeMin );
  $priceRangeMax = $priceRangeMin;  // needed for initial setup

  foreach ($rangeArr as $range)
  {
    $priceRangeMax = sprintf("%01.2f", ($priceRangeMin + $priceRange));
    if($range =='Low-Range'){
        $results .='<a name="Low-Range"></a> '. "<br />\n";
      }
    else if($range =='Mid-Range'){
        $results .='<a name="Mid-Range"></a> '. "<br />\n";
  }
  else if ($range =='High-Range'){
    $results .='<a name="High-Range"></a> '. "<br />\n";
    //$results .= '<a href="https://www.w3schools.com">Visit W3Schools</a>'. "<br />\n";
  }

    $results .=  "<h2>$range : $priceRangeMin ~ $priceRangeMax</h2>\n";
    // Construct the FindItems call
    $apicall = "$endpoint?OPERATION-NAME=findItemsAdvanced"
         . "&SERVICE-VERSION=1.0.0"
         . "&GLOBAL-ID=$site"
         . "&SECURITY-APPNAME=PiusJude-Ragnarok-PRD-c5d80d3bd-40178424" //replace with your app id
         . "&keywords=$safeQuery"
         . "&paginationInput.entriesPerPage=$itemsPerRange"
         //. "&paginationInput.pageNumber=$pageNumber"
         . "&sortOrder=BestMatch"
         . "&itemFilter(0).name=ListingType"
         . "&itemFilter(0).value=Auction"
         // . "&itemFilter(0).value(1)=AuctionWithBIN"
         . "&itemFilter(1).name=MinPrice"
         . "&itemFilter(1).value=$priceRangeMin"
         . "&itemFilter(2).name=MaxPrice"
         . "&itemFilter(2).value=$priceRangeMax"
         . "&itemFilter(3).name=Condition"
         . "&itemFilter(3).value=$cond"


         . "&aspectFilter(0).aspectName=Display"
        // . "&aspectFilter(0).aspectValueName=Analog"
         . "&aspectFilter(0).aspectValueName=$disp"
         . "&aspectFilter(1).aspectName=Gender"
         . "&aspectFilter(1).aspectValueName=$gend"
         . "&aspectFilter(2).aspectName=Year of Manufacture"
         . "&aspectFilter(2).aspectValueName=$year"

        // . "&aspectFilter(1).aspectName=Brand"
        // . "&aspectFilter(1).aspectValueName=$company"
        // . "&aspectFilter(2).aspectName=Condition"
        // . "&aspectFilter(2).aspectValueName='New with tags'"

         . "&affiliate.networkId=9"  // fill in your information in next 3 lines
         . "&affiliate.trackingId=1234567890"
         . "&affiliate.customId=456"
         . "&RESPONSE-DATA-FORMAT=$responseEncoding";

         $rest = simplexml_load_file($apicall) or die("Error: Please select the required filters");
         //print_r($resp);
         // Check to see if the response was loaded, else print an error
         // Probably best to split into two different tests, but have as one for brevity


           echo $rest->paginationOutput->totalEntries;
           echo "</br>";
           $pageCount=(int)($rest->paginationOutput->totalEntries /$itemsPerRange)+1;
           //echo $pageCount;
           //echo "</br>";
           $results .= 'Total items : ' . $rest->paginationOutput->totalEntries . "<br />\n";
           $results .= '<table id="example" class="tablesorter" border="0" width="100%" cellpadding="0" cellspacing="1">' . "\n";
           $results .= "<thead><tr><th>Count</th><th /><th>Product details</th><th>Seller Info </th><th>Price &nbsp; &nbsp; </th><th>Shipping &nbsp; &nbsp; </th><th>Total &nbsp; &nbsp; </th><th><!--Currency--></th><th>Time Left</th><th>Start Time</th><th>End Time</th></tr></thead>\n";
           $count=1;
    if ($rest && $rest->paginationOutput->totalEntries > 0) {
    for($pageNumber=1;$pageNumber<=$pageCount;$pageNumber++){
    $apicall = "$endpoint?OPERATION-NAME=findItemsAdvanced"
         . "&SERVICE-VERSION=1.0.0"
         . "&GLOBAL-ID=$site"
         . "&SECURITY-APPNAME=PiusJude-Ragnarok-PRD-c5d80d3bd-40178424" //replace with your app id
         . "&keywords=$safeQuery"
         . "&paginationInput.entriesPerPage=$itemsPerRange"
         . "&paginationInput.pageNumber=$pageNumber"
         . "&sortOrder=BestMatch"
         . "&itemFilter(0).name=ListingType"
         . "&itemFilter(0).value=Auction"
         // . "&itemFilter(0).value(1)=AuctionWithBIN"
         . "&itemFilter(1).name=MinPrice"
         . "&itemFilter(1).value=$priceRangeMin"
         . "&itemFilter(2).name=MaxPrice"
         . "&itemFilter(2).value=$priceRangeMax"
         . "&itemFilter(3).name=Condition"
         . "&itemFilter(3).value=$cond"



         . "&aspectFilter(0).aspectName=Display"
        // . "&aspectFilter(0).aspectValueName=Analog"
         . "&aspectFilter(0).aspectValueName=$disp"
         . "&aspectFilter(1).aspectName=Gender"
         . "&aspectFilter(1).aspectValueName=$gend"
         . "&aspectFilter(2).aspectName=Year of Manufacture"
         . "&aspectFilter(2).aspectValueName=$year"
        // . "&aspectFilter(1).aspectName=Brand"
        // . "&aspectFilter(1).aspectValueName=$company"
        // . "&aspectFilter(2).aspectName=Condition"
        // . "&aspectFilter(2).aspectValueName='New with tags'"

         . "&affiliate.networkId=9"  // fill in your information in next 3 lines
         . "&affiliate.trackingId=1234567890"
         . "&affiliate.customId=456"
         . "&RESPONSE-DATA-FORMAT=$responseEncoding";

    // if ($debug) {
    //   print "GET call = $apicall <br>";  // see GET request generated
    // }
    // Load the call and capture the document returned by the Finding API
    $resp = simplexml_load_file($apicall) or die("Error: Please select the required filters");
    //print_r($resp);
    // Check to see if the response was loaded, else print an error
    // Probably best to split into two different tests, but have as one for brevity

    //if ($resp && $resp->paginationOutput->totalEntries > 0) {
      // $results .= 'Total items : ' . $resp->paginationOutput->totalEntries . "<br />\n";
      // $results .= '<table id="example" class="tablesorter" border="0" width="100%" cellpadding="0" cellspacing="1">' . "\n";
      // $results .= "<thead><tr><th /><th>Product details</th><th>Seller Info </th><th>Price &nbsp; &nbsp; </th><th>Shipping &nbsp; &nbsp; </th><th>Total &nbsp; &nbsp; </th><th><!--Currency--></th><th>Time Left</th><th>Start Time</th><th>End Time</th></tr></thead>\n";


      // If the response was loaded, parse it and build links

      foreach($resp->searchResult->item as $item) {
        if ($item->galleryURL) {
          $picURL = $item->galleryURL;
        } else {
          $picURL = "http://pics.ebaystatic.com/aw/pics/express/icons/iconPlaceholder_96x96.gif";
        }
        $link  = $item->viewItemURL;
        $title = $item->title;
        $sellingState = sprintf("Selling Status: %s",(string) $item->sellingStatus->sellingState);
        $condition = sprintf("Condition: %s",(string) $item->condition->conditionDisplayName);
        if((string) $item->condition->conditionDisplayName == "New with tags"){
          $conditionInfo = sprintf("A brand-new, unused, unworn and undamaged item in the original packaging (such as the original box or bag) and/or with the original tags attached.");
        }
        else if((string) $item->condition->conditionDisplayName == "New without tags"){
            $conditionInfo = sprintf("A brand-new, unused and unworn item that is not in its original retail packaging or may be missing its original retail packaging materials (such as the original box or bag). The original tags may not be attached. For example, new shoes (with absolutely no signs of wear) that are no longer in their original box fall into this category.");
        }
        else if((string) $item->condition->conditionDisplayName == "New with defects"){
            $conditionInfo = sprintf("A brand-new, unused and unworn item with some kind of defect.  Possible cosmetic imperfections range from natural colour variations to scuffs, cuts or nicks, and hanging threads or missing buttons that occasionally occur during the manufacturing or delivery process. Apparel may contain irregular or mismarked size tags.  Item may be missing its original retail packaging materials (such as original box or bag).  New factory seconds and/or new irregular items may fall into this category. The original tags may or may not be attached. See seller’s listing for full details and description of any imperfections.");
        }
        else if((string) $item->condition->conditionDisplayName == "Used"){
            $conditionInfo = sprintf("An item that has been previously worn. See the seller’s listing for full details and description of any imperfections.");
        }

        //subtitle is optional description given by sellers
        $subtitle = $item->subtitle;
        //number of bids made for product
        $bids = sprintf("Number of bids: %u",$item->sellingStatus->bidCount);
        //unique ebay Id for product
        $ebayItemId  = sprintf("Item Id: %s ",$item->itemId);

        //display type e.g. analog or digital.
        //This is though copying filer selection.
        $display  = sprintf("Display type: %s",$disp);

        //seller info:
        //$positiveFeedbackPercent= sprintf("Seller name: %s",(string)$item->sellerInfo->sellerUserName);
        //location of product
        $location  = sprintf("Location: %s ",$item->location);


        $price = sprintf("%01.2f", $item->sellingStatus->convertedCurrentPrice);
        $ship  = sprintf("%01.2f", $item->shippingInfo->shippingServiceCost);
        $total = sprintf("%01.2f", ((float)$item->sellingStatus->convertedCurrentPrice
                      + (float)$item->shippingInfo->shippingServiceCost));

        $sqlItemSellingStatus = (float)$item->sellingStatus->convertedCurrentPrice;
        $sqlItemShippingInfo = (float)$item->shippingInfo->shippingServiceCost;
        $sqlEbayItemID = (float)$item->itemId;
        $sqlItemTitle = (string)$item->title;
        $sqlLink = (string) $item->viewItemURL;
        // SQL connection
        // $host = "ragnasvr.database.windows.net,1433";
        //
        // $dbname = "ragnaDB";
        //
        // $dbuser = "ragnarok@ragnasvr";
        //
        // $dbpwd = "Korangar2";
        //
        // $driver = "{ODBC Driver 13 for SQL Server}";
        //
        //
        // // Build connection string
        //
        // $dsn="Driver=$driver;Server=$host;Database=$dbname;";
        //
        // if (!($conn = @odbc_connect($dsn, $dbuser, $dbpwd))) {
        //
        //   die("Connection error: " . odbc_errormsg());
        //
        // }
        // else
        // {
        //   //print("Connection succesful");
        // }

        // $serverName = "tcp:ragnasvr.database.windows.net, 1433";
        // $connectionOptions = array(
        //     "Database" => "ragnaDB",
        //     "Uid" => "ragnarok@ragnasvr",
        //     "PWD" => "Korangar2"
        // );
        // //Establishes the connection
        // $conn = sqlsrv_connect($serverName, $connectionOptions);

        // if (!($conn)) {

        //   die("Connection error: " . sqlsrv_connect_error());

        // }
        // else
        // {
        //   //print("Connection succesful ");

        // }

        $query = "SELECT * FROM auction.product_searches WHERE ebayID = '$sqlEbayItemID'";
        $getMatches= sqlsrv_query($conn, $query);

        $row = sqlsrv_fetch_array($getMatches, SQLSRV_FETCH_ASSOC);
        //check for duplication
        if(!$row){
        $tsql= "INSERT INTO auction.product_searches (title, price, serviceCost, ebayID, product_link) VALUES (?,?,?,?,?);";
        // $user_id = $_SESSION['user_id'];
        $params = array($sqlItemTitle,$sqlItemSellingStatus,$sqlItemShippingInfo,$sqlEbayItemID,$sqlLink);
        $getResults= sqlsrv_query($conn, $tsql, $params);
        $rowsAffected = sqlsrv_rows_affected($getResults);
        if ($getResults == FALSE or $rowsAffected == FALSE)
          {
            echo $count;
            die(FormatErrors(sqlsrv_errors()));
          }
          else{
            //echo "Succeeded ";
            //echo $count;
            //echo "</br>";
          }
          // echo ($rowsAffected. " row(s) inserted: " . PHP_EOL);
          //header("Location: FindItemsAdvanced.php");
          sqlsrv_free_stmt($getResults);
        }
        // Determine currency to display - so far only seen cases where priceCurr = shipCurr, but may be others
        $priceCurr = (string) $item->sellingStatus->convertedCurrentPrice['currencyId'];
        $shipCurr  = (string) $item->shippingInfo->shippingServiceCost['currencyId'];
        if ($priceCurr == $shipCurr) {
          $curr = $priceCurr;
        } else {
          $curr = "$priceCurr / $shipCurr";  // potential case where price/ship currencies differ
        }
        $timeLeft = getPrettyTimeFromEbayTime($item->sellingStatus->timeLeft);
        //$endTime = strtotime($item->listingInfo->endTime);   // returns Epoch seconds
        $endTime = $item->listingInfo->endTime;
        $startTime = $item->listingInfo->startTime;

        // $sql = "INSERT INTO dbo.Product_Searches (title, price, serviceCost)
        // VALUES ('$sqlItemTitle','$sqlItemSellingStatus','$sqlItemShippingInfo' )";
        // $res = odbc_exec($conn, $sql);
        //   if (!$res) {
        //     echo $count;
        //     print("Table creation failed with error:\n");
        //     print(odbc_error($conn).": ".odbc_errormsg($conn)."\n");
        //     echo '</br>';
        //   } else {
        //     print("Table fyi_links created.\n");
        //     echo '</br>';
        //   }


          // Free the connection

        //  @odbc_close($conn);

        $results .= "<tr><td>$count</td><td><a href=\"$link\"><img src=\"$picURL\"></a></td><td> <a href=\"$link\">$title</a></br></br>     <button type=\"button\" class=\"btn btn-warning\" onclick=\"location.href = '$link';\">Buy/Bid</button> &nbsp;&nbsp;      <iframe name=\"votar\" style=\"display:none;\"></iframe>  <form id= \"add_to_watchlist\" target=\"votar\" method=\"post\">  <button type=\"submit\" class=\"btn btn-warning\" name=\"add_to_watchlist\" onclick=\"return confirm('Want to add item?');\" value=\"$sqlEbayItemID\">Add to Watchlist</button></form>           </br></br>      $subtitle </br></br> $sellingState </br></br> $bids</br></br> $condition</br></br>$conditionInfo</br></br> </br> $ebayItemId</br></br> $display</br><td >$location</td>"
             .  "<td>$price</td><td>$ship</td><td>$total</td><td>$curr</td><td>$timeLeft</td><td><nobr>$startTime</nobr></td><td><nobr>$endTime</nobr></td></tr>";
            $count++;
      }// each item

      <script>
        var countDownDate = new Date("").getTime();

        var x = setInterval(function() {
          var timeleft = document.getElementById("demo").innerHTML = timeleft;
        }, 1000);
      </script>


    //if resp more than 0
    // If there was no response, print an error


  }// for each page
}
  else {
    $results .= "<p> $range <i><b>No items found</b></i></p>". "<br />\n";
  }
    $results .= "</table>";
    $priceRangeMin = $priceRangeMax; // set up for next iteration

  } // foreach
      echo $results;
      exit;
} // if


?>
