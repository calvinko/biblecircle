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

define('DB_NAME', 'dbko1');

/** MySQL database username */
define('DB_USER', 'dbko1');

/** MySQL database password */
define('DB_PASSWORD', 'Spart@123');

/** MySQL hostname */
define('DB_HOST', 'dbko1.db.3694379.hostedresource.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysqli->query("SET NAMES 'utf8'");



$sql = "SELECT * from donation";

$allow = 1;

if ($allow) {  
    
    if (isset($_GET['runnerid'])) {
        $runnerid = $_GET['runnerid'];
    } else {
        $runnerid = 0;
    }
    
    if ($runnerid == 0) {
        $sql = "SELECT * from donation ORDER BY date";
    } else {
        $sql = "SELECT * from donation WHERE runnerid=$runnerid ORDER BY date";
    }
    $result = $mysqli->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $ret[] = $row;
        }
        echo json_encode($ret);
    }
}
    