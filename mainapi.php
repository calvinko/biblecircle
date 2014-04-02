<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

# biblecircle.org/bible/{version}/{book}/{chapter}
# biblecircle.org/users/{username}/profile
# biblecircle.org/users/me/profile
# biblecircle.org/plan/{planid}

require_once 'authutil.php';
require_once 'bcutil.php';

$route = $_GET["__route__"];
if ($route[0] == "/")
    $route = substr($route, 1);
        
$elms = explode("/", $route);

$base = strtolower($elms[0]);
$userid = 0;

$ret['_status'] = "failure";
$ret['_errorcode'] = 10;
$ret['_errormsg'] = "Invalid REST query";

function get_request_userid($instr, $userid) {
    if ($instr == "me") {
        $requid = $userid;
    } elseif (is_integer($instr)) {
        $requid = intval($instr);

    } else {
        $requid = getUseridFromUsername($instr);
    }
    return $requid;
}

function rest_userapi($elms, $r) {
    $astatus = Authenticate::validateAuthCookie();
    $ret = array();
    if ($astatus) {
        $userid = Authenticate::getUserId();
        $requid = get_request_userid($elms[1], $userid);
        if ($elms[2] == "profile" || $elms[2] == null) {
            // check permission
            if ($userid == $requid) {
                $p = getUserProfile($userid);
                if ($p != null) {
                    $ret = array_merge($r, $p);
                    if ($ret['passwd'] != "empty") {
                        $ret['passwd'] = "set"; 
                    };
                    $ret['_status'] = 'success';
                    $ret['_errorcode'] = 0;
                    $ret['_errormsg'] = "";
                } else {
                    $ret['_status'] = 'failure';
                }
                return $ret;
            } else {
                $ret['_status'] = 'failure';
                $ret['_errorcode'] = 20;
                $ret['_errormsg'] = 'Access denied';
                return $ret;
            }
        } elseif ($elms[2] == "readingplan") {
            return $r;
        } elseif ($elms[2] == "readingplanlist") {
            return $r;
        } else {
            return $r;
        }
        
    } else {
        $ret['_status'] = "failure";
        $ret['_errorcode'] = 100;   /* authentication needed */
        return $ret;
    } 
}

if ($base == "user") {
    echo json_encode(rest_userapi($elms, $ret));
    exit();
} elseif ($base == "bible") {
    
} elseif ($base == "page") {
    if ($elms[1] == "profile") {
        require "profile.php";
        exit();
    }
} else {
    
}
    
 echo "exception";   
 print_r($_GET);
    
 print_r($elms);
    
    
    
?>