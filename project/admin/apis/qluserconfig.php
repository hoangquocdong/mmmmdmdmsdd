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
 //    $sql = 'SELECT `flag_change` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id;
	// $result = mysql_query($sql) or die('0');
	// $rs = 0;
	// while($row = mysql_fetch_array($result)){
	// 	$rs = $row['flag_change'];
	// }
	// if ($rs == 1) {
	// 	include('menuleftupdate.php');
	// }

	/*
    	* Check update menu
    */

 //    $object = getmenuleft($token, $id);
    $allpc = getalltpc();
    //$alluser = getalluser();
 //    $allins = getallinvestor();
    
 //    function getmenuleft($token, $id){

	// 	$return='';

	// 	$sql = 'SELECT `cacheall` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id;
	// 	$result = mysql_query($sql) or die('0');
		
	// 	if (mysql_num_rows($result)){
	// 		$rows = mysql_num_rows($result);
	// 		if ($rows == 1) { 
	// 			while($rsl=mysql_fetch_array($result)){
	// 				$val = $rsl['cacheall'];
	// 		    }
	// 		    $return = $val;
	// 		} else {
	// 			$return = '';
	// 		}
	// 	} else {
	// 		$return = '';
	// 	}

	// 	return json_decode($return);
	// }

	function getalltpc(){

		$sql='SELECT `ID`, `id_pwc`, `name_pwc` FROM `power_company` WHERE 1'; 
		$result = mysql_query($sql) or die('0');
		$data = array();

		while($row = mysql_fetch_array($result)){
			$tmp = array(
				'ID' => $row['ID'], 
				'id_pwc' => $row['id_pwc'], 
				'name_pwc' => $row['name_pwc']
			);

			$keyword = "pcid_".$row['ID'];
			$data["$keyword"] = $tmp;

			//array_push($data, $tmp);
		}

		return $data;
	}

	function getalluser($id_pwc){
		/*
		*	WHERE status ?
		*/


		$sql = 'SELECT `ID`, `id_pwc`, `id_investor`, `id_sub`, `full_name`, `user_name`, `password`, `office_name`, 
				`department_name`, `phone_number`, `email`, `enable`, `last_visit`, `visit_number`, `register_date`, 
				`writable`, `usertype`, `editable`, `flag_change` , `permission` 
				FROM `user` 
				WHERE `id_pwc` = '.$id_pwc;

		$result = mysql_query($sql) or die('0');

		$data = array();

		while($row = mysql_fetch_array($result)){

			$tmp = array(
					'ID' => $row['ID'], 
					'id_pwc' => $row['id_pwc'],
					'id_investor' => $row['id_investor'],
					'id_sub' => $row['id_sub'],
					'full_name' => $row['full_name'],
					'user_name' => $row['user_name'],
					'password' => $row['password'], 
					'office_name' => $row['office_name'],
					'department_name' => $row['department_name'],
					'phone_number' => $row['phone_number'],
					'email' => $row['email'],
					'enable' => $row['enable'],
					'last_visit' => $row['last_visit'], 
					'visit_number' => $row['visit_number'], 
					'register_date' => $row['register_date'],
					'writable' => $row['writable'],
					'usertype' => $row['usertype'], 
					'editable' => $row['editable'], 
					'permission' => $row['permission'], 
					'flag_change' => $row['flag_change']
				);

			//array_push($data, $keyword);

			$keyword = "id_".$row['ID'];
			$data["$keyword"] = $tmp;

		}

		return $data;

	}


	function returnresult($allpc){

		$object = array();



		foreach ($allpc as $key => $item) {

			$tmp = array(

				'pc_name' => $item['name_pwc'], 
				'list_users' => getalluser($item['ID'])

			);
			
			//array_push($object, $tmp);

			$keyword = "pcid_".$item['ID'];
			$object["$keyword"] = $tmp;

		}


		// for ($i=0; $i<sizeof($allpc); $i++){

		// 	$tmp = array(

		// 			'pc_name' => $allpc[$i]['name_pwc'], 
		// 			'list_users' => getalluser($allpc[$i]['ID'])

		// 		);
			
		// 	array_push($object, $tmp);
		// }

		return $object;
	}

	$rs = returnresult($allpc);

	echo json_encode($rs);

	//echo json_encode($allins);
	//echo $time_elapsed_us = microtime(true) - $start;

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 