

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


$serial = clean_text($serial);
$month = clean_text($month);
$token = clean_text($token);

/*
* chưa có token
*/

$data = array();
$data['status']=404;	//có tìm thấy dữ liệu trong bảng confirm ko? có thì ko cho ghi - ko thì cho xác nhận ol - code: 200
$data['type']=0;
$data['sourcedauky']='h1now';
$data['sourcecuoiky']='h1now';

  CONNECT_DB();
    mysql_query("SET NAMES utf8");

    $checktoken = checktoken($userid, $token);

    if (!$checktoken) die ('404');

    $data['dauky'] = null; 
    $data['cuoiky'] = null;

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

	$sql = 'SELECT `unit_meter`,`count_point_meter`,`level_meter`, `relation_meter`,`factor_meter`,`lineloss_meter`, `pconvert_meter` FROM `meter` WHERE `serial_meter` = "'.$serial.'" ';
	$result = mysql_query($sql) or die('500');
	
	$level_meter_char='';
	$level_meter_char_plus='';
 	while($row = mysql_fetch_array($result)){
		if ($row['level_meter']==0) {$level_meter_char='C'; $level_meter_char_plus='Chính'; } else {$level_meter_char='P'; $level_meter_char_plus='Phụ'; }
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
				'level_meter_string'=> $level_meter_char,
				'level_meter_string_plus'=> $level_meter_char_plus,				
				'relation_meter' => $row['relation_meter'] 				
			);
		$data['meter'] = $tmp;
	}


	/*
	*	Chỉ get gt cuối kỳ của type = 1 
	*	Nếu ko có thì status == 201 - not found
	*	500 - internal error
	*/

	$sql = 'SELECT `dauky`, `cuoiky`, `pc_confirm`, `fullname_sub_confirm`, `fullname_pc_confirm`, `date_pc_confirm`, `date_confirm`, `edit_date_confirm`, `edit_count` 
			FROM `h1_confirm` 
			WHERE `meter_serial`="'.$serial.'" AND `month_confirm` ="'.$month.'" AND `type`=2 ORDER BY `ID` DESC LIMIT 0,1';
	
    $result = mysql_query($sql) or die('500');

    if (mysql_num_rows($result) > 0){

	 	while($row = mysql_fetch_array($result)){

			$data['dauky'] = $row['dauky']; 
			$data['cuoiky'] = $row['cuoiky'];
			
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

		//$data['dauky'] = json_decode($data['dauky']);
		$data['type']=2;
		$data['confirminfo'] = $confirminfo;
		$data['status']=200;
	}

	/*
	*	Lấy xong dữ liệu trong h1 confirm -> so sánh nếu ko có thì bắt đầu lấy kỳ trước hoặc online
	*/

		

   	if ($data['dauky']!=null&&$data['dauky']!='') { 
   		$get_comfirm_data_available = 1; 
   		$data['dauky'] = json_decode($data['dauky']);

   	} 

    if ($data['cuoiky']!=null&&$data['cuoiky']!='') { 
   		$get_comfirm_data_available = 1; 
   		$data['cuoiky'] = json_decode($data['cuoiky']);

   	}   


	echo json_encode($data);

	CLOSE_DB();
	unset($sql, $result, $rs, $data);
    
       
?> 

