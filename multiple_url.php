<?php
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
$cols = "103079231488";

// Put it all together and you get your request URL.
$requestUrl = "http://lsapi.seomoz.com/linkscape/url-metrics/?Cols=".$cols."&AccessID=".$accessID."&Expires=".$expires."&Signature=".$urlSafeSignature;

// Put your URLS into an array and json_encode them.
$batchedDomains = array('www.moz.com', 'www.apple.com', 'www.pizza.com');
$encodedDomains = json_encode($batchedDomains);
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
	$da = round($value->pda,2);
	$rank = round($value->umrp,2);
	$pa = round($value->upa,2);
}
?>
