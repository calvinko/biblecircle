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

require_once("authutil.php");
require_once("bcutil.php");

initmysqli();

function hasfieldvalue($userid, $fname) 
{
    global $mysqli;
    $result = $mysqli->query("SELECT fieldname, fieldvalue FROM miscuserdata WHERE userid=$userid and fieldname='$fname'");
    if ($result) {
        if ($result->num_rows >= 1)
            return 1;
        else 
            return 0;
    } else {
        return -1;
    }
}

function setgoal($goal, $userid) 
{
    global $mysqli;
    $result = $mysqli->query("SELECT fieldname, fieldvalue FROM miscuserdata WHERE userid=$userid and fieldname='shoegoal2014'");
    if ($result) {
        if ($result->num_rows == 0) {
            $mysqli->query("INSERT INTO miscuserdata SET userid=$userid, fieldname='shoegoal2014', fieldvalue='$goal'");
            return 1;
        } else {
            $result = $mysqli->query("UPDATE miscuserdata SET fieldvalue='$goal' WHERE userid=$userid and fieldname='shoegoal2014' ");
            return 1;
        }
    } else {
        
        return 0;
    }
}

$astatus = Authenticate::validateAuthCookie();
if ($astatus) {
    $userid = Authenticate::getUserId();
} 

$ret['_status'] = 0;

if ($userid != 0) {
    if (filter_has_var(INPUT_POST, "goal")) {
        $newgoal = filter_input(INPUT_POST, "goal", FILTER_DEFAULT);
        $ret['_status'] = setgoal($newgoal, $userid);
        
    } elseif (filter_has_var(INPUT_POST, "addlog")) {
        
    } elseif (filter_has_var(INPUT_POST, "updatelog"))  {
        
    }
};
echo json_encode($ret);