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

$astatus = Authenticate::validateAuthCookie();
if ($astatus) {
    $userid = Authenticate::getUserId();
} 

if ($userid != 0) {
    $sql = "SELECT * from miscuserdata WHERE userid=$userid and fieldname='shoegoal2014'";
    $result = $mysqli->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
            $ret['goal'] = $row['fieldvalue'];
            $ret['gstatus'] = "0";
        } else {
            $ret['goal'] = "";
            $ret['gstatus'] = "0";
        }
    } else {
        $ret["_status"] = 3;
    }
} else {
    $ret["_status"] = 0;
}
echo json_encode($ret);