<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once "authutil.php";
require_once "bcutil.php";

if ( isset($_POST['username']) && isset($_POST['passwd'])) {
    $username = $_POST['username'];
    $passwd = $_POST['passwd'];
    if (isset ($_POST['remember'])) {
        $remember = intval($_POST['remember']);
    } else {
        $remember = 0;
    }
     
    try {
        $auth = new Authenticate($username);
        $auth->authenticate($username, $passwd, $remember);
        
        $ret['remember'] = $remember;
        $ret['status'] = 1;
        
    } catch (AuthException $e) {
        $ret['status'] = 0;
        $ret['errormsg'] = "Invalid username or password";
        
    };
    
} else {
    $ret['status'] = 0;
    $ret['errormsg'] = "Invalid username or password";
}

echo json_encode($ret);