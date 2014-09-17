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
 * 
 * biblecircle.org/api/plan/planinstid/day/section POST/GET ?filter=status|info|all ?accesstoken=.... 
 * biblecircle.org/api/plan/planinstid/day/
 * 
 */


require_once 'config.php';
require_once 'bibleutil.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysqli->query("SET NAMES 'utf8'");

$route = filter_input(INPUT_GET, "__route__", FILTER_DEFAULT);

if ($route[0] == "/") {
    $route = substr($route, 1);
}
        
$elms = explode("/", $route);


if (isset($elms[2])) {
    $instid = filter_var($elms[2], FILTER_DEFAULT);
   
}

 
if (isset($elms[3])) {
    $day = filter_var($elms[3], FILTER_DEFAULT);
    $arg[3] = filter_var($elms[3], FILTER_DEFAULT);
}
if (isset($elms[4])) {
    $section = filter_var($elms[4], FILTER_DEFAULT);
    $arg[4] = filter_var($elms[4], FILTER_DEFAULT);
}

//echo "route = $route \n";
//echo "instid = $instid \n";
//echo "day = $day \n";

$rmethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_DEFAULT);
//echo $rmethod;

$logmgr = new BibleLogMgr();
if ($rmethod == 'GET') {
    try {
        if ($day == 'all') {
            $statusarray = $logmgr->getAllChapterStatus($instid);
            //echo "day status";
            $ret['_status'] = 'success';
            $ret['statustable'] = $statusarray;
            echo json_encode($ret);
            exit();
        } else if (is_numeric($day)) {
            $book = intval($day);
            if ($arg[4] != NULL && is_numeric($arg[4])) {
                $chapter = intval($arg[4]);
                if (filter_has_var(INPUT_GET, "filter")) {
                    $filter = filter_input(INPUT_GET, "filter", FILTER_DEFAULT);
                    if ($filter == "all") {
                        $status = $logmgr->getChapterStatus($instid, $book, $chapter);
                        $ret['_status'] = 'success';
                        $ret['status'] = $status;
                    } else if ($filter == 'status') {
                        $status = $logmgr->getChapterStatus($instid, $book, $chapter);
                        $ret['_status'] = 'success';
                        $ret['status'] = $status;
                    } else if ($filter == 'desc') {
                        
                    } else {
                        $ret['_status'] = 'failure';
                        
                    }
                } else {
                    $status = $logmgr->getChapterStatus($instid, $book, $chapter);
                    $ret['_status'] = 'success';
                    $ret['status'] = $status;
                }
                echo json_encode($ret);
                exit(0);
            } else {
                $statusarray = $logmgr->getBookChapterStatus($instid, $book);
                $ret['_status'] = 'success';
                $ret['statustable'] = $statusarray;
                echo json_encode($ret);
                return;
            }
        } else {
            echo "Method is $rmethod, day is $day";
        }
    } catch(Exception $e) {
        $ret['_status'] = 'failure';
        echo json_encode($ret);
        echo "Exception " . $e->getMessage();
        exit();
    }
} else if ($rmethod == 'POST') {
    
    try {
        $status = filter_input(INPUT_POST, "status", FILTER_SANITIZE_NUMBER_INT);
        $logmgr->updateChapterStatus($instid, $day, $section, $status);
        $ret['_status'] = 'success';
        $ret['_input'] = $status;
        //$ret['chpstatus'] = '1';
        $ret['chpstatus'] = $logmgr->getChapterStatus($instid, $day, $section);
        echo json_encode($ret);
    } catch (Exception $ex) {
        $ret['_status'] = 'failure';
        echo "Exception " . $ex->getMessage();
    }
} else {
    echo "null method";
}








