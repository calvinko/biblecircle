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

$mysqli = new mysqli('dbko1.db.3694379.hostedresource.com', 'dbko1', 'Spart@123', 'dbko1');
$mysqli->query("SET NAMES 'utf8'");

function getRunnerName($fbuid)
{
    global $mysqli;
    $sql = "SELECT name FROM r4o_user where fbuid=$fbuid ";
    $result = $mysqli->query($sql);
    if ($result) {
        $row = $result->fetch_row();
        return $row[0];
    } else {
        return "Name";
    }
}  

$user = 547918697;

if ($user != 0) {
    
    if (isset($_POST['runnerid'])) {
        $runnerid = filter_input(INPUT_POST, "runnerid", FILTER_DEFAULT);
    } else {
        $runnerid = $user;
    }
    $runnername = getRunnerName($runnerid);
    
    if (isset($_POST['donorid'])) {
        $donorid = filter_input(INPUT_POST, "donorid", FILTER_DEFAULT);
    } else {
        $donorid = 0;
    }
    if (isset($_POST['donorname'])) {
        $donorname = filter_input(INPUT_POST, "donorname", FILTER_DEFAULT);
    } else {
        $donorname = "Calvin KoKo";
    }
    if (isset($_POST['email'])) {
        $email = filter_input(INPUT_POST, "email", FILTER_DEFAULT);
    } else {
        $email = "ko.calvin@gmail.com";
    }
    if (isset($_POST['amount'])) {
        $amount = filter_input(INPUT_POST, "amount", FILTER_DEFAULT);
    } else {
        $amount = 0;
    }
    if (isset($_POST['paymenttype'])) {
        $paymenttype = filter_input(INPUT_POST, "paymenttype", FILTER_DEFAULT);
    } else {
        $paymenttype = "Paypal";
    }
    if (isset($_POST['paymentstatus'])) {
        $paymentstatus = filter_input(INPUT_POST, "paymentstatus", FILTER_DEFAULT);
    } else {
        $paymentstatus = "paid";
    }
    if (isset($_POST['date'])) {
        $date = filter_input(INPUT_POST, "date", FILTER_DEFAULT);
    } else {
        $date = date("Y-m-d");
    }
    //if ($runnerid == $user || isAdmin($user)) {
        $sql = "INSERT into donation (donorname,donor_id,email,runner,runnerid,amount,paymenttype,paymentstatus,date) VALUES('$donorname',$donorid,'$email','$runnername', $runnerid, $amount, '$paymenttype', '$paymentstatus', '$date')";
        $ret = $mysqli->query($sql);
        if ($ret != true) {
            echo $sql;
            echo "###";
            //echo $mysqli->error;
        } else {
            echo "1";
        }
    //}
}

