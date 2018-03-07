<?php
/* 
Send email to user if a particular item is no longer active or in the future -> user can select a threshold for the price
of the item and if the item falls below this threshold we send an email to the user 
~~~~~~~~~~~UNCOMMENT THE CODE ONLY WHEN EVERYONE HAS A VALID EMAIL ADDRESS IN THE users TABLE~~~~~~~~~~~~~~~~~~


                              WARNING!!!!!!!!!!!!!!!!!!!!
INCLUDING THIS FILE MIGHT INCUR API COSTS BY USING THE AZURE SENDGRID API.

*/


// $sql_to_check_inactive = "SELECT * FROM auction.product_searches AS auc
// WHERE auc.ebayID IN (SELECT ebayID FROM auction.watch_list WHERE user_id = '$current_user_id') AND auc.status LIKE 'inactive'";
// $getResults= sqlsrv_query($conn, $sql_to_check_inactive, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
// if ($getResults == FALSE)
//     die(FormatErrors(sqlsrv_errors()));

// // $row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC);
// $num_of_rows = sqlsrv_num_rows($getResults);

// if($num_of_rows > 0)
// {
//   $string_of_inactive_items = "";
//   while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
//     $string_of_inactive_items .= $row['title'] . "\n";
    
//   }



  // $url = 'https://api.sendgrid.com/';
  // $user = 'azure_2f083ea88d2f6546925eee193832ea77@azure.com';
  // $pass = 'arotcua1!';
  // $api_key = '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';

  // $params = array(
  //       'api_user' => $user,
  //       'api_key' => $pass,
  //       'to' => $current_user_id,
  //       'subject' => "Watchlist item(s) no longer active",
  //       'html' => "Greetings, " . $current_user_id . "\n Unfortunately the following items in your watchlist are no longer
  //       active: \n" . $string_of_inactive_items,
  //       'text' => "Greetings," . $current_user_id . "\n Unfortunately the following items in your watchlist are no longer
  //       active: \n" . $string_of_inactive_items,
  //       'from' => 'auctora@azure.com',
  //    );

  // $request = $url.'api/mail.send.json';

  // // Generate curl request
  //  $session = curl_init($request);

  //  // Tell curl to use HTTP POST
  //  curl_setopt ($session, CURLOPT_POST, true);

  //  // Tell curl that this is the body of the POST
  //  curl_setopt ($session, CURLOPT_POSTFIELDS, $params);

  //  // Tell curl not to return headers, but do return the response
  //  curl_setopt($session, CURLOPT_HEADER, false);
  //  curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

  //  // obtain response
  //  $response = curl_exec($session);
  //  curl_close($session);

  //  // print everything out
  //  // print_r($response);

  //}
?>