<?
	/*
		*Lấy dữ liệu nhét vào bảng
	*/	
	
	//session_start();
	if (!$_SESSION['login']) header('Location: index.php?page=login');//ko cho vào trực tiếp, khi chưa đăng nhập thì tự động chuyển về trang đăng nhập	
	//$_SESSION['page1']='home';

	// $menu =  isset($_REQUEST['menu'])? $_REQUEST['menu'] : '-';
	// $sub =  isset($_REQUEST['sub'])? $_REQUEST['sub'] : 0;
	// $meter =  isset($_REQUEST['meter'])? $_REQUEST['meter'] : '';
	
	// $menu = clean_text($menu);
	// $sub = (int)($sub);
	// $meter = clean_text($meter);

	CONNECT_DB();

	mysql_query("SET NAMES utf8");
	
	//$userid = 0;$token = 0;
	//if (isset($_SESSION['token'])) {$token = $_SESSION['token'];}
	//if (isset($_SESSION['id'])) {$userid = $_SESSION['id'];}

	global $_htmlpath, $userid, $token, $crpage, $crmenu, $crpcid, $crsubid, $crmeter, $crnamesub, $crmonth;
	
	$_htmlpath = $page_template_path;

	$checktoken = checktoken($userid, $token);

	$modunheader = load_modun('header');
	
	//$menuleft = menuleftget($userid, $token);

	/*
		LOAD PAGE
	*/
	/*
		KHU VỰC PHẢI THAY ĐỔI - KHỞI TẠO - KHỞI TẠO CÁC BIẾN CẦN DÙNG
		khai báo các biến cần truyền cho pages như title, list, nội dung các modun khác...
	*/
	global $_content;
	$page_tile = 'EVN Monitoring Program Homepage!';       			//Title của trang đó

	/*
		KHU VỰC KHÔNG THAY ĐỔI - TỰ ĐỘNG - PHẦN TỰ ĐỘNG
		gọi template ra - phần này tự động sử dụng các biến truyền vào.
	*/
	$cctpl = new Template($page_template_path.'/default.html');

	
	/*
		KHU VỰC PHẢI THAY ĐỔI THEO CÁC BIẾN ĐÃ KHỞI TẠO/ KHAI BÁO
		Truyền các biến vào nội dung page
	*/
	$cctpl -> set('modunheader',$modunheader);
	$cctpl -> set('checktoken',$checktoken);
	$cctpl -> set('tile',$page_tile);
	//$cctpl -> set('menuleft',$menuleft);
	$cctpl -> set('page_template_path',$page_template_path);

	//echo $page_content = $cctpl->fetch();		//echo tại file include
	$_content = $cctpl->fetch();				//echo tại file index.php
?>

