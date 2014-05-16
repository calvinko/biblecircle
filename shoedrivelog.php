<?php

/* 
 * The MIT License
 *
 * Copyright 2014 KoSolution.net
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

require_once("authutil.php");
require_once("bcutil.php");

initmysqli();

function getgoal($userid) {
    global $mysqli;
    $sql = "SELECT * from miscuserdata WHERE userid=$userid and fieldname='shoegoal2014'";
    $result = $mysqli->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
            return $row['fieldvalue'];
        } else {
            return "";
        }
    }
}

function gettotal() {
    global $mysqli;
    $sql = "SELECT SUM(number) from shoedonationlog WHERE dflag=0 and ugroup='Oakland'";
    $result = $mysqli->query($sql);
    if ($result) {
        $row = $result->fetch_row();
        return $row[0];
    } else {
        return 0;
    }
}

function getuserlist() {
    global $mysqli;
    $ret = array();
    $result = $mysqli->query("SELECT userid,username,firstname,lastname from usertbl where type='user'");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $ret[] = $row;
        }
    }
    return $ret;
}

$SHOEGOAL2014 = "500";
$ret['_status'] = 0;

$astatus = Authenticate::validateAuthCookie();
if ($astatus) {
    $userid = Authenticate::getUserId();
} 

if ($userid != 0) {
    $op = "fetch";
    if (filter_has_var(INPUT_GET, "op")) {
        $op = filter_input(INPUT_GET, "op", FILTER_DEFAULT);
    }
    if ($op == "getlog") {
        if (filter_has_var(INPUT_GET, "filter")) {
            $filter = filter_input(INPUT_GET, "filter", FILTER_DEFAULT);
            $cond = "dflag = 0 and $filter";
        } else {
            $cond = "dflag = 0";
        }
        $ret['goal'] = $SHOEGOAL2014;
        $ret['userlist'] = getuserlist();
        $result = $mysqli->query("SELECT * from shoedonationlog WHERE $cond");
        if ($result) {
            $total = 0;
            while ($row = $result->fetch_assoc()) {
                $ret['data'][] = $row;
                $total = $total + $row['number'];
            }
            $ret['_status'] = 1;
            $ret['total'] = $total;
        } else {
            $ret['_status'] = 0;
            $ret['_errormsg'] = $mysqli->error;
        }
    } elseif ($op == "fetch") {
        if (filter_has_var(INPUT_GET, "targetuser")) {
            $targetuser = filter_input(INPUT_GET, "targetuser", FILTER_DEFAULT);
            // check permission
            if ($targetuser == "all" or $targetuser == "*") {
                $cond = "dflag = 0";
            } else {
                $cond = "userid = $targetuser and dflag = 0";
            }
        } else {
            $cond = "userid = $userid and dflag = 0";
        }
        $ret['goal'] = getgoal($userid);
        $ret['gstatus'] = "0";
        $result = $mysqli->query("SELECT * from shoedonationlog WHERE $cond");
        if ($result) {
            $total = 0;
            while ($row = $result->fetch_assoc()) {
                $ret['data'][] = $row;
                $total = $total + $row['number'];
            }
            $ret['_status'] = 1;
            $ret['total'] = $total;
        } else {
            $ret['_status'] = 0;
            $ret['_errormsg'] = $mysqli->error;
        }
    } elseif ($op == "insert") {
        if (filter_has_var(INPUT_GET, "inuserid")) {
            $inuserid = filter_input(INPUT_GET, "inuserid", FILTER_SANITIZE_NUMBER_INT);
        } else {
            $inuserid = $userid;
        }
        if (filter_has_var(INPUT_GET, "date") and filter_has_var(INPUT_GET, "number")) {
            $date = filter_input(INPUT_GET, "date", FILTER_DEFAULT);
            $num = filter_input(INPUT_GET, "number", FILTER_SANITIZE_NUMBER_INT);
            $result = $mysqli->query("INSERT INTO shoedonationlog (userid,number,ddate) VALUES($inuserid, $num, '$date') ");
            if ($result) {
                $ret['ddate'] = $date;
                 $ret['_status'] = 1;
            } else {
                $ret['_status'] = 0;
            $ret['_errormsg'] = $mysqli->error;
            }
        } else {
            $ret['_status'] = 0;
            $ret['_errormsg'] = "date and number needed";
        }
    } elseif ($op == "update") {
        if (filter_has_var(INPUT_GET, "recid") and filter_has_var(INPUT_GET, "number")) {
            $recid = filter_input(INPUT_GET, "recid", FILTER_SANITIZE_NUMBER_INT);
            $num = filter_input(INPUT_GET, "number", FILTER_SANITIZE_NUMBER_INT);
            $result = $mysqli->query("UPDATE shoedonationlog SET number=$number WHERE recid=$recid");
        } else {
            $ret['_status'] = 0;
            $ret['_errormsg'] = "recid and number needed";
        }
    } elseif ($op == "delete") {
        if (filter_has_var(INPUT_GET, "recid")) {
            $recid = filter_input(INPUT_GET, "recid", FILTER_SANITIZE_NUMBER_INT);
            $mysqli->query("update shoedonationlog SET dflag=1 WHERE recid=$recid");
        } else {
             $ret['_status'] = 0;
            $ret['_errormsg'] = "recid needed";
        }
        
    }
    
}

echo json_encode($ret);




        


