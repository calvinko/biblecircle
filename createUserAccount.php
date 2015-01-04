<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once "authutil.php";

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
            return false;

        }
    } else {
        $errormsg[] = "Invalid email address";
        return false;
    }
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
   
    $line3 = "     Go to https://kosolution.net/BibleApp/activateUserAccount.php?user=$email&acode=$acode to confirm and activate your account.";
    $line4 = "Activation code: $acode ";
    $message = "Hi $firstname, \n\n Thank you for signing up with Bible Circle \n\n $line3 \n\n $line4";

    // In case any of our lines are larger than 70 characters, we should use wordwrap()
    
    
    $headers = 'From: biblecircle@kosolution.net' . "\r\n" .
    'Reply-To: biblecircle@kosolution.net' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    
    mail($email, "Welcome to BibleCircle", $message, $headers);
    
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
    
        $passwd = $_POST['passwd'];
        $hashedpw = crypt($passwd, generatemd5salt());

        if ( isset($_POST['firstname']) ) {
            $setfields .= ", firstname='" . $mysqli->real_escape_string($_POST['firstname']) . "'";
        }
        if ( isset($_POST['lastname']) ) {
            $setfields .= ", lastname='" . $mysqli->real_escape_string($_POST['lastname']) . "'";
        }
        
        $uts = time();
        $tstamp = date('Y-m-d H:i:s', $uts);
        

        $sql = "INSERT into usertbl set created='$tstamp', email='$email', username='$email', passwd='$hashedpw'" . $setfields;
        $result = $mysqli->query($sql);
        if ($result) {
            $ret['userid'] = $mysqli->insert_id;
            $ret['sql'] = $sql;
            $ret['status'] = 1; 
            $ret['actcode'] = getactivationcode($email, "$uts");
            //sendActivationEmail($email, $_POST['firstname'], $ret['userid'], $ret['actcode']);
        } else {
            $ret['status'] = 0;
            $errormsg[] = $mysqli->error . $sql;
        }
    } 
} else {
    $errormsg[] = "Empty email or password";
}

$ret['errormsg'] = $errormsg;
echo json_encode($ret);

?>


