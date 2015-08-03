
<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$serial_meter =  isset($_REQUEST['serial'])? $_REQUEST['serial'] : '';

$serial_meter = clean_text($serial_meter);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");

    //$sql='SELECT `serial_meter`, `name_meter`, `level_meter`, `relation_meter`, `online_status`  FROM `meter` WHERE `id_sub` = '.$id_sub.' ORDER BY `relation_meter` DESC';

    $sql = 'SELECT MAX(ID), `import_kw`, `export_kw`, `q1`, `q2`, `q3`, `q4`, `rate1`, `rate2`, `rate3`, `rate4`, `rate5`, `rate6`, `cd1`, `cd2`, `full_time_h`, `full_time_d` FROM `current_value` WHERE `serial_meter` = "'.$serial_meter.'"';
    $result = mysql_query($sql) or die('0');

	$data = array();
	while($row = mysql_fetch_array($result)){
		$tmp = array(
				'import_kw' => $row['import_kw'], 
				'export_kw' => $row['export_kw'], 
				'q1' => $row['q1'], 
				'q2' => $row['q2'],
				'q3' => $row['q3'], 
				'q4' => $row['q4'], 
				'rate1' => $row['rate1'], 
				'rate2' => $row['rate2'],
				'rate3' => $row['rate3'], 
				'rate4' => $row['rate4'], 
				'rate5' => $row['rate5'], 
				'rate6' => $row['rate6'],
				'cd1' => $row['cd1'], 
				'cd2' => $row['cd2'], 
				'full_time_h' => $row['full_time_h'], 
				'full_time_d' => $row['full_time_d']
			);
		array_push($data, $tmp);
	}

	$sql ='SELECT MAX(ID), `i_pha_a`, `i_pha_b`, `i_pha_c`, `u_pha_a`, `u_pha_b`, `u_pha_c`, `p_pha_a`, `p_pha_b`, `p_pha_c`, `p_sum`, `ppk_pha_a`, `ppk_pha_b`, `ppk_pha_c`, `ppk_sum`, `pbk_pha_a`, `pbk_pha_b`, `pbk_pha_c`, `pbk_sum`, `cosphi_a`, `cosphi_b`, `cosphi_c`, `cosphi_mean`, `f_pha_a`, `f_pha_b`, `f_pha_c`, `full_time_h`  FROM `instan_value` WHERE `serial_meter` = "'.$serial_meter.'"';
	$result = mysql_query($sql) or die('0');

	while($row = mysql_fetch_array($result)){
		$tmp = array(
				'i_pha_a' => $row['i_pha_a'], 
				'i_pha_b' => $row['i_pha_b'], 
				'i_pha_c' => $row['i_pha_c'], 
				'u_pha_a' => $row['u_pha_a'],
				'u_pha_b' => $row['u_pha_b'], 
				'u_pha_c' => $row['u_pha_c'], 
				'p_pha_a' => $row['p_pha_a'], 
				'p_pha_b' => $row['p_pha_b'],
				'p_pha_c' => $row['p_pha_c'], 
				'p_sum' => $row['p_sum'], 
				'ppk_pha_a' => $row['ppk_pha_a'], 
				'ppk_pha_b' => $row['ppk_pha_b'],
				'ppk_pha_c' => $row['ppk_pha_c'], 
				'ppk_sum' => $row['ppk_sum'], 
				'pbk_pha_a' => $row['pbk_pha_a'],
				'pbk_pha_b' => $row['pbk_pha_b'], 
				'pbk_pha_c' => $row['pbk_pha_c'], 
				'pbk_sum' => $row['pbk_sum'], 
				'cosphi_a' => $row['cosphi_a'],
				'cosphi_b' => $row['cosphi_b'], 
				'cosphi_c' => $row['cosphi_c'], 
				'cosphi_mean' => $row['cosphi_mean'], 
				'f_pha_a' => $row['f_pha_a'],
				'f_pha_b' => $row['f_pha_b'], 
				'f_pha_c' => $row['f_pha_c'],
				'full_time_h' => $row['full_time_h']
			);
		array_push($data, $tmp);
	}

	echo json_encode($data);

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 



