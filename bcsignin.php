<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once "authutil.php";
require_once "bcutil.php";

$ret['errormsg'] = "Invalid username or password";
$ret['status'] = 0;

if ( isset($_POST['username']) && isset($_POST['passwd'])) {
    $username = $_POST['username'];
    $passwd = $_POST['passwd'];
    if ( intval(filter_input(INPUT_POST, "remember")) != 0) {
            $remember = true;
        } else {
            $remember = false;
    }
   
    try {
        $auth = new Authenticate($username);
        $auth->authenticate($username, $passwd, $remember);
        
        $ret['remember'] = $remember;
        $ret['status'] = 1;
        $ret['errormsg'] = "Succeed";
        
    } catch (AuthException $e) {
        $ret['status'] = 0;
        $ret['errormsg'] = "Invalid username or password";
        
    };
    
} else {
    $ret['status'] = 0;
    $ret['errormsg'] = "Invalid username or password";
}

echo json_encode($ret);