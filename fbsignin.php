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
$config['appId'] = '72255585723';
$config['secret'] = '91bb265d7aa60af7f03a3f42a58a4f96';
$config['allowSignedRequest'] = false;
$config['fileUpload'] = false;

$facebook = new Facebook($fbconfig);
$ret['status'] = 0;
if ($facebook) {
    if (isset($_POST['fbuid'])) {
        $fbuid = intval($_POST['fbuid']);  
        if ($fbuid == $facebook->getUser()) {
            $userid = getUseridFromFbuid($fbuid);
            $user_profile = $facebook->api('/me','GET');
            
            $email = $user_profile['email'];    
            // case 1: fbuid not in the system
            if ($userid != 0) {
                 setBACookie($userid);
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
                        //setBACookie($userid);
                        $ret['status'] = "createdaccount";
                        
                    }
                    $ret['firstname'] = $user_profile["first_name"];
                    $ret['lastname'] = $user_profile["last_name"];
                    
                } else {
                    $mysqli->query("UPDATE usertbl SET fbuserid=$fbuid where email='$email'");
                    setBACookie($uid);
                    $ret['firstname'] = $user_profile['firstname'];
                    $ret['lastname'] = $user_profile["last_name"];
                    $ret['email'] = $email;
                    $ret['status'] = "error";
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