<?php
//Check if exist special characters donghq
function isContainSpecialChar($string){
  if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string)){
    // one or more of the 'special characters' found in $string
    return true;
  } else {
    return false;
  }
}

function is_JSON($string) {

  json_decode($string);
  $isValid = json_last_error() === JSON_ERROR_NONE;
 
  return $isValid;

    //$data = json_decode($string);
    //return (json_last_error() == JSON_ERROR_NONE) ? ($return_data ? $data : TRUE) : FALSE;
}

//Get a GET vairable, if the variable is not present, take the default value
function GET_value($key,$default_value='')
{
   $value=isset($_GET[$key])? $_GET[$key] : $default_value;
   if (is_string($value))$value=trim($value);
   return $value;

}

// Get a POST vairable, if the variable is not present, take the default value
function POST_value($key,$default_value='')
{
   $value=isset($_POST[$key])? $_POST[$key] : $default_value;
   if (is_string($value))$value=trim($value);
   return $value;
}

//Get a COOKIE vairable, if the variable is not present, take the default value
function COOKIE_value($key,$default_value='')
{
   $value=isset($_COOKIE[$key])? $_COOKIE[$key] : $default_value;
   if (is_string($value))$value=trim($value);
   return $value;
}

//Get a SESSION vairable, if the variable is not present, take the default value
function SESSION_value($key,$default_value='')
{
   $value=isset($_SESSION[$key])? $_SESSION[$key] : $default_value;
   if (is_string($value))$value=trim($value);
   return $value;
}

// Prepare variable for mysql queries
function quote_smart($value)
{
    // trim strings
    if (is_string($value))$value=trim($value);

    // Stripslashes
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    if (empty($value)) return "''";
    if (!is_numeric($value)) {
        $value = "'" . mysql_real_escape_string($value) . "'";
    }
    return $value;
}
/// 
function  clean_text($word){
    $word=trim($word);
    $word=strip_tags($word);
    if (get_magic_quotes_gpc()) {
        $word = stripslashes($word);
    }
    $word=mysql_real_escape_string($word);
    return $word;
}


// Make a random string which has length of $length
function str_makerand ($length, $useupper=false,$usenumbers=false, $usespecial=false)
{
    $charset = "abcdefghijklmnopqrstuvwxyz";
    if ($useupper)   $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    if ($usenumbers) $charset .= "0123456789";
    if ($usespecial) $charset .= "~@#$%^*()_+-={}|][";   // Note: using all special characters this reads: "~!@#$%^&*()_+`-={}|\\]?[\":;'><,./";
    $key='';
    for ($i=0; $i<$length; $i++) $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
    return $key;
}


function format_time($time_stamp)
{
   global $_text;
   $now=time();
   $past_seconds=$now-$time_stamp;
   $past_minutes=floor($past_seconds/60);
   $past_hours=floor($past_seconds/3600);
   $past_days=floor($past_seconds/(24*3600));

   if ($past_days>1)
      $time="$past_days $_text[days]"."<span class='listing_key'> ".$_text['ago']."</span>";
   else if ($past_days>0)
      $time="$past_days $_text[day] $_text[ago]";
   else if ($past_hours>1)
      $time="$past_hours $_text[hours] $_text[ago]";
   else if ($past_hours>0)
      $time="$past_hours $_text[hour] $_text[ago]";
   else if ($past_minutes>1)
      $time="$past_minutes $_text[minutes] $_text[ago]";
   else //if ($past_minutes>0)
      $time="$past_minutes $_text[minute] $_text[ago]";
   return $time;
}

function format_time2($time_stamp)
{
   global $_text;
   $now=time();
   $tmp = $now - $time_stamp;
   if ($tmp > 0)
      $past_seconds=$tmp;
   else
   {
      $past_seconds= $tmp*(-1);
   }
    
   $past_minutes=floor($past_seconds/60);
   $past_hours=floor($past_seconds/3600);
   $past_days=floor($past_seconds/(24*3600));

   if ($past_days>1)
      $time="$past_days $_text[days]"."<span class='listing_key'></span>";
   else if ($past_days>0)
      $time="$past_days $_text[day]";
   else if ($past_hours>1)
      $time="$past_hours $_text[hours]";
   else if ($past_hours>0)
      $time="$past_hours $_text[hour]";
   else if ($past_minutes>1)
      $time="$past_minutes $_text[minutes]";
   else //if ($past_minutes>0)
      $time="$past_minutes $_text[minute]";
   if ($tmp > 0)
      $time .= "<font color='red'> ".$_text['ago']."</font>";
   return $time;
}


//
function get_micro_time()
{
   list($usec, $sec) = explode(" ",microtime());
   return ((float)$usec + (float)$sec);
}
//

function format_duration($dur)
{

   $hh=floor($dur/3600);
   $mm=floor($dur/60) - $hh*60;
   $ss=$dur - $hh*3600 - $mm*60;
   if ($hh>0) return "${hh}h${mm}m";
   else if ($mm>0) return "${mm}m";
   else return "${ss}m";
}


// Similar to http_build_query of PHP 5 but simpler
function http_simple_query($array)
{
   $query='';
   foreach ($array as $key => $value)
   {
      if (is_array($value))
      {
         foreach ($value as $subkey => $subvalue)
         {
            $query.=$key."[$subkey]=".urlencode($subvalue).'&';
         }
      }
      else
         $query.=$key.'='.urlencode($value).'&';
   }
   return $query;
}


/*
*/
function trigger_error_page($message)
{
   require_once(WEBSITE_ROOT.'/common/class_Template.php');
   $pageinfo=new Pageinfo();
   $pageinfo->current_page = 'error';
   $pageinfo->page_title.=' - Error! ';
   $content='<div style="padding:50px;"><div class="error_message">'.$message.'</div></div>';

   $main_tpl = new Template(WEBSITE_ROOT.'/themes/main.html');
   $pageinfo->generate_pageinfo();
   $main_tpl->set('pageinfo', $pageinfo->data);
   $main_tpl->set('content', $content);
   echo $main_tpl->fetch();
   die();
}


//Check and create a directory if neccessary
function check_and_create_dir($dir)
{
   if (file_exists($dir))
   {
      if (is_dir($dir))
         return true;
      else
         return false; //file is not a directory!";
   }
   else  // try to create it
   {
      if (mkdir($dir, true))
      {
         chmod($dir,0777);
         return true;
      }
      else
         return false; // Cannot create
   }
}

// Remove <!--comment like this-->
function remove_html_comments($str){
   return preg_replace('/<!--(.|\s)*?-->/', '', $str); 
}


// High lighe
function hightlight($str, $keywords,$style = 'highlight')
{
    $keywords = preg_replace('/\s\s+/', ' ', strip_tags(trim($keywords))); // filter
    $keywords=explode(' ', $keywords);
    foreach($keywords as $k){
       $k=trim($k);
       $pat="/\b(${k})\b/i";
       $str = preg_replace("$pat", '<span class="'.$style.'">$1</span>', $str);
    }
    return $str;
}

//For script times
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function get_url(){
  $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
  if ($_SERVER["SERVER_PORT"] != "80")
  {
      $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
  } 
  else 
  {
      $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
  }
  return $pageURL;
}

function get_url_no_get_var($var){
  $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
  if ($_SERVER["SERVER_PORT"] != "80")
  {
      $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
  } 
  else 
  {
      $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
  }

  $pos = strpos($pageURL, $var);

  if ($pos === false) {
      return $pageURL;
  } else {
      $len = $pos;
      //echo $pageURL; exit();
      $rest = substr($pageURL,0, $pos);
  return $rest;
  }
}
function remove_substr($org_string,$substring){
  $string =$org_string;
  $pos = strpos($string, $substring);

  if ($pos === false) {
      return $string;
  } else {
      $len = $pos;
      //echo $pageURL; exit();
      $string = substr($string,0, $pos);
  return $string;
  }
}

function load_modun($modun_name){//mặc định file php và html tên là default 
  $modun_content='';
  $modun_template_path = 'template/modun/'.$modun_name;
  include 'phpfiles/modun/'.$modun_name.'/default.php';
  //xử lý file default.php và trả lại giá trị vào biến $modun_content;
  return $modun_content;
}
function load_footer($pathlevels){//mặc định file php và html tên là default 
  $modun_content='';
  $pathlevel = $pathlevels;
  $modun_name= 'footer';
  $modun_template_path = 'template/modun/'.$modun_name;
  include 'phpfiles/modun/'.$modun_name.'/default.php';
  //xử lý file default.php và trả lại giá trị vào biến $modun_content;
  return $modun_content;
}
function load_header($modun_name,$category,$keywords){//mặc định file php và html tên là default 
  //echo $category;exit();
  $option=$category;
  $modun_content='';
  $keyword=$keywords;
  $modun_template_path = 'template/modun/'.$modun_name;
  $path = $modun_template_path;
  include 'phpfiles/modun/'.$modun_name.'/default.php';
  //xử lý file default.php và trả lại giá trị vào biến $modun_content;
  return $modun_content;
}
function load_modun_wp($modun_name,$page){//mặc định file php và html tên là default 
  $page=$page;
  $modun_content='';
  $modun_template_path = 'template/modun/'.$modun_name;
  include 'phpfiles/modun/'.$modun_name.'/default.php';
    //xử lý file default.php và trả lại giá trị vào biến $modun_content;
  return $modun_content;
}
function loadpage($page_name){
  $page_content='';
  $page_template_path = 'template/pages/'.$page_name;
  $page_phpfiles_path = 'phpfiles/pages/'.$page_name;
  include $page_phpfiles_path.'/default.php';
  return $page_content;
}
function load_page($page_name,$cat,$id){
  $cat = $cat;
  $id = $id;
  $page_content='';
  $page_template_path = 'template/pages/'.$page_name;
  $page_phpfiles_path = 'phpfiles/pages/'.$page_name;
  include $page_phpfiles_path.'/default.php';
    //xử lý file default.php và trả lại giá trị vào biến $page_content;
  return $page_content;
}
function load_subpage($subpage_name){
  //$cat = $cat;
  //$id = $id;
  $page_content='';
  $page_template_path = 'template/pages/'.$subpage_name;
  $page_phpfiles_path = 'phpfiles/pages/'.$subpage_name;
  include $page_phpfiles_path.'/default.php';
    //xử lý file default.php và trả lại giá trị vào biến $page_content;
  return $page_content;
}

  function paging($page,$show_number_page_link,$count_totalpage,$current_page_url){

    //$show_number_page_link = 2;                         //Hiển thị số trang liên tiếp ở pagination      ///
    //$limit_items = 3;                                 //Hiển thị bao nhiêu đối tượng/ bài viết mỗi trang  ///
    //$page=isset($_GET['page'])? $_GET['page'] : 1;      //Lấy giá trị số trang page truyền từ URL       ///
    //$item_to_count="ID";                              //Select 'ID' để đếm số dòng thay cho SELECT *    ///
    //$count_totalpage=100;
    //$current_page_url='home.php';

    $pagination = '';
    $start_page=1;
    $end_page=$count_totalpage;

    if ($page>$show_number_page_link) {$start_page = $page-$show_number_page_link;}
    $end_page=$count_totalpage;
    if ($page<$count_totalpage-$show_number_page_link) {$end_page = $page+$show_number_page_link;}
    $pagination.= "<div class=\"pagination\">";
      $pagination.=  "<a href='{$current_page_url}&page=1'>Đầu &nbsp;</a>";
      for ( $page = $start_page; $page <= $end_page; $page ++ ){
        $pagination.=  "<a href='{$current_page_url}&page={$page}'>{$page} &nbsp;</a>";
      }
      $pagination.=  "<a href='{$current_page_url}&page={$count_totalpage}'>Cuối &nbsp;</a>";
    $pagination.=  "</div>";
    //echo $pagination;
    return $pagination;
  }
  function search_paging($page,$show_number_page_link,$count_totalpage,$current_page_url,$keyword){

    $pagination = '';
    $start_page=1;
    $end_page=$count_totalpage;

    if ($page>$show_number_page_link) {$start_page = $page-$show_number_page_link;}
    $end_page=$count_totalpage;
    if ($page<$count_totalpage-$show_number_page_link) {$end_page = $page+$show_number_page_link;}
    $pagination.= "<div class=\"pagination\">";
      $pagination.=  "<a href='{$current_page_url}/search-{$keyword}-page-1.html'>Đầu &nbsp;</a>";
      for ( $page = $start_page; $page <= $end_page; $page ++ ){
        $pagination.=  "<a href='{$current_page_url}/search-{$keyword}-page-{$page}.html'>{$page} &nbsp;</a>";
      }
      $pagination.=  "<a href='{$current_page_url}/search-{$keyword}-page-{$count_totalpage}.html'>Cuối &nbsp;</a>";
    $pagination.=  "</div>";
    //echo $pagination;
    return $pagination;
  }
  function download_permission($linkkute){
      $linkkute;
      if ((!isset($_SESSION['memberloggedin']))&&($_SESSION['memberloggedin']!='Guest')){
          return $linkkute;
      } else {
          return '#';
      };
  }

  //echo $friendurl = str_replace_assoc($replace_special_chars,$title);

  function str_replace_assoc($subject) { 
    $replace_special_chars = array( 
    'A' => 'a', 
    'Á' => 'a', 
    'À' => 'a', 
    'Ả' => 'a', 
    'Ạ' => 'a', 
    'Ã' => 'a',
    'Â' => 'a', 
    'Ấ' => 'a', 
    'Ầ' => 'a', 
    'Ẩ' => 'a', 
    'Ậ' => 'a', 
    'Ẫ' => 'a',
    'Ă' => 'a', 
    'Ắ' => 'a', 
    'Ằ' => 'a', 
    'Ẳ' => 'a', 
    'Ặ' => 'a', 
    'Ẵ' => 'a',
    'D' => 'd', 
    'Đ' => 'd', 
    'E' => 'e', 
    'É' => 'e',
    'È' => 'e', 
    'Ẻ' => 'e', 
    'Ẹ' => 'e', 
    'Ẽ' => 'e', 
    'Ê' => 'e', 
    'Ế' => 'e',
    'Ề' => 'e', 
    'Ể' => 'e', 
    'Ệ' => 'e',
    'Ễ' => 'e', 
    'O' => 'o',
    'Ó' => 'o',
    'Ò' => 'o',
    'Ỏ' => 'o',
    'Ọ' => 'o',
    'Õ' => 'o',
    'Ô' => 'o',
    'Ố' => 'o',
    'Ồ' => 'o',
    'Ổ' => 'o',
    'Ộ' => 'o',
    'Ỗ' => 'o',
    'Ơ' => 'o',
    'Ớ' => 'o',
    'Ờ' => 'o',
    'Ợ' => 'o',
    'Ở' => 'o',
    'Ỡ' => 'o',
    'U' => 'u',
    'Ú' => 'u',
    'Ù' => 'u',
    'Ủ' => 'u',
    'Ụ' => 'u',
    'Ũ' => 'u',
    'Ư' => 'u',
    'Ứ' => 'u',
    'Ừ' => 'u',
    'Ữ' => 'u',
    'Ự' => 'u',
    'Ữ' => 'u',
    'Y' => 'y',
    'Ý' => 'y',
    'Ỳ' => 'y',
    'Ỷ' => 'y',
    'Ỵ' => 'y',
    'Ỹ' => 'y',
    'à' => 'a', 
    'á' => 'a', 
    'ả' => 'a', 
    'ã' => 'a', 
    'ạ' => 'a', 
    'â' => 'a', 
    'ă' => 'a', 
    'ặ' => 'a', 
    'á' => 'a',
    'à' => 'a',
    'ả' => 'a',
    'ấ' => 'a', 
    'ắ' => 'a',
    'ầ' => 'a', 
    'ậ' => 'a', 
    'ằ' => 'a',
    'ẳ' => 'a',
    'ẩ' => 'a',
    'đ' => 'd',
    'è' => 'e', 
    'é' => 'e', 
    'ẻ' => 'e', 
    'ẹ' => 'e',
    'ê' => 'e', 
    'ế' => 'e',
    'ề' => 'e',
    'ể' => 'e',
    'ệ' => 'e',
    'ò' => 'o', 
    'ó' => 'o', 
    'ỏ' => 'o', 
    'ô' => 'o', 
    'ố' => 'o', 
    'ồ' => 'o', 
    'ổ' => 'o', 
    'ộ' => 'o', 
    'ơ' => 'o', 
    'ớ' => 'o', 
    'ờ' => 'o', 
    'ở' => 'o', 
    'ỡ' => 'o', 
    'ợ' => 'o', 
    'ư' => 'u', 
    'ứ' => 'u', 
    'ừ' => 'u', 
    'ử' => 'u', 
    'ữ' => 'u', 
    'ự' => 'u', 
    'ủ' => 'u', 
    'ụ' => 'u', 
    'ỷ' => 'y',
    'ý' => 'y',
    'ỳ' => 'y',
    'ỹ' => 'y',
    'ỵ' => 'y',
    '"' => '', 
    '”' => '',
    '“' => '',
    '*' => '',
    '`' => '',
    '\'' => '',
    ':' => '', 
    '&' => '_', 
    ' ' => '_'
  ); 
     return str_replace(array_keys($replace_special_chars), array_values($replace_special_chars), $subject);    
  }

?>