<?php

/* 
 * The MIT License
 *
 * Copyright 2014 ko.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

require_once 'authutil.php';
require_once 'bcutil.php';

$userid = 0;
    
$astatus = Authenticate::validateAuthCookie();
if ($astatus) {
    $userid = Authenticate::getUserId();
} 

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

$ret['_status'] = 0;
$ret['api'] = "userapi";
$ret['userid'] = $userid;

$rmethod = $_SERVER['REQUEST_METHOD'];
$ret['method'] = $rmethod;
if ($userid != 0) {
    $route = $_GET["__route__"];
    if ($route[0] == "/")
        $route = substr($route, 1);
        
    $elms = explode("/", $route);
    
    $ret["id"] = $elms[0];
    if ($rmethod == "POST") {
        $expr = "";
        if (filter_has_var(INPUT_POST, "firstname")) {
            $firstname = filter_input(INPUT_POST, "firstname", FILTER_DEFAULT);
            if ($expr == "") {
                $expr = "firstname = '$firstname'";
            } else {
                $expr = $expr . " ,firstname = '$firstname'";
            }
        }
        if (filter_has_var(INPUT_POST, "lastname")) {
            $lastname = filter_input(INPUT_POST, "lastname", FILTER_DEFAULT);
             if ($expr == "") {
                $expr = "lastname = '$lastname'";
            } else {
                $expr = $expr . " ,lastname = '$lastname'";          
            }

        }

        if ($expr != "") {
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $result = $mysqli->query("UPDATE usertbl SET $expr WHERE userid=$userid");
            $ret['_status'] = $result;
        }
    } elseif ($rmethod == "GET") {
        
        $requid = get_request_userid($elms[0], $userid);
        if ($elms[1] == "profile" || $elms[1] == null) {
            // check permission
            if ($userid == $requid) {
                $p = getUserProfile($userid);
                if ($p != null) {
                    $ret = array_merge($ret, $p);
                    if ($ret['passwd'] != "empty") {
                        $ret['passwd'] = "set"; 
                    };
                    $ret['_status'] = 'success';
                    $ret['_errorcode'] = 0;
                    $ret['_errormsg'] = "";
                } else {
                    $ret['_status'] = 'failure';
                }
            } else {
                $ret['_status'] = 'failure';
                $ret['_errorcode'] = 20;
                $ret['_errormsg'] = 'Access denied';
            }
        }
    }
}

echo json_encode($ret);

 