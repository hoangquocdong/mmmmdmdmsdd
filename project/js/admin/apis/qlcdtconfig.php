<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$id =  isset($_REQUEST['id'])? $_REQUEST['id'] : 0;
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';
$value =  isset($_REQUEST['value'])? $_REQUEST['value'] : '';

$start = microtime(true);




  CONNECT_DB();

    mysql_query("SET NAMES utf8");

    /*
    	* Check update menu
    */

    $allinvestor = getallcdt();


	function getallcdt(){

		$data = array('status' => 500, 'content' => 'Can not connect to db!');

		$sql='SELECT `ID`, `id_investor`, `name_investor`, `phone_investor`, `email_investor`, `adress_investor`
		FROM `investor` WHERE 1'; 

		$result = mysql_query($sql) or die(json_encode($data));
		$data = array();

		while($row = mysql_fetch_array($result)){
			$tmp = array(
				'ID' => $row['ID'], 
				'id_investor' => $row['id_investor'], 
				'name_investor' => $row['name_investor'],
				'phone_investor' => $row['phone_investor'], 
				'email_investor' => $row['email_investor'], 
				'adress_investor' => $row['adress_investor']
			);

			$keyword = "cdt_".$row['ID'];
			$data["$keyword"] = $tmp;
		}

		return $data;
	}

	$data = array('status' => 200, 'content' => 'successful!');
	$data['data'] = $allinvestor;

	echo json_encode($data);

	CLOSE_DB();
	unset($sql, $result, $data);
          
?> 