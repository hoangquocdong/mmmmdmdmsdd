<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$start = microtime(true);

$today=date("d-m-Y");

$serial_meter =  isset($_REQUEST['serial'])? $_REQUEST['serial'] : '';

$st =  isset($_REQUEST['st'])? $_REQUEST['st'] : '';

$et =  isset($_REQUEST['et'])? $_REQUEST['et'] : '';

$default = 1;
if ($st==''){ $st=(int)(''.(strtotime($today)));} 
	else { $st = (int)(''.(strtotime($st))); $default = 0;}


if ($et==''){ $et = (int)$st+86400;} 
	else { $et = (int)(''.(strtotime($et))); }

//echo $st.$et; exit();

//echo $st.' -- '.$et; exit();

$serial_meter = clean_text($serial_meter);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");
	
	$data = array();
	
	function getdecimalnumber($unitmeter, $number, $decimalcount){
		if ($unitmeter == 1){
			$number = $number/1000;
		}
		$pointer = 1;	
		for ($i=1; $i<=$decimalcount; $i++){
			$pointer=$pointer*10;
		}		
		$result = floor($number*$pointer)/$pointer;
		$result = number_format($result ,$decimalcount ,"," ,"." );
		return $result.'';
	}
	function convertarray($data){
		$length = sizeof($data)-1;
        $newarray='';
        for ($i=0; $i<=$length; $i++){
        $newarray[$i]=$data[$length-$i];        
        }
        return $newarray;
    }

	$unitmeter=0; $countpoint = 0;
    /*
    *	Lây dư liệu bảng - chỉ số công tơ
    */
	$sql = 'SELECT `unit_meter`,`count_point_meter` FROM `meter` WHERE `serial_meter` = "'.$serial_meter.'" ';
	$result = mysql_query($sql) or die('0');
 	while($row = mysql_fetch_array($result)){
		$unitmeter=(int)$row['unit_meter'];
		$countpoint = (int)$row['count_point_meter'];
		$tmp = array(
				'unit_meter' => $unitmeter, 
				'count_point_meter' => $countpoint
			);
		$data['meter'] = $tmp;
	} 
	
	


    /*
    *	Lây dư liệu bảng - chỉ số công tơ
    */

    if ($default) {
		$sql ='SELECT `i_pha_a`, `i_pha_b`, `i_pha_c`, `u_pha_a`, `u_pha_b`, `u_pha_c`, `p_pha_a`, `p_pha_b`, 
				`p_pha_c`, `p_sum`, `ppk_pha_a`, `ppk_pha_b`, `ppk_pha_c`, `ppk_sum`, `pbk_pha_a`, `pbk_pha_b`, `pbk_pha_c`, 
				`pbk_sum`, `cosphi_a`, `cosphi_b`, `cosphi_c`, `cosphi_mean`, `f_pha_a`, `f_pha_b`, `f_pha_c`, `full_time`, `full_time_d`  
				FROM `instan_value` WHERE `serial_meter` = "'.$serial_meter.'" ORDER BY ID DESC LIMIT 0,350';
	} else {
		$sql ='SELECT `i_pha_a`, `i_pha_b`, `i_pha_c`, `u_pha_a`, `u_pha_b`, `u_pha_c`, `p_pha_a`, `p_pha_b`, 
				`p_pha_c`, `p_sum`, `ppk_pha_a`, `ppk_pha_b`, `ppk_pha_c`, `ppk_sum`, `pbk_pha_a`, `pbk_pha_b`, `pbk_pha_c`, 
				`pbk_sum`, `cosphi_a`, `cosphi_b`, `cosphi_c`, `cosphi_mean`, `f_pha_a`, `f_pha_b`, `f_pha_c`, `full_time`, `full_time_d`  
				FROM `instan_value` WHERE `full_time_d` >= '.$st.' AND  `full_time_d` <= '.$et.' AND `serial_meter` = "'.$serial_meter.'"';// ORDER BY ID DESC LIMIT 0,1';
	}

	

	$result = mysql_query($sql) or die('0');

	$data = array();
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

$donviu='(V)';
		$donvip='(kW)';
		$donvippk='(kVAr)';
		$donvipbk='(kVA)';		
		if ($row['u_pha_a']>1000) {
			$donviu='(kV)';
			$ua=getdecimalnumber(1,$row['u_pha_a'],2);
			$ub=getdecimalnumber(1,$row['u_pha_b'],2);
			$uc=getdecimalnumber(1,$row['u_pha_c'],2);			
		} else {
			$ua=getdecimalnumber(0,$row['u_pha_a'],2);
			$ub=getdecimalnumber(0,$row['u_pha_b'],2);
			$uc=getdecimalnumber(0,$row['u_pha_c'],2);
		
		}
		if ($row['p_pha_a']>1000) {
			$donvip='(MW)';
			$pa=getdecimalnumber(1,$row['p_pha_a'],2);
			$pb=getdecimalnumber(1,$row['p_pha_b'],2);
			$pc=getdecimalnumber(1,$row['p_pha_c'],2);
			$ps=getdecimalnumber(1,$row['p_sum'],2);			
		} else {
			$pa=getdecimalnumber(0,$row['p_pha_a'],2);
			$pb=getdecimalnumber(0,$row['p_pha_b'],2);
			$pc=getdecimalnumber(0,$row['p_pha_c'],2);
			$ps=getdecimalnumber(0,$row['p_sum'],2);
		}
		if ($row['ppk_pha_a']>1000) {
			$donvippk='(MVAr)';
			$ppka=getdecimalnumber(1,$row['ppk_pha_a'],2);
			$ppkb=getdecimalnumber(1,$row['ppk_pha_b'],2);
			$ppkc=getdecimalnumber(1,$row['ppk_pha_c'],2);
			$ppks=getdecimalnumber(1,$row['ppk_sum'],2);			
		} else {
			$ppka=getdecimalnumber(0,$row['ppk_pha_a'],2);
			$ppkb=getdecimalnumber(0,$row['ppk_pha_b'],2);
			$ppkc=getdecimalnumber(0,$row['ppk_pha_c'],2);
			$ppks=getdecimalnumber(0,$row['ppk_sum'],2);
		}
		if ($row['pbk_pha_a']>1000) {
			$donvipbk='(MVA)';
			$pbka=getdecimalnumber(1,$row['pbk_pha_a'],2);
			$pbkb=getdecimalnumber(1,$row['pbk_pha_b'],2);
			$pbkc=getdecimalnumber(1,$row['pbk_pha_c'],2);
			$pbks=getdecimalnumber(1,$row['pbk_sum'],2);			
		} else {
			$pbka=getdecimalnumber(0,$row['pbk_pha_a'],2);
			$pbkb=getdecimalnumber(0,$row['pbk_pha_b'],2);
			$pbkc=getdecimalnumber(0,$row['pbk_pha_c'],2);
			$pbks=getdecimalnumber(0,$row['pbk_sum'],2);
		}		
		
		$tmp = array(
			'u_pha_a' => $ua, 
			'u_pha_b' => $ub, 
			'u_pha_c' => $uc, 
			'i_pha_a' => getdecimalnumber(0,$row['i_pha_a'],2),
			'i_pha_b' => getdecimalnumber(0,$row['i_pha_b'],2),
			'i_pha_c' => getdecimalnumber(0,$row['i_pha_c'],2),
			'f_pha_a' => getdecimalnumber(0,$row['f_pha_a'],2),
			'f_pha_b' => getdecimalnumber(0,$row['f_pha_b'],2), 
			'f_pha_c' => getdecimalnumber(0,$row['f_pha_c'],2),
			'cosphi_a' => getdecimalnumber(0,$row['cosphi_a'],2),
			'cosphi_b' => getdecimalnumber(0,$row['cosphi_b'],2), 
			'cosphi_c' => getdecimalnumber(0,$row['cosphi_c'],2),
			'p_pha_a' => $pa,
			'p_pha_b' => $pb,
			'p_pha_c' => $pc, 
			'ppk_pha_a' => $ppka,
			'ppk_pha_b' => $ppkb,
			'ppk_pha_c' => $ppkc, 
			'pbk_pha_a' => $pbka,
			'pbk_pha_b' => $pbkb, 
			'pbk_pha_c' => $pbkc, 
			'p_sum' => $ps,
			'ppk_sum' => $ppks, 
			'pbk_sum' => $pbks,   
			'cosphi_mean' => getdecimalnumber(0,$row['cosphi_mean'],2),  
			'full_time' => $row['full_time_h'],
			'unitu' => $donviu,
			'unitp' => $donvip,
			'unitppk' => $donvippk,
			'unitpbk' => $donvipbk
				
			);
		array_push($data,$tmp);
	}

	$du = array();
	$di = array();
	$df = array();
	if ($default) {	
		$di1=convertarray($di1);$di2=convertarray($di2);$di3=convertarray($di3);
		$du1=convertarray($du1);$du2=convertarray($du2);$du3=convertarray($du3);
		$df1=convertarray($df1);$df2=convertarray($df2);$df3=convertarray($df3);
	}
	array_push($du, $du1, $du2, $du3);
	array_push($di, $di1, $di2, $di3);
	array_push($df, $df1, $df2, $df3);

	$rs = array(
		'dienap' => $du,
		'dongdien' => $di,
		'tanso' => $df,
		'dulieu' => $data
		);

	//echo $time_elapsed_us = microtime(true) - $start;

	echo json_encode($rs);
	//echo json_encode($tmp2);
	CLOSE_DB();

	$vars = array_keys(get_defined_vars());
	for ($i = 0; $i < sizeOf($vars); $i++) {
	    unset($$vars[$i]);
	}
	unset($vars,$i);
          
?> 
