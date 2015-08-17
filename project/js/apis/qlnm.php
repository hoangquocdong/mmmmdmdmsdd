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
    $sql = 'SELECT `flag_change` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id;
	$result = mysql_query($sql) or die('0');
	$rs = 0;
	while($row = mysql_fetch_array($result)){
		$rs = $row['flag_change'];
	}
	if ($rs == 1) {
		include('menuleftupdate.php');
	}

	/*
    	* Check update menu
    */

    $object = getmenuleft($token, $id);
    $allpc = getalltpc();
    $allsub = getallsub();
    $allins = getallinvestor();
    
    function getmenuleft($token, $id){

		$return='';

		$sql = 'SELECT `cacheall` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id;
		$result = mysql_query($sql) or die('0');
		
		if (mysql_num_rows($result)){
			$rows = mysql_num_rows($result);
			if ($rows == 1) { 
				while($rsl=mysql_fetch_array($result)){
					$val = $rsl['cacheall'];
			    }
			    $return = $val;
			} else {
				$return = '';
			}
		} else {
			$return = '';
		}

		return json_decode($return);
	}

	function getalltpc(){
		$sql='SELECT `ID`, `id_pwc`, `name_pwc`, `name_operator_pc`, `phone_operator_pc`, `email_operator_pc`,
					 `status`, `id_orderby` FROM `power_company` WHERE 1'; 
		$result = mysql_query($sql) or die('0');
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
			$key = 'idpc_'.$row['id_pwc'];
			$data[$key] = $tmp;
			//array_push($data, $tmp;);
			//echo $key.' -- '.json_encode($data[$key]);
		}
		return $data;
	}

	function getallsub(){
		/*
		*	WHERE status ?
		*/

		$sql='SELECT `ID`, `id_pwc`, `id_investor`, `id_sub`, `name_sub`, `phone_sub`, `email_sub`, `address_sub`, 
					  `connection_sub`,`ip_address`, `levelvoltage`, `levelcapacity`, `type_sub`, `gps_sub`, `status`, `startdate`
					   FROM `substation_power` WHERE 1'; 

		$result = mysql_query($sql) or die('0');
		$data = array();
		while($row = mysql_fetch_array($result)){
			$tmp = array(
					'ID' => $row['ID'], 
					'id_pwc' => $row['id_pwc'],
					'id_investor' => $row['id_investor'],
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
			$key = 'idsub_'.$row['id_sub'];
			$data[$key] = $tmp;
		}
		return $data;
	}

	function getallinvestor(){
		/*
		*	WHERE status ?
		*/

		$sql =	'SELECT `ID`, `id_investor`, `name_investor`, `phone_investor`, `email_investor`, `adress_investor`
				FROM `investor` WHERE 1'; 

		$result = mysql_query($sql) or die('0');
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
			$key = 'idins_'.$row['id_investor'];
			$data[$key] = $tmp;
		}
		return $data;
	}


	function returnresult($object, $allpc, $allsub, $allins){

		//$object = $object;
		//$rs = json_encode($object);

		//die($rs);

		$objlength = sizeof($object);
		$idsub = 'id_sub';
		$idpwc = 'id_pwc';
		//echo $objlength; exit();

		for ($i=0; $i< $objlength; $i++){
			//push more pc info 
			//$idpc = $object[$i][1][$j] ->$idpwc;
			//$keys = 'idsub_'.$ids; $inskey = 'insinfo';



			$key = 'idpc_'.$object[$i][0]->$idpwc;;
			if (isset($allpc[$key])){
				$object[$i]['pcinfo'] = $allpc[$key];
			} else {$object[$i]['pcinfo'] = '';}

			$tmp = array();

			for ($j=0; $j<sizeof($object[$i][1]); $j++){
				$ids = $object[$i][1][$j] ->$idsub;
				$keys = 'idsub_'.$ids; $subkey = 'subinfo';$inskey = 'insinfo';
				if (isset($allsub[$keys])){
					$object[$i][1][$j] -> $subkey = $allsub[$keys];
				} else {$object[$i][1][$j] -> $subkey = '';}


				$investorid = 'idins_'.$allsub[$keys]['id_investor'];
				if (isset($allins[$investorid])){
					$object[$i][1][$j] -> $inskey = $allins[$investorid];
				} else {$object[$i][1][$j] -> $inskey = '';}

			}
		}

		return $object;
	}

	$rs = returnresult($object, $allpc, $allsub, $allins);

	echo json_encode($rs);
	//echo json_encode($allins);
	//echo $time_elapsed_us = microtime(true) - $start;

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 