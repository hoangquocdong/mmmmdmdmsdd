<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";
require "libs/custom_functions.php";

$id =  (int)isset($_REQUEST['id'])? $_REQUEST['id'] : 0;
$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$info =  isset($_REQUEST['info'])? $_REQUEST['info'] : '';

$start = microtime(true);

  CONNECT_DB();

    mysql_query("SET NAMES utf8");

    

    update_userinfo($userid, $info);

    function update_userinfo($userid, $info) {

        $returnarray = array(
            'status' => 500,
            'content'=> 'Create new user fail!'
        );

        $userdata = json_decode($info);

        $full_name = clean_text($userdata ->{'fullname'});
        $id_pwc = (int)clean_text($userdata ->{'pcid'});
        $pcname = clean_text($userdata ->{'pcname'});
        $id_investor = (int)clean_text($userdata ->{'investorid'});
        $id_sub = (int)clean_text($userdata ->{'subid'});
        $user_name = clean_text($userdata ->{'username'});
        $password = clean_text($userdata ->{'password'});
        $office_name = clean_text($userdata ->{'officename'});
        //$department_name = clean_text($userdata ->{'fullname'});
        $phone_number = clean_text($userdata ->{'phone'});
        $email = clean_text($userdata ->{'email'});
        $enable = (int)clean_text($userdata ->{'enable'});
        $last_visit = clean_text($userdata ->{'lastvisit'});
        $visit_number = (int)clean_text($userdata ->{'visitnumber'});
        $register_date = clean_text($userdata ->{'regdate'});
        $permission = clean_text($userdata ->{'permission'});
        $writable = (int)clean_text($userdata ->{'writable'});
        $usertype = (int)clean_text($userdata ->{'usertype'});
        $editable = (int)clean_text($userdata ->{'editable'});

        $register_date=date("d/m/y H:i:s"); 
    
        $currentpos = 'csdl,csct,0,60,96471056,MK,';  


        $sql = 'INSERT INTO `user` (`id_pwc`, `id_investor`, `id_sub`, `full_name`, `user_name`, `password`, 
                            `office_name`, `phone_number`, `email`, `currentpos`, `enable`, `register_date`, `permission`, 
                            `writable`, 
                            `usertype`, `editable`, `flag_change`) 
                VALUES ('.$id_pwc.','.$id_investor.','.$id_sub.',"'.$full_name.'","'.$user_name.'",
                        "'.MD5($password).'","'.$office_name.'", "'.$phone_number.'","'.$email.'","'.$currentpos .'",
                        '.$enable.',"'.$register_date.'","'.$permission.'", '.$writable.','. $usertype.','. $editable.', 1)';


        $result = mysql_query($sql) or die(json_encode($returnarray));

        $returnarray = array(
            'status' => 200,
            'content'=> 'Create new user info succesfully!'
        );

        $actioncode = 22; //create new user info profile
        $moredetail = 'create username : '.$user_name;
        useradminlogs($userid, $actioncode, $moredetail);

        echo json_encode($returnarray); 
    }

    CLOSE_DB();
    unset($sql, $result);
          
?> 

    