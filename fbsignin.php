<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once ("config.php");
require_once ("facebook.php");
require_once ("authutil.php");
require_once ("bcutil.php");

$config = array();
$config['appId'] = FB_APPID;
$config['secret'] = FB_SECRET;
$config['allowSignedRequest'] = false;
$config['fileUpload'] = false;
$config['scope'] = 'email';

$facebook = new Facebook($config);
$ret['status'] = 'failure';
if ($facebook) {
    if (isset($_POST['fbuid'])) {
        if ( intval(filter_input(INPUT_POST, "remember")) != 0) {
            $remember = true;
        } else {
            $remember = false;
        }
        $fbuid = intval($_POST['fbuid']);  
        if ($fbuid == $facebook->getUser()) {
            $userid = getUseridFromFbuid($fbuid);
            $user_profile = $facebook->api('/me','GET');
            $tstamp = time();
            $email = $user_profile['email'];    
            // case 1: fbuid not in the system
            if ($userid != 0) {
                 setBACookie($userid, $remember);
                 $ret['status'] = "success";
            } else {
                if ($email == '') {
                    $ret['errormsg'] = "Facebook authorization denial";
                    echo json_encode($ret);
                    exit();
                }
                $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                if ($mysqli->connect_error) {
                    $ret['errormsg'] = "Internal Error: Can't connect to DB";
                    echo json_encode($ret);
                    exit();
                }
                if (($uid = getUseridFromUsername($email)) != 0) {   
                    $mysqli->query("UPDATE usertbl SET fbuserid=$fbuid where username='$email'");
                    setBACookie($uid, $remember);
                    $ret['firstname'] = $user_profile['firstname'];
                    $ret['lastname'] = $user_profile["last_name"];
                    $ret['email'] = $email;
                    $ret['status'] = "accountlinked";
                } else if (($uid = getUseridFromEmail($email)) != 0) {
                    $mysqli->query("UPDATE usertbl SET fbuserid=$fbuid where email='$email'");
                    setBACookie($uid, $remember);
                    $ret['firstname'] = $user_profile['firstname'];
                    $ret['lastname'] = $user_profile["last_name"];
                    $ret['email'] = $email;
                    $ret['status'] = "accountlinked";
                } else {
                    $first = $user_profile["first_name"];
                    $last = $user_profile["last_name"];
                    $sql = "INSERT into usertbl set email='$email', username='$email', firstname='$first', lastname='$last', passwd='none', fbuserid=$fbuid, type='user'";
                    $result = $mysqli->query($sql);
                    if ($result) {
                        $userid = $mysqli->insert_id;
                        setBACookie($userid, $remember);
                        $ret['status'] = "accountcreated";
                        $ret['email'] = $email;
                        $ret['firstname'] = $user_profile["first_name"];
                        $ret['lastname'] = $user_profile["last_name"];
                    } else {
                        $ret['errormsg'] = "mysql failure";
                        $ret['email'] = $email;
                    }
                }
            }   
        } else {
            $ret['errormsg'] = "Can't login with Facebook";
            $ret['status'] = 'failure';        
        }
    }
} else {
    $ret['errormsg'] = "Can't connect to facebook"; 
} 
echo json_encode($ret);

?>