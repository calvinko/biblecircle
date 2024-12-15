<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once "authutil.php";
require_once "bcutil.php";

if ( isset($_POST['username']) && isset($_POST['oldpasswd']) && isset($_POST['newpasswd'])) {

    $username = $_POST['username'];
    $oldpasswd = $_POST['oldpasswd'];
    $newpasswd = $_POST['newpasswd'];
 
    $sql = "SELECT `userid`, `passwd` FROM `usertbl` WHERE `username` = '$username'"; 
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $result = $mysqli->query($sql);

    

}