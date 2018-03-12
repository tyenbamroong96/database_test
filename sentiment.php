<?php
/*
                              WARNING!!!!!!!!!!!!!!!!!!!!
INCLUDING THIS FILE MIGHT INCUR API COSTS BY USING AZURE SENTIMENT ANALYSIS API.

*/


// Replace the accessKey string value with your valid access key.
$accessKey = '8920b1e338e84cea9898a0e3ac4f55a1';

// Replace or verify the region.

// You must use the same region in your REST API call as you used to obtain your access keys.
// For example, if you obtained your access keys from the westus region, replace 
// "westcentralus" in the URI below with "westus".

// NOTE: Free trial access keys are generated in the westcentralus region, so if you are using
// a free trial access key, you should not need to change this region.
$host = 'https://westeurope.api.cognitive.microsoft.com';
$path = '/text/analytics/v2.0/sentiment';

function GetSentiment ($host, $path, $key, $data) {

    $headers = "Content-type: text/json\r\n" .
        "Ocp-Apim-Subscription-Key: $key\r\n";

    $data = json_encode ($data);

    // NOTE: Use the key 'http' even if you are making an HTTPS request. See:
    // http://php.net/manual/en/function.stream-context-create.php
    $options = array (
        'http' => array (
            'header' => $headers,
            'method' => 'POST',
            'content' => $data
        )
    );
    $context  = stream_context_create ($options);
    $result = file_get_contents ($host . $path, false, $context);
    return $result;
}

  $data = array (
      'documents' => array (
          array ( 'id' => '1', 'language' => 'en', 'text' => $comment)
              )
  );

  $result = GetSentiment ($host, $path, $accessKey, $data);

  $buffer = json_decode ($result, true);
  $result_score = $buffer["documents"][0]["score"];

  //Convert to range of (1-5)
  $comment_sentiment_score = (($result_score - 0) / (1 - 0)) * (4) + 1;

  //Average it with the users rating and now the rating on the table is this average

  $rating = (int) ceil(($rating + $comment_sentiment_score)/2);

?>
