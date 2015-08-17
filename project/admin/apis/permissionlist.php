<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";
require "libs/custom_functions.php";

//$id_sub =  isset($_REQUEST['id_sub'])? $_REQUEST['id_sub'] : 0;


/*
* thiếu user id - phân quyền
*/

  CONNECT_DB();
    mysql_query("SET NAMES utf8");

    $sql=' SELECT `id_pwc`, `name_pwc`, `status` FROM `power_company` WHERE 1 ORDER BY `id_orderby` ASC'; 

	$result = mysql_query($sql) or die('0');

	$data = array();
	while($row = mysql_fetch_array($result)){
		$tmp = array(
				'id_pwc' => $row['id_pwc'], 
				'name_pwc' => $row['name_pwc'],
				'status' => $row['status']
			);
		array_push($data, $tmp);
	}

	$online = 0;
	$offline = 0;
	foreach($data as $key => $value) {
	    //echo $value['id_pwc'];
		$sub = array();

	    $sql=' SELECT `id_sub`, `name_sub`, `connection_sub`, `status` FROM `substation_power` WHERE `id_pwc` = '.$value["id_pwc"];//.' AND `status` = 1'; 
	    $result = mysql_query($sql) or die('0');

		while($row = mysql_fetch_array($result)){
			$tmp = array(
					'id_sub' => $row['id_sub'], 
					'name_sub' => $row['name_sub'],
					'online' => $row['connection_sub'],
					'status' => $row['status']
				);
			array_push($sub, $tmp);
			if ( $row['connection_sub'] == 1) {
				$online++;
			} else {
				$offline++;
			}
		}

		$ss = $sub;
		$data[$key]['sub'] = $ss;
	}

	echo json_encode($data);

	/*
		chưa xử lý cache menu left
	*/

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 