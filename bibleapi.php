<?php

/* 
 * The MIT License
 *
 * Copyright 2014 Calvin Ko.
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
 * 
 * 
 * biblecircle.org/api/bible/{book}/{chapter}?version=ver
 * biblecircle.org/api/plan/planinstid/day/section POST/GET status ?accesstoken=.... 
 * biblecircle.org/api/plan/planinstid/day/
 */


require_once 'config.php';
require_once 'bibleutil.php';

$allowedOrigins = array(
    'http://localhost:5173',
    'http://biblecircle.vercel.app',
);

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins, true)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Vary: Origin');
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysqli->query("SET NAMES 'utf8'");

$route = filter_input(INPUT_GET, "__route__", FILTER_DEFAULT);

if ($route[0] == "/") {
    $route = substr($route, 1);
}
        
$elms = explode("/", $route);

$book = filter_var($elms[2], FILTER_DEFAULT);
$chapter = filter_var($elms[3], FILTER_DEFAULT);
if ( filter_has_var(INPUT_GET, 'version')) {
    $version = filter_input(INPUT_GET, 'version', FILTER_DEFAULT);
} else {
    $version = 'KJV';
}

//if ( filter_has_var(INPUT_GET, 'tab')) {
//    $book = filter_input(INPUT_GET, 'book', FILTER_DEFAULT);
//}

$data = getChapterText($book, $chapter, $version);
$retval['bookname'] = parseBookName($book, $version);
$retval['chapter'] = $chapter;
$retval['title'] = $retval['bookname'] . ' ' . $chapter;
$retval['rows'] = $data;
echo json_encode($retval);

?>
