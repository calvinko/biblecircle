<?php


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include "authutil.php";

Authenticate::logOut();

header("location: http://www.biblecircle.org");


