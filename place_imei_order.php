<?php

/**

 *	@author Dhru.com

 *	@APi kit version 2.0 March 01, 2012

 *	@Copyleft GPL 2001-2011, Dhru.com

 **/
include ('dhrufusionapi.class.php');
define("REQUESTFORMAT", "JSON"); // we recommend json format (More information http://php.net/manual/en/book.json.php)
define('DHRUFUSION_URL', "https://www.unlockking.us/");
define("USERNAME", "muhitmonsur");
define("API_ACCESS_KEY", "N9-JXT-6NQ-IJ2-5KC-N9C-MBU-12Y");
$api = new DhruFusion();


// Debug on
$api->debug = true;
//Order #14844


$para['IMEI'] = "355796073708426";
$para['ID'] = "4667"; // got from 'imeiservicelist' [SERVICEID]
// PARAMETRES IS REQUIRED
// $para['MODELID'] = "";
// $para['PROVIDERID'] = "";
// $para['MEP'] = "";
// $para['PIN'] = "";
// $para['KBH'] = "";
// $para['PRD'] = "";
// $para['TYPE'] = "";
// $para['REFERENCE'] = "";
// $para['LOCKS'] = "";


$request = $api->action('placeimeiorder', $para);


echo '<PRE>';
print_r($request);
echo '</PRE>';

?>