<?php
ini_set('display_errors','on');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
/**

 *	@author Dhru.com

 *	@APi kit version 2.0 March 01, 2012

 *	@Copyleft GPL 2001-2011, Dhru.com

 **/
require ('header.php');
include ('dhrufusionapi.class.php');
define("REQUESTFORMAT", "JSON"); // we recommend json format (More information http://php.net/manual/en/book.json.php)
define('DHRUFUSION_URL', "http://yoursite.com/");
define("USERNAME", "XXXXXXXX");
define("API_ACCESS_KEY", "XXX-XXX-XXX-XXX-XXX-XXX-XXX-XXX");

$api = new DhruFusion();

// Debug on
$api->debug = true;

$request = $api->action('imeiservicelist');
#$entries = $request['SUCCESS']['0']['MESSAGE'];

echo '<PRE>';
#$tmp = explode('\n', (string) $request);
#echo $tmp;
#var_dump($request);
print_r($request);
#$stuff = array($entries);
 
#print serialize($stuff);
#print json_encode($stuff);
#print_r (explode(" ",$stuff));
#print join(',', $stuff);     
 #$stuff = array($entries);
 #print_r($stuff);
#echo $request['SUCCESS']['0']['MESSAGE'], "<br>";

#echo $request['SUCCESS']['0']['LIST'], "<br>";

#var_dump(current($entries));
#var_dump($request);

?>