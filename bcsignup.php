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

require_once "authutil.php";
require_once "bcutil.php";

$errormsg = array();
$ret['status'] = 0;

function isValidEmailAddress($email) {
    global $errormsg; 
    list($userName, $mailDomain) = split("@", $email);
    if ($mailDomain != "" && $userName !="") {
        if (checkdnsrr($mailDomain, "MX")) {
            return true;
        } else {    
            $errormsg[] = "Invalid email domain in email address";  
        }
    } else {   
        $errormsg[] = "Invalid email address";  
    }
    return false;
}

function isUniqueEmailAddress($email)
{
    global $mysqli;
    global $errormsg;
    $result = $mysqli->query("SELECT * from usertbl WHERE email='$email'");
    if ($result) {
        if ($result->num_rows == 0) {
            return true;
        } else {
            $errormsg[] = "$email is a registered user";   
            return false;
        }
    } else {
        $errormsg[] = "Internal Error - email";
        return false;  
    }
      
}

function sendActivationEmail($email, $firstname, $userid, $acode)
{
   
    $line3 = "     Go to http://www.biblecircle.org/activation.php?user=$email&acode=$acode to confirm and activate your account.";
    $line4 = "Activation code: $acode ";
    $message = "Hi $firstname, \n\n Thank you for signing up with Bible Circle \n\n $line3 \n\n $line4";

    // In case any of our lines are larger than 70 characters, we should use wordwrap()
    
    
    $headers = 'From: biblecircle@kosolution.net' . "\r\n" .
    'Reply-To: biblecircle@kosolution.net' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    
    mail($email, "Welcome to BibleCircle", $message, $headers);
    
}

function validicode($code) 
{
    return $code == "3350";
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_error) {
    $ret['errormsg'][] = "Internal Error: Can't connect to DB";
    echo json_encode($ret);
    exit();
}

if ( isset($_POST['email']) && isset($_POST['passwd'])) {
    $email = $_POST['email'];
    if (isValidEmailAddress($email) && isUniqueEmailAddress($email)) {
        $icode = filter_input(INPUT_POST, 'icode', FILTER_DEFAULT);
        $passwd = $_POST['passwd'];
        $hashedpw = crypt($passwd, generatemd5salt());

        if ( isset($_POST['firstname']) ) {
            $setfields .= ", firstname='" . $mysqli->real_escape_string($_POST['firstname']) . "'";
        }
        if ( isset($_POST['lastname']) ) {
            $setfields .= ", lastname='" . $mysqli->real_escape_string($_POST['lastname']) . "'";
        }
        
        if (validicode($icode))  {
            $setfields .= ", type='user'";
        }
        
        $uts = time();
        $tstamp = date('Y-m-d H:i:s', $uts);   
  
        $sql = "INSERT into usertbl set created='$tstamp', email='$email', username='$email', passwd='$hashedpw'" . $setfields;
        $result = $mysqli->query($sql);
        if ($result) {
            $ret['userid'] = $mysqli->insert_id;
            $ret['sql'] = $sql;
            if (validicode($icode)) {
                $ret['status'] = 2;
            } else {
                $ret['status'] = 1;
                $ret['actcode'] = getactivationcode($email, "$uts");
                sendActivationEmail($email, $_POST['firstname'], $ret['userid'], $ret['actcode']);
            }
            
        } else {
            $ret['status'] = 0;
            $errormsg[] = $mysqli->error . $sql;
        }
        
    } 
} else {
    $ret['status'] = 0;
    $errormsg[] = "Empty email or password";
}

$ret['errormsg'] = $errormsg;
echo json_encode($ret);
