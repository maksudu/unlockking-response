<?php

global $post, $woocommerce, $the_order;
if (!extension_loaded('curl')) {
    trigger_error('cURL extension not installed', E_USER_ERROR);
}

class DhruFusion
{
    var $xmlData;
    var $xmlResult;
    var $debug;
    var $action;

    function __construct()
    {
        $this->xmlData = new DOMDocument();
    }

    function getResult()
    {
        return $this->xmlResult;
    }

    function action($action, $arr = array())
    {
        if (is_string($action)) {
            if (is_array($arr)) {
                if (count($arr)) {
                    $request = $this->xmlData->createElement("PARAMETERS");
                    $this->xmlData->appendChild($request);
                    foreach ($arr as $key => $val) {
                        $key = strtoupper($key);
                        $request->appendChild($this->xmlData->createElement($key, $val));
                    }
                }
                $posted = array(
                    'username' => USERNAME,
                    'apiaccesskey' => API_ACCESS_KEY,
                    'action' => $action,
                    'requestformat' => REQUESTFORMAT,
                    'parameters' => $this->xmlData->saveHTML());
                $crul = curl_init();
                curl_setopt($crul, CURLOPT_HEADER, false);
                curl_setopt($crul, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                //curl_setopt($crul, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($crul, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($crul, CURLOPT_URL, DHRUFUSION_URL . '/api/index.php');
                curl_setopt($crul, CURLOPT_POST, true);
                curl_setopt($crul, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($crul, CURLOPT_POSTFIELDS, $posted);
                $response = curl_exec($crul);
                if (curl_errno($crul) != CURLE_OK) {
                    echo curl_error($crul);
                    curl_close($crul);
                } else {
                    curl_close($crul);
                    // $response = XMLtoARRAY(trim($response));
                    if ($this->debug) {
                        echo "<textarea rows='20' cols='200'> ";
                        print_r($response);
                        echo "</textarea>";
                    }
                    return (json_decode($response, true));
                }
            }
        }
        return false;
    }
}

function XMLtoARRAY($rawxml)
{
    $xml_parser = xml_parser_create();
    xml_parse_into_struct($xml_parser, $rawxml, $vals, $index);
    xml_parser_free($xml_parser);
    $params = array();
    $level = array();
    $alreadyused = array();
    $x = 0;
    foreach ($vals as $xml_elem) {
        if ($xml_elem['type'] == 'open') {
            if (in_array($xml_elem['tag'], $alreadyused)) {
                ++$x;
                $xml_elem['tag'] = $xml_elem['tag'] . $x;
            }
            $level[$xml_elem['level']] = $xml_elem['tag'];
            $alreadyused[] = $xml_elem['tag'];
        }
        if ($xml_elem['type'] == 'complete') {
            $start_level = 1;
            $php_stmt = '$params';
            while ($start_level < $xml_elem['level']) {
                $php_stmt .= '[$level[' . $start_level . ']]';
                ++$start_level;
            }
            $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
            eval($php_stmt);
            continue;
        }
    }
    return $params;
}

/**
 * @author Dhru.com
 * @APi kit version 2.0 March 01, 2012
 * @Copyleft GPL 2001-2011, Dhru.com
 **/


#include ('dhrufusionapi.class.php');
define("REQUESTFORMAT", "JSON"); // we recommend json format (More information http://php.net/manual/en/book.json.php)
define('DHRUFUSION_URL', "https://www.unlockking.us/");
define("USERNAME", "muhitmonsur");
define("API_ACCESS_KEY", "N9-JXT-6NQ-IJ2-5KC-N9C-MBU-12Y");
$status = '';
date_default_timezone_set('US/Eastern');
$currenttime = date('h:i:s:u');
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
echo "sql: " . $sql . "</br>";
$result = $conn->query($sql);
if ($result->num_rows > 1) {
    //echo "heloo";
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $api = new DhruFusion();
        // Debug on
        $api->debug = true;
        $order_id = $row['order_id'];
        $para['ID'] = $row['reference_id'];
        echo "order_id:" . $row['order_id'] . "</br>";
        echo "reference_id:" . $row['reference_id'] . "</br>";
        echo "</br></br>";
        if ($row['reference_id'] == "") {
            echo 'reference id not found</br>';
        } else {
            echo 'reference data find</br>';
            //echo $order_id;
            $request = $api->action('getimeiorder', $para);
            //echo "</br></br>";
            //print_r($request);
            //echo "</br></br>";
            $reference_id = $request['ID'];
            //echo "</br></br>";
            $status = $request['SUCCESS']['0']['STATUS'];
            //echo "</br></br>";
            $description = $request['SUCCESS']['0']['CODE'];
            //echo "</br></br>";
            //echo "reference_id:".$request['ID']."</br>";
            //echo "</br></br>";
            //echo "status:".$request['SUCCESS']['0']['STATUS']."</br>";
            //echo "</br></br>";
            //echo "description:".$request['SUCCESS']['0']['CODE']."</br>";
            //echo "</br></br>";
            //processing, on-hold, cancelled, completed
            if ($status == 4) {
                $update_status = 'completed';
                $apistatus = 1;
                $metavalue = ',completed,' . " " . $currenttime;
            } elseif ($status == 1) {
                $update_status = 'processing';
                $apistatus = 0;
                $metavalue = 'processing';
            } elseif ($status == 2) {
                $update_status = 'processing';
                $apistatus = 0;
                $metavalue = 'processing';
            } elseif ($status == 3) {
                $update_status = 'processing';
                $apistatus = 1;
                $metavalue = ',rejected,' . " " . $description;
            } else {
                $update_status = 'processing';
                $apistatus = 0;
                $metavalue = 'processing';
            }
            $sql = "UPDATE wp_vhut_api_unlockking_response SET description ='$description',status='$status',api_status='$apistatus' where reference_id='$reference_id'";
            //echo "sql: ".$sql."</br>";
            if ($conn->query($sql) === TRUE) {
                echo 'ooooooooo';

                if ($status == 4) {
                    //echo 'yyyyyyy';
                    $orders = new WC_Order($order_id);
                    echo "orders: " . $orders . "</br>";
                    // The text for the note
                    //$note = __($description);
                    $orders->set_customer_note($description);
                    $orders->save();
                    if (!empty($orders)) {
                        $orders->update_status($update_status);
                        meta_key_update($order_id, $conn, $description, $metavalue);
                    }
                    echo "Record updated successfully";
                }

            } else {
                echo "Error updating record: " . $conn->error;
            }
        }

        $para['ID'] = null;
        $api = null;
    }
} else {
    echo "0 results";
}
$result->free();
$conn->close();

function meta_key_update($order_id, $conn, $description, $metavalue)
{
    echo "okay";
    $update = "SELECT * FROM  wp_vhut_postmeta WHERE  post_id =$order_id";
    echo "sql: " . $update . "</br>";
    echo 'ttt';

    $resultup = $conn->query($update);
    while ($rowsup = $resultup->fetch_assoc()) {
        $customeupdate = $rowsup['meta_key'];
        echo $customeupdate;
        if ($customeupdate == '_wc_acof_2') {
            $name = $rowsup["meta_value"] . $metavalue;
            echo "name: " . $name . "</br>";
            $sqlupdate = "UPDATE wp_vhut_postmeta SET meta_value='$name' WHERE  post_id =$order_id AND  meta_key ='_wc_acof_2' ";
            echo "sqlupdate: " . $sqlupdate . "</br>";
            if ($conn->query($sqlupdate) === TRUE) {
                echo "Record Update successfully";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }
    }
    //echo "</br></br>";
    $resultup->free();
    $sqlopt = "update wp_vhut_EWD_OTP_Orders set Order_Notes_Public='$description' where WooCommerce_ID='$order_id'";
    echo "sqlopt: " . $sqlopt . "</br>";
    if ($conn->query($sqlopt) === TRUE) {
        echo "opt update";
    } else {
        echo "not upadte";
    }
    $conn->close();
}


?>