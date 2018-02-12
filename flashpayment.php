<?php
require_once __DIR__ . '/vendor/autoload.php';
use Endroid\QrCode\QrCode;
$allowRequestParameters = False;

// replace below address with your own
$address = "";
$amount  = 0; // Leave at 0 to not request a specific amount
$message = ""; // Optional message

if($allowRequestParameters) {
	$address = ($_REQUEST['address']) ? $_REQUEST['address'] : $address;
	$amount  = ($_REQUEST['amount']) ? $_REQUEST['amount'] : $amount;
}


// Compile parameters
$string = "{$address}";
($amount) ? $string .= "?amount={$amount}" : "";
if(empty($message)) {
	$string .= ($amount) ? "&" : "?";
	$string .= "message={$message}";
}


// Generate the QR Code
$qrCode = new QrCode("flashcoin:{$string}");
header('Content-Type: '.$qrCode->getContentType());
echo $qrCode->writeString();
