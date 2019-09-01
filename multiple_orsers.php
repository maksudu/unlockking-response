<?php
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
                        //print_r($response);
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

#include ('dhrufusionapi.class.php');
define("REQUESTFORMAT", "JSON"); // we recommend json format (More information http://php.net/manual/en/book.json.php)
define('DHRUFUSION_URL', "https://www.unlockking.us/");
define("USERNAME", "muhitmonsur");
define("API_ACCESS_KEY", "N9-JXT-6NQ-IJ2-5KC-N9C-MBU-12Y");
//error_log( $suwp_dhru_imei . '  BEING SUBMITTED, NOT YET PROCESSED.' );

$servername = "how2zzcom.ipagemysql.com";
$username = "SDyVRGrgqr72kR0";
$password = "moURoyaMfXbvd4oi";
$dbname = "ss_dbname_72n51cab1h";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection

$sql = "SELECT * FROM wp_vhut_api_products_serviceid";
//echo "sql: ".$sql."</br>";
$result = $conn->query($sql);
$error = 0;
$apireq = 0;
$api_status = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {

        $productsid = $row['products_id'];
        $serviceid = $row['service_id'];

        $sql1 = "SELECT p.ID,p.post_status,p.post_title,pm.order_item_name,item.order_item_id
		FROM wp_vhut_posts AS p
		JOIN  wp_vhut_woocommerce_order_items  AS pm ON p.ID = pm.order_ID  
		JOIN wp_vhut_woocommerce_order_itemmeta AS item ON item.order_item_id=pm.order_item_id
		AND p.post_status = 'wc-processing' AND item.meta_value=5222 ";
        $results = $conn->query($sql1);
        while ($rows = $results->fetch_assoc()) {
            $order_id = $rows['ID'];
            $order_metaid = $rows['order_item_id'];
            $sql2 = "SELECT * FROM wp_vhut_woocommerce_order_itemmeta WHERE order_item_id=$order_metaid ";
            $resultsm = $conn->query($sql2);
            while ($rowst = $resultsm->fetch_assoc()) {
                $duplicate = "SELECT * FROM wp_vhut_api_unlockking_response WHERE order_id=$order_id ";
                //echo "sql: " . $duplicate . "</br>";
                $resultDuplicate = $conn->query($duplicate);
                if ($resultDuplicate->num_rows > 0) {
                    echo 'Message Iemi already exists' . "</br>";

                } else {
                    $metakey = $rowst['meta_key'];
                    if ($metakey == '_qty') {
                        $qty = $rowst["meta_value"];
//                        echo $qty;
//                        echo 'fffffffff';

                    }
                    if ($qty > 1) {
//                        echo 'ggggggggg';
//                        echo '4';
//                        echo 'ttttt';

                        if ($metakey == 'IMEI/SL:') {

                            $metavalue = $rowst["meta_value"];
                            $myString = $metavalue;
                            $myArray = explode(',', $myString);
                            foreach ($myArray as $my_Array) {
                                $api = new DhruFusion();
                                // Debug on
                                $api->debug = true;
                                echo $my_Array;
                                $para['IMEI'] = $my_Array;
                                $para['ID'] = $serviceid;
                                $request = $api->action('placeimeiorder', $para);
                                //echo $request['IMEI'];
                                //echo $request['ID'];
                                print_r($request);
                                $error = $request['ERROR'][0]['MESSAGE'];
                                $apireq = $request['SUCCESS']['0']['MESSAGE'];

                                if ((!isset($apireq)) || ($apireq < 1) || (!isset($error)) || ($error < 1)) {
                                    #$order_id=$request['ID'];
                                    if ($error) {
                                        $nonces = $request['ERROR'][0]['FULL_DESCRIPTION'];
                                        $reference_id = '';
                                        $name = 'UK,Automatic,Multiple,Order Error,';
                                        $massages = $name . " " . $reference_id;
                                        $api_status = 2;
                                        echo $massages;
                                    } else {
                                        $nonces = $request['SUCCESS']['0']['MESSAGE'];
                                        $reference_id = $request['SUCCESS']['0']['REFERENCEID'];
                                        $name = 'UK,Automatic,Multiple,Order Submitted,';
                                        $massages = $name . " " . $reference_id;
                                        $api_status = 0;
                                    }

                                    mysqli_query($conn, "INSERT INTO wp_vhut_api_unlockking_response(order_id,reference_id,nonces,imei,api_status)
                                        VALUES ('$order_id','$reference_id','$nonces','$my_Array','$api_status')");

                                    // meta_key_update($order_id, $reference_id, $conn, $massages, $metavalue);
                                } else {
                                    echo "Please Try Again Later!!!! <br>";
                                }


                            }


                        }

                    }
                    /////
                }
            }
            $resultsm->free();
        }
    }
    $results->free();
    $api = null;
    $para['ID'] = null;

} else {
    echo "0 results";
}
$result->free();
$conn->close();

function meta_key_update($order_id, $reference_id, $conn, $massages, $metavalue)
{

    $update = "SELECT * FROM  wp_vhut_postmeta WHERE  post_id =$order_id";
    echo 'ttttttt';
    echo "sql: " . $update . "</br>";
    $resultup = $conn->query($update);
    while ($rowsup = $resultup->fetch_assoc()) {
        $customeupdate = $rowsup['meta_key'];
        //echo $customeupdate;
        if ($customeupdate == '_wc_acof_2') {
            $sqlupdate = "UPDATE wp_vhut_postmeta SET meta_value='$massages' WHERE  post_id =$order_id AND  meta_key ='_wc_acof_2' ";
            if ($conn->query($sqlupdate) === TRUE) {
                echo "Record Update successfully";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }
        if ($customeupdate == '_wc_acof_4') {
            $sqlupdate = "UPDATE wp_vhut_postmeta SET meta_value='$metavalue' WHERE  post_id =$order_id AND  meta_key ='_wc_acof_4' ";
            if ($conn->query($sqlupdate) === TRUE) {
                echo "Record Update successfully";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }
    }
    $resultup->free();
    $conn->close();
}
