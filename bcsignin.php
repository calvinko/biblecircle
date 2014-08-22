<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once "userauth.php";

$ret['errormsg'] = "Invalid username or password";
$ret['status'] = 0;

try {
    $auth = new UserManager(100);
    print_r($auth);
} catch (Exception $e) {
    echo $e->getMessage();
}

if (filter_has_var(INPUT_POST, 'username') && filter_has_var(INPUT_POST, 'passwd')) {
    $username = filter_input(INPUT_POST, 'username', FILTER_DEFAULT);
    $passwd = filter_input(INPUT_POST, 'passwd', FILTER_DEFAULT);
    if ( intval(filter_input(INPUT_POST, "remember")) != 0) {
            $remember = true;
        } else {
            $remember = false;
    }
   
    try {
        $auth = new UserManager($username);
        if ($auth->checkpasswd("$passwd") == 1) {
            $ret['remember'] = $remember;
            $auth->setAuthCookie($remember);
            $ret['status'] = 1;
            $ret['errormsg'] = "Succeed";
        } else {
            $ret['status'] = 0;
            $ret['errormsg'] = "Incorrect password";
        }
        
    } catch (AuthException $e) {
        $ret['status'] = 0;
        $ret['errormsg'] = "Invalid username or password";
        
    };
    
} else {
    $ret['status'] = 0;
    $ret['errormsg'] = "Sign In Error";
}



echo json_encode($ret);