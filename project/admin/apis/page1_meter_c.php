<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$start = microtime(true);

$serial_meter =  isset($_REQUEST['serial'])? $_REQUEST['serial'] : '';

//$serial_meter = clean_text($serial_meter);

$serial_meter = '97815743';
  CONNECT_DB();
    mysql_query("SET NAMES utf8");


    /*
    *	Lây dư liệu bảng - chỉ số công tơ
    */
    $sql = 'SELECT `import_kw`, `export_kw`, `q1`, `q2`, `q3`, `q4`, `rate1`, `rate2`, `rate3`, 
    		`rate4`, `rate5`, `rate6`, `cd1`, `cd2`, `full_time_h`, `full_time_d` FROM `current_value` 
    		WHERE `serial_meter` = "'.$serial_meter.'" ORDER BY ID LIMIT 0,1';

    
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
		$data['chiso'] = $tmp;
	}

	/*
	$sql ='SELECT MAX(ID), `i_pha_a`, `i_pha_b`, `i_pha_c`, `u_pha_a`, `u_pha_b`, `u_pha_c`, `p_pha_a`, `p_pha_b`, 
				`p_pha_c`, `p_sum`, `ppk_pha_a`, `ppk_pha_b`, `ppk_pha_c`, `ppk_sum`, `pbk_pha_a`, `pbk_pha_b`, `pbk_pha_c`, 
				`pbk_sum`, `cosphi_a`, `cosphi_b`, `cosphi_c`, `cosphi_mean`, `f_pha_a`, `f_pha_b`, `f_pha_c`, `full_time_h`  
				FROM `instan_value` WHERE `serial_meter` = "'.$serial_meter.'"';
				*/
	$sql ='SELECT `i_pha_a`, `i_pha_b`, `i_pha_c`, `u_pha_a`, `u_pha_b`, `u_pha_c`, `p_pha_a`, `p_pha_b`, 
				`p_pha_c`, `p_sum`, `ppk_pha_a`, `ppk_pha_b`, `ppk_pha_c`, `ppk_sum`, `pbk_pha_a`, `pbk_pha_b`, `pbk_pha_c`, 
				`pbk_sum`, `cosphi_a`, `cosphi_b`, `cosphi_c`, `cosphi_mean`, `f_pha_a`, `f_pha_b`, `f_pha_c`, `full_time_h`  
				FROM `instan_value` WHERE `serial_meter` = "'.$serial_meter.'" ORDER BY ID LIMIT 0,1';

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
		$data['thongso'] = $tmp;
	}

	/*
    *	Hêt Lây dư liệu bảng - chỉ số công tơ
    */

    $sql = 'SELECT `u_pha_a`, `u_pha_b`, `u_pha_c`, `i_pha_a`, `i_pha_b`, `i_pha_c`, 
    		`f_pha_a`, `f_pha_b`, `f_pha_c`, `full_time_d` FROM `instan_value` 
    		WHERE `serial_meter`= "'.$serial_meter.'" ORDER BY ID DESC LIMIT 0,50';
    //echo $sql;
    $result = mysql_query($sql) or die('0');

    //$data = array();
	$du1 = array();
	$du2 = array();
	$du3 = array();

	$di1 = array();
	$di2 = array();
	$di3 = array();

	$df1 = array();
	$df2 = array();
	$df3 = array();

	while($row = mysql_fetch_array($result)){

		$fulltime = (int)$row['full_time_d']*1000;

		array_push($di1, array($fulltime,(float)$row['i_pha_a']));
        array_push($di2, array($fulltime,(float)$row['i_pha_b']));
        array_push($di3, array($fulltime,(float)$row['i_pha_c']));

        array_push($du1, array($fulltime,(float)$row['u_pha_a']));
        array_push($du2, array($fulltime,(float)$row['u_pha_b']));
        array_push($du3, array($fulltime,(float)$row['u_pha_c']));

        array_push($df1, array($fulltime,(float)$row['f_pha_a']));
        array_push($df2, array($fulltime,(float)$row['f_pha_b']));
        array_push($df3, array($fulltime,(float)$row['f_pha_c']));

	}

	$du = array();
	$di = array();
	$df = array();

	array_push($du, $du1, $du2, $du3);
	array_push($di, $du1, $du2, $du3);
	array_push($df, $du1, $du2, $du3);

	$data['dienap'] = $du;
	$data['dongdien'] = $di;
	$data['tanso'] = $df;


	//echo $time_elapsed_us = microtime(true) - $start;

	echo json_encode($data);
	//echo json_encode($tmp2);
	CLOSE_DB();

	$vars = array_keys(get_defined_vars());
	for ($i = 0; $i < sizeOf($vars); $i++) {
	    unset($$vars[$i]);
	}
	unset($vars,$i);
          
?> 
