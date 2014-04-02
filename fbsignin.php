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

$facebook = new Facebook($config);
$ret['status'] = 0;
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
            
            $email = $user_profile['email'];    
            // case 1: fbuid not in the system
            if ($userid != 0) {
                 setBACookie($userid, $remember);
                 $ret['status'] = "success";
            } else {
                $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                if ($mysqli->connect_error) {
                    $ret['errormsg'] = "Internal Error: Can't connect to DB";
                    echo json_encode($ret);
                    exit();
                }
                if (($uid = getUseridFromUsername($email)) == 0) {   
                    $sql = "INSERT into usertbl set created='$tstamp', email='$email', username='$email', passwd='none', fbuserid=$fbuid";
                    $result = $mysqli->query($sql);
                    if ($result) {
                        $userid = $mysqli->insert_id;
                        setBACookie($userid, $remember);
                        $ret['status'] = "accountcreated";
                        $ret['email'] = $email;
                        $ret['firstname'] = $user_profile["first_name"];
                        $ret['lastname'] = $user_profile["last_name"];
                        
                    }
                    
                } else {
                    $mysqli->query("UPDATE usertbl SET fbuserid=$fbuid where email='$email'");
                    setBACookie($uid, $remember);
                    $ret['firstname'] = $user_profile['firstname'];
                    $ret['lastname'] = $user_profile["last_name"];
                    $ret['email'] = $email;
                    $ret['status'] = "accountlinked";
                }
            }   
        } else {
            $ret['errormsg'] = "Can't login with Facebook";
            $ret['status'] = 'failure';        
        }
    }
}
echo json_encode($ret);

?>