<?php

/**
 * @author Dhru.com
 * @APi kit version 2.0 March 01, 2012
 * @Copyleft GPL 2001-2011, Dhru.com
 **/
#require('header.php');
include('dhrufusionapi.class.php');
define("REQUESTFORMAT", "JSON"); // we recommend json format (More information http://php.net/manual/en/book.json.php)
define('DHRUFUSION_URL', "https://www.unlockking.us/");
define("USERNAME", "muhitmonsur");
define("API_ACCESS_KEY", "N9-JXT-6NQ-IJ2-5KC-N9C-MBU-12Y");
$api = new DhruFusion();

// Debug on
$api->debug = true;
$servername = "how2zzcom.ipagemysql.com";
$username = "SDyVRGrgqr72kR0";
$password = "moURoyaMfXbvd4oi";
$dbname = "ss_dbname_72n51cab1h";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM wp_vhut_api_unlockking_response WHERE api_status='0'";
$result = $conn->query($sql);
echo '$result num_rows'."</br>";
print_r( $result->num_rows);
echo '$result num_rows'."</br>";
echo "</br>";
echo "</br>";
if (mysql_num_rows($result) > 0) {

    while ($row = mysql_fetch_assoc($result)) {
        echo "</br>";
        echo "$row:---</br>";
        print_r($row);
        echo "</br>";
        echo "</br>";
        $reference_id = $row['reference_id'];
        echo $reference_id . "kjkjkjk/n/r";
        $para['ID'] = "" . $reference_id;
        echo "</br>";
        echo "</br>";
        echo "</br>";
        echo "</br>";
        print_r($para['ID']);
        $request = $api->action('getimeiorder', $para);
        echo "</br>";
        echo "</br>";
        echo "request:---</br>";
        print_r($request);
    }

    mysql_free_result($result);

}




//
//if ($result->num_rows > 0 ) {
//    while ($result->num_rows ) {
//        $row = $result->fetch_assoc();
//        echo "</br>";
//        echo "$row:---</br>";
//        print_r( $row );
//        echo "</br>";
//        echo "</br>";
//        $reference_id = $row['reference_id'];
//        echo  $reference_id. "kjkjkjk/n/r";
//        $para['ID'] = "".$reference_id;
//        echo "</br>";
//        echo "</br>";
//        echo "</br>";
//        echo "</br>";
//        print_r($para['ID']);
//        $request = $api->action('getimeiorder', $reference_id);
//       // echo $para['ID']."hjhjh/n/r";
//        print_r($request);
//
//    }
//}else {
//    echo "0 results";
//}
$conn->close();


?>