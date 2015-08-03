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

    $data = array('status' => 500, 'content' => 'Can not connect to db!');
    $sql='SELECT `ID`, `id_pwc`, `name_pwc` FROM `power_company` WHERE 1'; 
	$result = mysql_query($sql) or die(json_encode($data));
	
	$pclist = array();

	while($row = mysql_fetch_array($result)){
		$tmp = array(
			'ID' => $row['ID'], 
			'id_pwc' => $row['id_pwc'],
			'name_pwc' => $row['name_pwc']
		);

		$keyword = "pcid_".$row['ID'];
		$pclist["$keyword"] = $tmp;
	}


    $alldata = getalldata();

    foreach($pclist as $key=>$value) {

    	$tmp = array();
	    foreach($alldata as $keys=>$values) {
		    if ($value['id_pwc'] == $values['id_pwc']){
		    	
		    	$keyword = "sub_".$values['id_sub'];
				$tmp[$keyword] = $values;
		    }
		}
		$pclist[$key]['list_sub'] = $tmp;

	}

	function getalldata(){

		$data = array('status' => 500, 'content' => 'Can not connect to db!');

		$sql	=	'SELECT `ID`, `id_pwc`, `id_investor`, `id_sub`, `name_sub`, `phone_sub`, `email_sub`, 
						`address_sub`, `connection_sub`, `ip_address`, `levelvoltage`, `levelcapacity`, `type_sub`, 
						`gps_sub`, `status`, `startdate` 
					FROM `substation_power` 
					WHERE 1'; 

		$result = mysql_query($sql) or die(json_encode($data));
		$data = array();

		while($row = mysql_fetch_array($result)){
			$tmp = array(
				'ID' => $row['ID'], 
				'id_pwc' => $row['id_pwc'], 
				'id_investor' => $row['id_investor'],
				'id_sub' => $row['id_sub'], 
				'name_sub' => $row['name_sub'], 
				'phone_sub' => $row['phone_sub'],
				'email_sub' => $row['email_sub'], 
				'address_sub' => $row['address_sub'], 
				'connection_sub' => $row['connection_sub'],
				'ip_address' => $row['ip_address'], 
				'levelvoltage' => $row['levelvoltage'], 
				'levelcapacity' => $row['levelcapacity'],
				'type_sub' => $row['type_sub'],
				'gps_sub' => $row['gps_sub'],
				'status' => $row['status'],
				'startdate' => $row['startdate']
			);

			$keyword = "sub_".$row['ID'];
			$data[$keyword] = $tmp;
		}

		return $data;
	}

	$data['status'] = 200; $data['content'] = 'successful!';
	$data['data'] = $pclist;

	echo json_encode($data);

	CLOSE_DB();
	unset($sql, $result, $data);
          
?> 