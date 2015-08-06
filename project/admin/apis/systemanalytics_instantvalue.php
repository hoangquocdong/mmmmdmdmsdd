<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$info =  isset($_REQUEST['info'])? $_REQUEST['info'] : '';
$time = time();
$deltatime_instan = 15*60;
$instan_condition = $time - $deltatime_instan;

$deltatime_current = 60*60;
$current_condition = $time - $deltatime_current;

$deltatime_profile = 60*60;
$profile_condition = $time - $deltatime_profile;

$deltatime_history = 60*60;
$history_condition = $time - $deltatime_history;

  CONNECT_DB();

    mysql_query("SET NAMES utf8");

    $returnarray = array(
		'status' => 500,
		'content'=>	'Update info fail!'
	);

    $sql = 'SELECT  lastactivities.serial_meter, lastactivities.instan_value, 
            lastactivities.id_sub, lastactivities.id_pwc, power_company.name_pwc, substation_power.name_sub
            FROM `lastactivities` 
            LEFT JOIN `substation_power` ON lastactivities.id_sub = substation_power.id_sub
            LEFT JOIN `power_company` ON  lastactivities.id_pwc = power_company.id_pwc
            WHERE lastactivities.instan_value < '.$instan_condition;

	$result = mysql_query($sql) or die(json_encode($returnarray));

    $instan = array();
    while($rs=mysql_fetch_array($result)){
        $tmp = array(
                'serial_meter' => $rs['serial_meter'],
                'instan_value' => $rs['instan_value'],
                'last_update' => date('d-m-Y H:i:s', $rs['instan_value']),
                'id_sub' => $rs['id_sub'],
                'id_pwc' => $rs['id_pwc'],
                'name_pwc' => $rs['name_pwc'],
                'name_sub' => $rs['name_sub']
            );

        array_push($instan, $tmp);
    }


    $sql = 'SELECT  lastactivities.serial_meter, lastactivities.current_value, 
            lastactivities.id_sub, lastactivities.id_pwc, power_company.name_pwc, substation_power.name_sub
            FROM `lastactivities` 
            LEFT JOIN `substation_power` ON lastactivities.id_sub = substation_power.id_sub
            LEFT JOIN `power_company` ON  lastactivities.id_pwc = power_company.id_pwc
            WHERE lastactivities.current_value < '.$current_condition;

    $result = mysql_query($sql) or die(json_encode($returnarray));

    $current = array();
    while($rs=mysql_fetch_array($result)){
        $tmp = array(
                'serial_meter' => $rs['serial_meter'],
                'current_value' => $rs['current_value'],
                'last_update' => date('d-m-Y H:i:s', $rs['current_value']),
                'id_sub' => $rs['id_sub'],
                'id_pwc' => $rs['id_pwc'],
                'name_pwc' => $rs['name_pwc'],
                'name_sub' => $rs['name_sub']
            );

        array_push($current, $tmp);
    }


    $sql = 'SELECT  lastactivities.serial_meter, lastactivities.profile_value, 
            lastactivities.id_sub, lastactivities.id_pwc, power_company.name_pwc, substation_power.name_sub
            FROM `lastactivities` 
            LEFT JOIN `substation_power` ON lastactivities.id_sub = substation_power.id_sub
            LEFT JOIN `power_company` ON  lastactivities.id_pwc = power_company.id_pwc
            WHERE lastactivities.profile_value < '.$profile_condition;

    $result = mysql_query($sql) or die(json_encode($returnarray));

    $profile = array();
    while($rs=mysql_fetch_array($result)){
        $tmp = array(
                'serial_meter' => $rs['serial_meter'],
                'profile_value' => $rs['profile_value'],
                'last_update' => date('d-m-Y H:i:s', $rs['profile_value']),
                'id_sub' => $rs['id_sub'],
                'id_pwc' => $rs['id_pwc'],
                'name_pwc' => $rs['name_pwc'],
                'name_sub' => $rs['name_sub']
            );

        array_push($profile, $tmp);
    }

    $sql = 'SELECT  lastactivities.serial_meter, lastactivities.history_value, 
            lastactivities.id_sub, lastactivities.id_pwc, power_company.name_pwc, substation_power.name_sub
            FROM `lastactivities` 
            LEFT JOIN `substation_power` ON lastactivities.id_sub = substation_power.id_sub
            LEFT JOIN `power_company` ON  lastactivities.id_pwc = power_company.id_pwc
            WHERE lastactivities.history_value < '.$history_condition;

    $result = mysql_query($sql) or die(json_encode($returnarray));

    $history = array();
    while($rs=mysql_fetch_array($result)){
        $tmp = array(
                'serial_meter' => $rs['serial_meter'],
                'history_value' => $rs['history_value'],
                'last_update' => date('d-m-Y H:i:s', $rs['history_value']),
                'id_sub' => $rs['id_sub'],
                'id_pwc' => $rs['id_pwc'],
                'name_pwc' => $rs['name_pwc'],
                'name_sub' => $rs['name_sub']
            );

        array_push($history, $tmp);
    }


    /*
    * processing ykienphanhoi
    */
    $sql = 'SELECT `ID`, `userid`, `fullname`, `email`, `phone`, `content`, `pcid`, `read_status` , `time` 
            FROM `ykienphanhoi` 
            WHERE 1 ORDER BY `ID` DESC LIMIT 0,10';
	
    $result = mysql_query($sql) or die(json_encode($returnarray));

    $ykienphanhoi = array();
    while($rs=mysql_fetch_array($result)){
        $tmp = array(
                'ID' => $rs['ID'],
                'userid' => $rs['userid'],
                'time' => date('d-m-Y H:i:s', $rs['time']),
                'fullname' => $rs['fullname'],
                'email' => $rs['email'],
                'phone' => $rs['phone'],
                'content' => $rs['content'],
                'pcid' => $rs['pcid'],
                'read_status' => $rs['read_status']
            );

        array_push($ykienphanhoi, $tmp);
    }
    
    /*
    * processing H1
    */
    $m = (int)date('Y').'-'.(int)date("m", strtotime("-1 months")); 
    //echo $m;
    $sql =  'SELECT `meter_serial` FROM `h1_confirm` 
            WHERE (`type` = 0 OR `type` = 2) AND `sub_confirm` = 1 AND `month_confirm` = "'.$m.'"';

    $result = mysql_query($sql) or die(json_encode($returnarray));

    $h1confirmed = array();
    while($rs=mysql_fetch_array($result)){
        $h1confirmed[$rs['meter_serial']] = $rs['meter_serial'];
    }        

    
    $sql = 'SELECT  `serial_meter` FROM `meter` WHERE 1';
    $result = mysql_query($sql) or die(json_encode($returnarray));
    $allmeter = array();
    while($rs=mysql_fetch_array($result)){
        $allmeter[$rs['serial_meter']] = $rs['serial_meter'];
    } 

    $h1_not_confirmed = array();

    foreach ($allmeter as $key => $value) {
        if (!array_key_exists($key,$h1confirmed)) {
            $tmp = array(
                    'serial_meter' => $key.'',
                    'month' => $m
                );
            array_push($h1_not_confirmed, $tmp);
        }
    }

    /*
    * end of processing H1
    */

    $returnarray = array(
		'status' => 200,
		'content'=>	'Update info succesfully!',
        'currenttime' => $time,
        'instanc_timewarning' => $instan_condition,
        'current_timewarning' => $current_condition,
        'profile_timewarning' => $profile_condition,
        'history_timewarning' => $history_condition
	);
    $returnarray['datainstan'] = $instan;
    $returnarray['datacurrent'] = $current;
    $returnarray['dataprofile'] = $profile;
    $returnarray['datahistory'] = $history;
    $returnarray['dataykien'] = $ykienphanhoi;
    $returnarray['datayh1confirm'] = $h1_not_confirmed;

	echo json_encode($returnarray);	

	CLOSE_DB();
	unset($sql, $result);
          
?> 