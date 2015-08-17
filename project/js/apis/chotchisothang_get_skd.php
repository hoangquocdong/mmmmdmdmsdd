

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
$data['type']=2;
$data['sourcedauky']='h1now';
$data['sourcecuoiky']='h1now';

$daytmp = $month.'-01';												//đầu tháng chọn in timespan format
$date = new DateTime($daytmp);
$date->modify('first day of next month');
$et = $date->getTimestamp()-300;


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
		'sub_confirm' => 0,
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

	$sql = 'SELECT `dauky`, `cuoiky`, `pc_confirm`, `sub_confirm`, `fullname_sub_confirm`, `fullname_pc_confirm`, `date_pc_confirm`, `date_confirm`, `edit_date_confirm`, `edit_count` 
			FROM `h1_confirm` 
			WHERE `meter_serial`="'.$serial.'" AND `month_confirm` ="'.$month.'" AND `type`=2 ORDER BY `ID` DESC LIMIT 0,1';
	
    $result = mysql_query($sql) or die('500');

    if (mysql_num_rows($result) > 0){

	 	while($row = mysql_fetch_array($result)){

			$data['dauky'] = $row['dauky']; 
			$data['cuoiky'] = $row['cuoiky'];
			
			$confirminfo = array(
				'month' => $month,
				'sub_confirm' => (int)$row['sub_confirm'],
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
		$data['status']=200;
	}

	/*
	*	Lấy xong dữ liệu trong h1 confirm -> so sánh nếu ko có thì bắt đầu lấy kỳ trước hoặc online
	*/

		

   	if ($data['dauky']!=null&&$data['dauky']!='') { 
   		$data['dauky'] = json_decode($data['dauky']);
   	} 

    if ($data['cuoiky']!=null&&$data['cuoiky']!='') { 
   		$data['cuoiky'] = json_decode($data['cuoiky']);
   	}   

   	if ($data['cuoiky']==null&&$data['cuoiky']=='') {
   		$data['cuoiky']=get_cuoiky_online($serial, $et, $unitmeter, $countpoint);
   	}


	echo json_encode($data);

	CLOSE_DB();
	unset($sql, $result, $rs, $data);
    
    function get_cuoiky_online($serial, $et, $unitmeter, $countpoint){
    	$sql =  'SELECT `import_kw`, `export_kw`, `q1`, `q2`, `q3`, `q4`, `rate1`, `rate2`, 
					`rate3`, `rate4`, `rate5`, `rate6`, `cd1`, `cd2`,`full_time`, `full_time_d`  
				FROM `history_value` 
	    		WHERE `full_time_d` > '.$et.' AND `serial_meter` = "'.$serial.'" 
	    		ORDER BY `full_time_d` ASC LIMIT 0,1';

	    $result = mysql_query($sql) or die('500');

	    //$tmp=array();
	    $tmp = new stdClass();
		while($row = mysql_fetch_array($result)){

			
				$tmp ->{'import_kw'} = getdecimalnumber($unitmeter,(float)$row['import_kw'],$countpoint);
				$tmp ->{'rate1'} = getdecimalnumber($unitmeter,(float)$row['rate1'],$countpoint);
				$tmp ->{'rate2'} = getdecimalnumber($unitmeter,(float)$row['rate2'],$countpoint);
				$tmp ->{'rate3'} = getdecimalnumber($unitmeter,(float)$row['rate3'],$countpoint);
				$tmp ->{'cd1'} = getdecimalnumber($unitmeter,(float)$row['cd1'],$countpoint);
				$tmp ->{'q1'} = getdecimalnumber($unitmeter,(float)$row['q1'],$countpoint);
				$tmp ->{'q2'} = getdecimalnumber($unitmeter,(float)$row['q2'],$countpoint);
					
				$tmp ->{'export_kw'} = getdecimalnumber($unitmeter,(float)$row['export_kw'],$countpoint);
				$tmp ->{'rate4'} = getdecimalnumber($unitmeter,(float)$row['rate4'],$countpoint);
				$tmp ->{'rate5'} = getdecimalnumber($unitmeter,(float)$row['rate5'],$countpoint);
				$tmp ->{'rate6'} = getdecimalnumber($unitmeter,(float)$row['rate6'],$countpoint);

				$tmp ->{'cd2'} = getdecimalnumber($unitmeter,(float)$row['cd2'],$countpoint);
				$tmp ->{'q3'} = getdecimalnumber($unitmeter,(float)$row['q3'],$countpoint);
				$tmp ->{'q4'} = getdecimalnumber($unitmeter,(float)$row['q4'],$countpoint);
				$tmp ->{'full_time'} = $row['full_time'];				
		}

		return $tmp;
    }

function getdecimalnumber($unitmeter, $number, $decimalcount){
		
    	if ($unitmeter == 1){
			$number = $number/1000;
		}
		$pointer = 1;	
		for ($i=1; $i<=$decimalcount; $i++){
			$pointer=$pointer*10;
		}		

		$result = $number*$pointer.''; 

		$result = (int)$result/$pointer; 

		//$result = ($number*$pointer)/$pointer;
		//$result = floor($number*$pointer)/$pointer;
		$result = number_format($result ,$decimalcount ,"," ,"." );
		return $result;
	} 
	
       
?> 

