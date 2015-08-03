<?php
/*
	LOAD MODUN
	$newtopic=load_modun('newtopic');
*/
	global $_htmlpath, $userid, $token, $crpage, $crmenu, $crpcid, $crsubid, $crmeter, $crnamesub, $crmonth;

	$checktoken = checktoken($userid, $token);

	$menuleft = menuleftget($userid, $token);

	//$menu =  isset($_REQUEST['menu'])? $_REQUEST['menu'] : $crmenu;

	$mtpl = new Template($modun_template_path.'/default.html');

	//$mtpl -> set('menu',$menu);
	$mtpl -> set('menuleft',$menuleft);
	$mtpl -> set('checktoken',$checktoken);
	$mtpl -> set('htmlpath',$_htmlpath);
	$mtpl -> set('userid',$userid);
	$mtpl -> set('token',$token);
	$mtpl -> set('crpage',$crpage);
	$mtpl -> set('crmenu',$crmenu);
	$mtpl -> set('crpcid',$crpcid);
	$mtpl -> set('crsubid',$crsubid);
	$mtpl -> set('crmeter',$crmeter);
	$mtpl -> set('crnamesub',$crnamesub);
	$mtpl -> set('crmonth',$crmonth);	
	$mtpl -> set('path',$modun_template_path);
	$modun_content = $mtpl->fetch();
?>
