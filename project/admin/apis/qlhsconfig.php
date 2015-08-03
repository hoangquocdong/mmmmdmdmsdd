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



    $data = array('status' => 500, 'content' => 'Can not connect to db!');
    
    $sql='SELECT `id_pwc`, `name_pwc` FROM `power_company` WHERE 1'; 
	$result = mysql_query($sql) or die(json_encode($data));
	
	$pclist = array();

	while($row = mysql_fetch_array($result)){
		$tmp = array(
			'id_pwc' => $row['id_pwc'],
			'name_pwc' => $row['name_pwc']
		);

		array_push($pclist,$tmp);
	}

	$adata = array();

    foreach($pclist as $key=>$value) {

    	$tmp = getallsub($value['id_pwc']);
    	$tmpp = array();
    	array_push($tmpp, $value);
    	array_push($tmpp, $tmp);
    	array_push($adata, $tmpp);

	}

	$object = $adata;

	$getmoresubinfo = getmoresubinfo();

	$getallinvestor = getallinvestor();


    function getallsub($ipc){

		$data = array('status' => 500, 'content' => 'Can not connect to db!');

		$sql	=	'SELECT `id_sub`, `name_sub` 
					FROM `substation_power` 
					WHERE `id_pwc`='.$ipc; 

		$result = mysql_query($sql) or die(json_encode($data));
		$data = array();

		while($row = mysql_fetch_array($result)){
			
			$tmp = new stdClass();

			$tmp ->{'id_sub'} = $row['id_sub'];
			$tmp ->{'name_sub'} = $row['name_sub'];

			// $tmp = array(
			// 	'id_sub' => $row['id_sub'], 
			// 	'name_sub' => $row['name_sub']
			// );
			array_push($data, $tmp);

		}
		
		return $data;

	}
	    

	function getalldocs($pcarray){
		$subid_arr = array(); $subid = 'id_sub';
	    foreach($pcarray as $key => $value) {
	    	if (isset($value[1])){
	    		$docs = array();
	    		foreach($value[1] as $key => $val) {
	    			$key = 'idsub_'.$val->$subid;
	    			$subid_arr[$key]=getdocs_in_a_sub($val->$subid);
	    		}
	    	}
	    }
	    return $subid_arr;
	}

	function getdocs_in_a_sub($subid){
		
		$sql = "SELECT `ID`, `datatype`, `id_sub`, `name_file`, `link_file`, `time_start_file`, `number_file`
	    		FROM `profile_sub` WHERE `id_sub` = $subid ORDER BY `datatype`";

		$result = mysql_query($sql) or die('0');

		$data = array();
		while($row = mysql_fetch_array($result)){
			
			$tmp = array(
					'ID' => $row['ID'], 
					'datatype' => $row['datatype'],
					'id_sub' => $row['id_sub'],
					'name_file' => $row['name_file'],
					'link_file' => $row['link_file'],
					'time_start_file' => $row['time_start_file'],
					'number_file' => $row['number_file']
				);
			array_push($data, $tmp);
		}
		return $data;
	}

	function getmoresubinfo(){
		/*
		*	WHERE status ?
		*/

		$sql='SELECT `ID`, `id_investor`, `id_sub` FROM `substation_power` WHERE 1'; 

		$result = mysql_query($sql) or die('0');
		$data = array();
		while($row = mysql_fetch_array($result)){
			$tmp = array(
					'ID' => $row['ID'], 
					'id_investor' => $row['id_investor']
				);
			$key = 'idsub_'.$row['id_sub'];
			$data[$key] = $row['id_investor'];
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

	function returnresult($object, $getmoresubinfo, $getallinvestor){

		$objlength = sizeof($object);
		$idsub = 'id_sub';
		
		for ($i=0; $i< $objlength; $i++){
			//push more sub info 

			$tmp = array();

			for ($j=0; $j<sizeof($object[$i][1]); $j++){

				/*
				*	Lấy thông tin investor
				*/
				$ids = $object[$i][1][$j] ->$idsub;
				$keys = 'idsub_'.$ids; $inskey = 'insinfo';
				if (isset($getmoresubinfo[$keys])){

					$id_investor = $getmoresubinfo[$keys];
					$id_investor = 'idins_'.$id_investor;
					if (isset($getallinvestor[$id_investor])){
						$object[$i][1][$j] ->$inskey = $getallinvestor[$id_investor];
					} else {$object[$i][1][$j] ->$inskey ='';}

				} else {$object[$i][1][$j] ->$inskey ='';}

				/*
				*	Lấy thông tin hs
				*/

				$hskey = 'hoso';
				$object[$i][1][$j] ->$hskey = getdocs_in_a_sub($ids);

			}
		}

		return $object;
	}

	$rs = returnresult($object, $getmoresubinfo, $getallinvestor);

	echo json_encode($rs);
	//echo json_encode($allins);
	//echo $time_elapsed_us = microtime(true) - $start;

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 