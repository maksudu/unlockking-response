<?php

/**

 *	@author Dhru.com

 *	@APi kit version 2.0 March 01, 2012

 *	@Copyleft GPL 2001-2011, Dhru.com

 **/
require ('header.php');
include ('dhrufusionapi.class.php');
define("REQUESTFORMAT", "JSON"); // we recommend json format (More information http://php.net/manual/en/book.json.php)
define('DHRUFUSION_URL', "");
define("USERNAME", "");
define("API_ACCESS_KEY", "");

$api = new DhruFusion();
// Debug on
$api->debug = true;


$para['ID'] = "10319"; // got from 'imeiservicelist' [SERVICEID]
$request = $api->action('getimeiservicedetails', $para);


echo '<PRE>';
print_r($request);
echo '</PRE>';

?>