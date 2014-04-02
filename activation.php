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

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (isset($_GET['acode']) && isset($_GET['user'])) {
    $acode = $_GET['acode'];
    $email = $_GET['user'];

    $result = $mysqli->query("SELECT UNIX_TIMESTAMP(created),userid,type from usertbl WHERE email='$email'");
    if ($result) {
        $row = $result->fetch_row();
        $ts = $row[0];
        $userid = $row[1];
        if (checkactivationcode($email, "$ts", $acode)) {
            if ($row[2] == "waitactivation") {
                $mysqli->query("UPDATE usertbl SET type='user' where userid=$userid");
                $status = "success";
            } elseif ($row[2] == "user") {
                $status = "already";
            }
            
        } else {
            $errormsg = "Error: Invalid activation code";
            $status = "entercode";
        }
    } else {
        $errormsg =  "Internal Error";
       
    }
} else {
    $astatus = Authenticate::validateAuthCookie();
    if ($astatus) {
        $userid = Authenticate::getUserId();
        $p = getUserProfile($userid);
        if ($p['type'] == 'user') {
            header("location: http://www.biblecircle.org");
        };
        $email = $p['email'];
    } else {
        $userid = 0;
    }
    $status = "entercode";
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Bible Circle</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css" rel="stylesheet" >
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <link href="css/bcstyle.css" rel="stylesheet">

        <script src="//code.jquery.com/jquery.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <style>
            .kbox {
                margin-top:50px; 
                height:300px
            }
            
        </style>
        <script> 
            $(function() {
               
                $('#activation-modal').modal();
                $('#act-submit').click(function() {
                    var code = $("#activation-modal input[name='acode']").val();
                    window.open('activateUserAccount.php?user=<? echo $username ?>&code=' + code, "_self");
                })
                $('#act-cancel').click(function() {
                    window.open('logout.php');
                })
            })
        </script>
        
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div id="welcomebox" class="kbox col-md-offset-2 col-md-8 <?php if ($status == "entercode") { echo "hide"; } ?>">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Bible Circle</div>
                        </div>
                        <div class="panel-body">
                            <?php 
                                if ($status == "success") {
                                    echo '<p>Activation Successful - <a href="http://www.biblecircle.org/signin.php">Login Here</a></p>';
                                } elseif ($status == "already")  {
                                    echo '<p>Account already activated - <a href="http://www.biblecircle.org/signin.php">Login Here</a></p>';
                                } else {
                                    echo "<p>Activation failure - $errormsg </p>";
                                }
                            ?>
                        </div>
                    </div>
                </div>
                
                <div id="activationbox" class="kbox col-md-offset-2 col-md-8 <?php if ($status != "entercode") { echo "hide"; } ?>">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Bible Circle - Accoun Activation</div>
                        </div>
                        <div class="panel-body">
                            <p>Enter activation code to activate your BibleCircle account. The activation code was sent to your email after you register.</p>
                            <br/>
                            <form class="form-horizontal" role="form">
                            <div id = "emailfield" style="margin: 15px 5px" class="form-group">
                                
                                <span class="col-md-3">Your email:</span> <span class="col-md-6"><input <?php if ($userid != 0) { echo " disabled='disabled' value='$email' "; } ?> name="email" type="text" size="30"/></span>
                            </div>
                            <div style="margin: 15px 5px"  class="form-group">
                                <span class="col-md-3">Activation code:</span> <span class="col-md-6"><input name="acode" type="text" size="30"/></span>
                            </div>
                            
                            <div style="margin: 15px 5px"  class="form-group">
                                <span class="col-md-3"></span>
                                <span class="col-md-6">
                                    <button type="button" id="btn-submit" class="btn btn-primary">Submit</button>
                                    <button style="margin-left: 10px" type="button" id="btn-resend" class="btn btn-default">Resend Activation Code</button>
                                </span>
                            </div>
                                </form>
                        </div>
                    </div>
                </div>
 
            </div>
        </div>
                    
               
    </body>
</html>