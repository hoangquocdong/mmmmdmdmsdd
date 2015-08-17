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

$id_incident_type1 = 1; 
$id_incident_type2 = 1;
$id_incident_type3 = 1;
$id_incident_type4 = 1;
$id_incident_type5 = 1;
$id_incident_type6 = 1;
$id_incident_type7 = 1;
$id_incident_type8 = 1;

$start = microtime(true);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");

	function get_incident_process_history($process_code){
	
		$sql = 'SELECT `id_incident_code`,`content_process`,`time_process`,`user_process` FROM incident_process_history WHERE `id_incident_code` = '.$process_code.'';
		$result = mysql_query($sql) or die('0');
		$data = array();
		while($row = mysql_fetch_array($result)){
			$tmp = array(
				'id_incident_code' => $row['id_incident_code'],
				'content_process' => $row['content_process'],
				'time_process' => date('d-m-Y H:i:s', $row['time_process']),
				'user_process' => $row['user_process']
				);
			array_push($data, $tmp);
		}
		return $data;
	}	
	function get_name_sub($id_sub){
	
		$sql = 'SELECT `name_sub` FROM substation_power WHERE `id_sub` = '.$id_sub.'';
		$result = mysql_query($sql) or die('0');
		while($row = mysql_fetch_array($result)){
			$namesub = $row['name_sub'];
		}	
		return $namesub;
	}	
	
	function get_name_incident($id_incident){
	
		$sql = 'SELECT `name_incident` FROM incident_type WHERE `id_incident` = '.$id_incident.'';
		$result = mysql_query($sql) or die('0');
		while($row = mysql_fetch_array($result)){
			$name_incident = $row['name_incident'];
		}	
		return $name_incident;
	}	
	function get_name_cause($id_cause){
	
		$sql = 'SELECT `name_cause` FROM incident_cause WHERE `id_cause` = '.$id_cause.'';
		$result = mysql_query($sql) or die('0');
		while($row = mysql_fetch_array($result)){
			$name_cause = $row['name_cause'];
		}	
		return $name_cause;
	}		
	
	function get_array_type_incident(){
	
		$sql = 'SELECT `id_incident`, `name_incident` FROM incident_type' ;
		$result = mysql_query($sql) or die('0');
		$data = array();
		$number = 0;
		$array_type_sum = new stdClass();
		while($row = mysql_fetch_array($result)){
			$array_type = new stdClass();
			$array_type ->{'name'}=$row['name_incident']; 
			$array_type ->{'data'} = array(0=>get_number_type_incident($row['id_incident']));
			
			array_push($data, $array_type);
		}	
			
		return $data;
	}
	
	function get_array_type_incident_pie(){
	
		$sql = 'SELECT `id_incident`, `name_incident` FROM incident_type' ;
		$result = mysql_query($sql) or die('0');
		$data = array();
		$number = 0;
		$array_type_sum = new stdClass();
		while($row = mysql_fetch_array($result)){
			$array_type = new stdClass();
			$array_type ->{'name'}=$row['name_incident']; 
			$array_type ->{'y'} = get_number_type_incident($row['id_incident']);
			
			array_push($data, $array_type);
		}	
			
		return $data;
	}


	
	
	function get_number_type_incident($id_incident){
		$sql = 'SELECT `id_incident` FROM incident_manager WHERE `id_incident` = '.$id_incident.'';
		$result = mysql_query($sql) or die('0');
		$number = 0;	
		while($row = mysql_fetch_array($result)){
			$number ++;
		}
		return $number;
	}

	function get_array_type_cause(){
	
		$sql = 'SELECT `id_cause`, `name_cause` FROM incident_cause' ;
		$result = mysql_query($sql) or die('0');
		$data = array();
		$number = 0;
		$array_type_sum = new stdClass();
		while($row = mysql_fetch_array($result)){
			$array_type = new stdClass();
			$array_type ->{'name'}=$row['name_cause']; 
			$array_type ->{'data'} = array(0=>get_number_type_cause($row['id_cause']));
			
			array_push($data, $array_type);
		}	
			
		return $data;
	}

	function get_array_type_cause_pie(){
	
		$sql = 'SELECT `id_cause`, `name_cause` FROM incident_cause' ;
		$result = mysql_query($sql) or die('0');
		$data = array();
		$number = 0;
		$array_type_sum = new stdClass();
		while($row = mysql_fetch_array($result)){
			$array_type = new stdClass();
			$array_type ->{'name'}=$row['name_cause']; 
			$array_type ->{'y'} = get_number_type_cause($row['id_cause']);
			
			array_push($data, $array_type);
		}	
			
		return $data;
	}
	
	
	
	function get_number_type_cause($id_cause){
		$sql = 'SELECT `id_cause` FROM incident_manager WHERE `id_cause` = '.$id_cause.'';
		$result = mysql_query($sql) or die('0');
		$number = 0;	
		while($row = mysql_fetch_array($result)){
			$number ++;
		}
		return $number;
	}


	
	
	$sql = 'SELECT `ID`, `id_sub`,`serial_meter`,`id_incident`,`id_cause`,`note_incident`,`time_incident`,
	`status_incident`,`id_user_create`,`name_user_create` FROM incident_manager';
	$result = mysql_query($sql) or die('0');
	$data = array();
	$array_type1= new stdClass();
	$array_type2= new stdClass();
	$array_type3= new stdClass();
	$array_type4= new stdClass();
	$array_type5= new stdClass();
	$array_type6= new stdClass();
	$array_type7= new stdClass();
	$array_type8= new stdClass();

	
	while($row = mysql_fetch_array($result)){
		
		if ($row['id_incident'] == 1) { $array_type1 ->{'name'}='Mất kết nối'; $array_type1 ->{'data'} = array(0=>$id_incident_type1++);}
		if ($row['id_incident'] == 2) { $array_type2 ->{'name'}='Lỗi mdms'; $array_type2 ->{'data'} = array(0=>$id_incident_type2++);}
		if ($row['id_incident'] == 3) { $array_type3 ->{'name'}='Lỗi LogTran'; $array_type3 ->{'data'} = array(0=>$id_incident_type3++);}
		if ($row['id_incident'] == 4) { $array_type4 ->{'name'}='Lỗi SerTran'; $array_type4 ->{'data'} = array(0=>$id_incident_type4++);}
		if ($row['id_incident'] == 5) { $array_type5 ->{'name'}='Lỗi CSDL nhà máy'; $array_type5 ->{'data'} = array(0=>$id_incident_type5++);}
		if ($row['id_incident'] == 6) { $array_type6 ->{'name'}='Lỗi TB đo xa'; $array_type6 ->{'data'} = array(0=>$id_incident_type6++);}	
		if ($row['id_incident'] == 7) { $array_type7 ->{'name'}='Lỗi máy tính'; $array_type7 ->{'data'} = array(0=>$id_incident_type7++);}
		if ($row['id_incident'] == 8) { $array_type8 ->{'name'}='Do NM thao tác'; $array_type8 ->{'data'} = array(0=>$id_incident_type8++);}			
	}
	
	$tmp = array();
/*	array_push($tmp , $array_type1);
	array_push($tmp , $array_type2);
	array_push($tmp , $array_type3);
	array_push($tmp , $array_type4);
	array_push($tmp , $array_type5);
	array_push($tmp , $array_type6);
	array_push($tmp , $array_type7);
	array_push($tmp , $array_type8);
*/
	
	$data['sum_incident_type'] = get_array_type_incident();
	$data['sum_incident_cause'] = get_array_type_cause();	
	$data['sum_incident_type_pie'] = get_array_type_incident_pie();
	$data['sum_incident_cause_pie'] = get_array_type_cause_pie();
/*	
	$sum_type_incident = array(
		'id_incident_type1' => $id_incident_type1,
		'id_incident_type2' => $id_incident_type2,
		'id_incident_type3' => $id_incident_type3,
		'id_incident_type4' => $id_incident_type4,
		'id_incident_type5' => $id_incident_type5,
		'id_incident_type6' => $id_incident_type6,
		'id_incident_type7' => $id_incident_type7,
		'id_incident_type8' => $id_incident_type8		
	);
	
	$data ['sum_type_incident']= $sum_type_incident;
*/

	
	//array_push($data, $sum_type_incident);
	
	//$data2 = array();
	//for ($i=0; $i<sizeof($data); $i++){
	//	for ($j=0; $j<sizeof($data1); $j++){
	//		if ($data[$i]['ID']==$data1[$j]['id_incident_code']) {
				//echo $data[$i]['ID'].'    '.$data1[$j]['id_incident_code'];
	//			array_push($data2,$data1[$j]);		
	//		}
	//	}
	//	array_push($data[$i],$data2);
	//}
	
	
	//array_push($data, $data1);
	echo json_encode($data);

	//echo $time_elapsed_us = microtime(true) - $start;

	CLOSE_DB();
	unset($sql, $result);
          
?> 