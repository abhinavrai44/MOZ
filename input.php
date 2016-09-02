<?php
   if( isset($_POST["url"])) {
      
    // Get your access id and secret key here: https://moz.com/products/api/keys
	$accessID = "";
	$secretKey = "";

	// Set your expires times for several minutes into the future.
	// An expires time excessively far in the future will not be honored by the Mozscape API.
	$expires = time() + 300;

	// Put each parameter on a new line.
	$stringToSign = $accessID."\n".$expires;

	// Get the "raw" or binary output of the hmac hash.
	$binarySignature = hash_hmac('sha1', $stringToSign, $secretKey, true);

	// Base64-encode it and then url-encode that.
	$urlSafeSignature = urlencode(base64_encode($binarySignature));

	// Add up all the bit flags you want returned.
	$cols = "144115291691993125";

	// Put it all together and you get your request URL.
	$requestUrl = "http://lsapi.seomoz.com/linkscape/url-metrics/?Cols=".$cols."&AccessID=".$accessID."&Expires=".$expires."&Signature=".$urlSafeSignature;

	$batchedDomains = array($_POST["url"]);
	$encodedDomains = json_encode($batchedDomains);
	print "\n\n";
	//print $encodedDomains;

	// Use Curl to send off your request.
	// Send your encoded list of domains through Curl's POSTFIELDS.
	$options = array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POSTFIELDS     => $encodedDomains
		);

	$ch = curl_init($requestUrl);
	curl_setopt_array($ch, $options);
	$content = curl_exec($ch);
	curl_close( $ch );

	$contents = json_decode($content);
	print "\n\n";
	//echo "<pre>"; print_r($contents); echo "</pre>";
	//echo "Hello";
	//print_r($contents);

	foreach($contents as $key=>$value){
		echo "<br>" . PHP_EOL;
		echo "{". "<br>" . PHP_EOL;
		echo "URL : ".$value->uu. "<br>" . PHP_EOL;
		echo "The Normalized MozRank of the URL's subdomain : ".$value->fmrp. "<br>" . PHP_EOL;
		echo "The Raw MozRank of the URL's subdomain : ".$value->fmrr. "<br>" . PHP_EOL;
		echo "Domain Authority : ".$value->pda. "<br>" . PHP_EOL;
		echo "Number of external equity links : ".$value->ueid. "<br>" . PHP_EOL;
		echo "Number of Links : ".$value->uid. "<br>" . PHP_EOL;
		echo "Time last crawled : ".$value->ulc. "<br>" . PHP_EOL;
		echo "Normalized MozRank of URL : ".$value->umrp. "<br>" . PHP_EOL;
		echo "Raw MozRank of URL".$value->umrr. "<br>" . PHP_EOL;
		echo "Page Authority : ".$value->upa. "<br>" . PHP_EOL;
		echo "HTTP Status Code : ".$value->us. "<br>" . PHP_EOL;
		echo "Title : ".$value->ut. "<br>" . PHP_EOL;
		echo "}". "<br>" . PHP_EOL;
		echo "<br>" . PHP_EOL;
	}
      
      exit();
   }
?>
<html>
   <body>
   
      <form action = "<?php $_PHP_SELF ?>" method = "POST">
         URL: <input type = "text" name = "url" />
         <input type = "submit" class="like" value="Submit URL" />
      </form>
   
   </body>
</html>