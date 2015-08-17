<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";
require "../libs/custom_functions.php";

$month =  isset($_REQUEST['month'])? $_REQUEST['month'] : '';
$serial =  isset($_REQUEST['serial'])? $_REQUEST['serial'] : '';
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';
$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$subid =  (int)isset($_REQUEST['subid'])? $_REQUEST['subid'] : 0;

$serial = clean_text($serial);
$month = clean_text($month);
$token = clean_text($token);

/*
* check  token
*/
// $checktoken = checktoken($userid, $token);
// if (!$checktoken) die ('404');


$data = array();
$data['status']=404;	//có tìm thấy dữ liệu trong bảng confirm ko? có thì ko cho ghi - ko thì cho xác nhận ol - code: 200
$data['sourcedauky']='h1now';
$data['sourcecuoiky']='h1now';
$get_comfirm_data_available = 0;


$tmp = $month.'-01';
$et = strtotime($tmp)-300;

$datestring=$tmp.' first day of last month';
$dt=date_create($datestring);
$st = $dt->format('Y-m-d'); 

$lastmonthstr = explode('-', $st);
$lmstr = (int)$lastmonthstr[0].'-'.(int)$lastmonthstr[1]; 

$st = strtotime($st)-300;


  CONNECT_DB();
    mysql_query("SET NAMES utf8");

    /*
    * lấy thông tin investor
    */
    $sql = "SELECT substation_power.name_sub, power_company.name_pwc, investor.name_investor FROM substation_power
    		INNER JOIN power_company
    		ON substation_power.id_pwc = power_company.id_pwc
			INNER JOIN investor
    		ON substation_power.id_investor = investor.id_investor
			WHERE substation_power.id_sub = $subid";
	
	$result = mysql_query($sql) or die('10');

	$investorinfo = array();
 	while($row = mysql_fetch_array($result)){
		$investorinfo = array(
				'name_sub' => $row['name_sub'],
				'name_pwc' => $row['name_pwc'],
				'name_investor' => $row['name_investor']
			);
	}

	//echo '<pre>'; print_r($investorinfo); exit();

	$data['investorinfo'] = $investorinfo;
    //$data['dauky'] = null; 
    //$data['cuoiky'] = null;
    $data['type'] = 0;

    /*
    *	Khởi tạo giá trị confirm ban đầu, nếu tồn tại thì rewrite lại confirm
    */

    $confirminfo = array(
		'month' => '',
		'sub_confirm' => 0,
		'pc_confirm' => 0,
		'fullname_sub_confirm' => '', 
		'fullname_pc_confirm' => '', 
		'date_pc_confirm' => '',
		'date_confirm' => '',
		'edit_date_confirm' => '', 
		'edit_count' => 0
	);

    //tạm ẩn đi để xem nguồn h1 lấy ở đâu
    $data['confirminfo'] = $confirminfo;

    /*
    *	Lây các thông số unit, số sau dấu phẩy, hệ số nhân, hệ số tổn thất ĐZ của meter
    */

    $unitmeter=0; $countpoint = 0; $factor_meter=0; $lineloss_meter=0;

	$sql = 'SELECT `unit_meter`,`count_point_meter`,`factor_meter`,`level_meter`, `relation_meter`,`lineloss_meter`, `pconvert_meter` FROM `meter` WHERE `serial_meter` = "'.$serial.'" ';
	$result = mysql_query($sql) or die('0');
	
	$level_meter_char_plus='';	

 	while($row = mysql_fetch_array($result)){
	
		if ($row['level_meter']==0) {$level_meter_char_plus='Chính'; } else {$level_meter_char_plus='Phụ'; }
		
		$unitmeter=(int)$row['unit_meter'];
		$countpoint = (int)$row['count_point_meter'];
		$factor_meter=(double)$row['factor_meter'];
		$lineloss_meter = (double)$row['lineloss_meter'];
		$pconvert_meter = (float)$row['pconvert_meter'];
		$tmp = array(
				'unit_meter' => $unitmeter, 
				'count_point_meter' => $countpoint,
				'factor_meter' => $factor_meter, 
				'pconvert_meter' => $pconvert_meter, 
				'lineloss_meter' => $lineloss_meter,
				'level_meter_string_plus'=> $level_meter_char_plus,				
				'relation_meter' => $row['relation_meter'] 					
			);
		$data['meter'] = $tmp;
	}

	/*
	*	1. Tạo data đúng mẫu dataconfirm
	*	2. Kiểm tra đầu kỳ có (trong h1 confirm) ko. Có thì lấy luôn cho ra
	*	3. Đầu kỳ ko có (chưa chốt) thì lấy chốt cuối kỳ tháng trước
	*	4. Nếu cuối kỳ tháng trước ko có thì lấy online 
	*	5. Trường hợp công tơ ko online - lần đầu - thì ko có cả dữ liệu online -> phải bắt th này
	*	*********
	*	6. Lấy cuối kỳ - có confirm thì dùng luôn
	*	7. Không có trong confirm -> lấy online (ko có lấy từ tháng trước); 
	*	8. Set default whenever online value is not exist
	*	********
	*	Xác nhận của nhà máy là xác nhận chỉ số cuối
	*/
/*
	$sql = 'SELECT `dauky`, `cuoiky`, `type`, `pc_confirm`, `fullname_sub_confirm`, `fullname_pc_confirm`, `date_pc_confirm`, `date_confirm`, `edit_date_confirm`, `edit_count` 
			FROM `h1_confirm` 
			WHERE `meter_serial`="'.$serial.'" AND `month_confirm` ="'.$month.'" ORDER BY `ID` DESC LIMIT 0,1';

    $result = mysql_query($sql) or die('0');

    if (mysql_num_rows($result) > 0){

	 	while($row = mysql_fetch_array($result)){

			$data['dauky'] = $row['dauky']; 
			$data['cuoiky'] = $row['cuoiky'];
			$data['type'] = $row['type'];

			$confirminfo = array(
				'month' => $month,
				'sub_confirm' => 1,
				'pc_confirm' => (int)$row['pc_confirm'],
				'fullname_sub_confirm' => $row['fullname_sub_confirm'], 
				'fullname_pc_confirm' => $row['fullname_pc_confirm'], 
				'date_confirm' => $row['date_confirm'],
				'date_pc_confirm' => $row['date_pc_confirm'],
				'edit_date_confirm' => $row['edit_date_confirm'], 
				'edit_count' => (int)$row['edit_count']
			);

		} 

		$data['confirminfo'] = $confirminfo;
	}
*/

	/*
	*	Thay đổi lại - thêm phầm kiểm định
	*/
	
	$type = null;

	$sql = 'SELECT `type` 
			FROM `h1_confirm` 
			WHERE `meter_serial`="'.$serial.'" AND `month_confirm` ="'.$month.'" ORDER BY `ID` DESC LIMIT 0,1';

	$result = mysql_query($sql) or die('0');

	while($row = mysql_fetch_array($result)){

		$type = $row['type']; 
	
	} 

	if ($type == null) {die('404');}		//ko có dữ liệu

		else if ($type == 0) {
			
			$tmp = get_data_from_h1($month, $serial, $type);
			$data['data1'] = $tmp;
			$data['status'] = $tmp['status'];

			$data['data2'] = null;

		} else if ($type != 0) {
			
			$type = 1;
			$tmp = get_data_from_h1($month, $serial, $type);
			$data['data1'] = $tmp;
			$data['status'] = $tmp['status'];

			$type = 2;
			$tmp = get_data_from_h1($month, $serial, $type);
			$data['data2'] = $tmp;
			$data['status'] = $tmp['status'];

			$data['type'] = 1;								//	1/2 - trường hợp kiểm định

		}

	/*
	*	Lấy đầy đủ dữ liệu đầu kỳ cuối kỳ thì mới báo 200 - cho in
	*/
	// if ($data['dauky']!=null&&$data['dauky']!=''){
	// 	$data['dauky'] = json_decode($data['dauky']); 
	// }
	// if ($data['cuoiky']!=null&&$data['cuoiky']!=''){
	// 	$data['cuoiky'] = json_decode($data['cuoiky']);
	// 	$data['status']=200; 
	// }

	/*
	*	Lấy xong dữ liệu trong h1 confirm -> so sánh nếu ko có thì bắt đầu lấy kỳ trước hoặc online
	*/

	//echo '<pre>'; print_r($data); exit();	

	echo json_encode($data);

	CLOSE_DB();
	unset($sql, $result, $rs);
     
    function get_data_from_h1($month, $serial, $type){

    	$object = array('dauky' => null, 'cuoiky' => null);

    	$sql = 'SELECT `dauky`, `cuoiky`, `type`, `pc_confirm`, `fullname_sub_confirm`, `fullname_pc_confirm`, `date_pc_confirm`, `date_confirm`, `edit_date_confirm`, `edit_count` 
			FROM `h1_confirm` 
			WHERE `type` = '.$type.' AND `meter_serial`="'.$serial.'" AND `month_confirm` ="'.$month.'" ORDER BY `ID` DESC LIMIT 0,1';

	    $result = mysql_query($sql) or die('0');

	    if (mysql_num_rows($result) > 0){

		 	while($row = mysql_fetch_array($result)){

				$object['dauky'] = $row['dauky']; 
				$object['cuoiky'] = $row['cuoiky'];
				$object['type'] = $row['type'];

				$confirminfo = array(
					'month' => $month,
					'sub_confirm' => 1,
					'pc_confirm' => (int)$row['pc_confirm'],
					'fullname_sub_confirm' => $row['fullname_sub_confirm'], 
					'fullname_pc_confirm' => $row['fullname_pc_confirm'], 
					'date_confirm' => $row['date_confirm'],
					'date_pc_confirm' => $row['date_pc_confirm'],
					'edit_date_confirm' => $row['edit_date_confirm'], 
					'edit_count' => (int)$row['edit_count']
				);

			} 

			$object['confirminfo'] = $confirminfo;
		}

		if ($object['dauky']!=null&&$object['dauky']!=''){
			$object['dauky'] = json_decode($object['dauky']); 
		}
		if ($object['cuoiky']!=null&&$object['cuoiky']!=''){
			$object['cuoiky'] = json_decode($object['cuoiky']);
			$object['status']=200; 
		}

		return $object;

    }

?> 
