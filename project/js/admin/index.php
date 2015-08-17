<?
session_start();



require "libs/config.php";
require "libs/class_Template.php";
require "libs/common_functions.php";
require "libs/db_functions.php";
require "libs/custom_functions.php";



global $_content, $_menu, $_modun, $_htmlpath, $userid, $token, $crpage, $crmenu, $crpcid, $crsubid, $crmeter, $crnamesub;


$userid = 0;$token = 0;
if (isset($_SESSION['token'])) {$token = $_SESSION['token'];}
if (isset($_SESSION['id'])) {$userid = $_SESSION['id'];}
$page =  (isset($_REQUEST['page'])) ? $_REQUEST['page']: 'login';

	CONNECT_DB();
	mysql_query("SET NAMES utf8");

	$returnvalue = '';
	
	$sql = 'SELECT `currentpos` FROM `user` 
				WHERE `token` = "'.$token.'" AND `ID` = '.$userid.' AND `flag_change` = 0';
    $result = mysql_query($sql) or die('0');
    
    while($rsl=mysql_fetch_array($result)){
        $returnvalue = $rsl['currentpos'];
    }

	$crposarr = explode(',', $returnvalue);

	//echo '<pre>';
	//print_r($crposarr);
	$crpage=$crmenu=$crpcid=$crsubid=$crmeter=$crnamesub = '1111';
	if (isset($crposarr[0])){ $crpage = $crposarr[0];} else {$crpage = 'qldx';}
	if (isset($crposarr[1])){ $crmenu = $crposarr[1];} else {$crmenu = 'dx1';}
	if (isset($crposarr[2])){ $crpcid = (int)$crposarr[2];} else {$crpcid = 0;}
	if (isset($crposarr[3])){ $crsubid = (int)$crposarr[3];} else {$crsubid = 0;}
	if (isset($crposarr[4])){ $crmeter = $crposarr[4];} else {$crmeter = '';}
	if (isset($crposarr[5])){ $crnamesub = $crposarr[5];} else {$crnamesub = '';}

    //echo ($crpage.$crmenu.$crpcid.$crsubid.$crmeter.$crnamesub);

 //echo $crpage = getcrvalue($userid, $token, 'crpage');
 // $crmenu = getcrvalue($userid, $token, 'crmenu');
 // $crpcid = getcrvalue($userid, $token, 'crpcid');
 // $crsubid = getcrvalue($userid, $token, 'crsubid');
 // $crmeter = getcrvalue($userid, $token, 'crmeter');
 // $crnamesub = getcrvalue($userid, $token, 'crnamesub');

 //  die();
if($page==''||$page==null){$page= 'login';}

	switch ($page){
		case 'login':
		loadpage($page);
		break;
		case 'qldx':
		loadpage($page);
		break;
		case 'tkvh':
		loadpage($page);
		break;
		case 'csdl':
		loadpage($page);
		break;		
		case 'qlnm':
		loadpage($page);
		break;
		case 'doxa':
		loadpage($page);
		break;
		case 'qlhd':
		loadpage($page);
		break;
		case 'user':
		loadpage($page);
		break;
		default:
			$_content = '<h3 style="color:red">The page '.$page.' does not exist!</h3>';
		break;
	}
echo $_content;
?>