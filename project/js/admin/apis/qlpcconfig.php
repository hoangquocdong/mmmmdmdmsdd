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

    $allpc = getalltpc();


	function getalltpc(){

		$data = array('status' => 500, 'content' => 'Can not connect to db!');

		$sql='SELECT `ID`, `id_pwc`, `name_pwc`, `name_operator_pc`, `phone_operator_pc`, `email_operator_pc`, `status`, `id_orderby` FROM `power_company` WHERE 1'; 
		$result = mysql_query($sql) or die(json_encode($data));
		
		$data = array();

		while($row = mysql_fetch_array($result)){
			$tmp = array(
				'ID' => $row['ID'], 
				'id_pwc' => $row['id_pwc'], 
				'name_pwc' => $row['name_pwc'],
				'name_operator_pc' => $row['name_operator_pc'], 
				'phone_operator_pc' => $row['phone_operator_pc'], 
				'email_operator_pc' => $row['email_operator_pc'], 
				'status' => $row['status'],
				'id_orderby' => $row['id_orderby']
			);

			$keyword = "pcid_".$row['ID'];
			$data["$keyword"] = $tmp;
		}

		return $data;
	}

	$data = array('status' => 200, 'content' => 'successful!');
	$data['data'] = $allpc;

	echo json_encode($data);

	CLOSE_DB();
	unset($sql, $result, $data);
          
?> 