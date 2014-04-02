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
 */

require_once("authutil.php");
require_once("bcutil.php");
    

$userprofile = array();
$astatus = Authenticate::validateAuthCookie();
if ($astatus) {
    $userid = Authenticate::getUserId();
} else {
    header("location: http://www.biblecircle.org/signin.php");    
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
            
        body {
            padding-top: 60px;
            padding-bottom: 30px;      
        }
        
        .chpasswd {
            display: none;
        }
        
    </style>
    <script>
        function fillvalue(elm, nameattr, value) {
            var e = elm.find("input[name='" + nameattr + "']");
            console.log(nameattr + " = " + e.length);
            if (e.length !== 0) {
                e.val(value);
            }
        }
        
        $(function() {
            $.getJSON("/user/me/profile", {}, function(data) {
                var items = [];
                if (data._status === "success") {
                    $("#nav-user span").text(data.firstname + " " + data.lastname);
                    if (data.passwd === "empty") {
                        var spbtn = $("<button class='btn btn-sm btn-primary'>set password</button>");
                        spbtn.click(function() {
                            $("#passwdrow").hide();
                            $("#infobox .chpasswd").show();
                            return(false);
                        });
                        $("#passwdrow div").empty().append(spbtn);
                    } else {
                        var spbtn = $("<button style='margin-left: 20px' class='btn btn-sm btn-primary'>change password</button>");
                        spbtn.click(function() {
                            $("#passwdrow").hide();
                            $("#infobox .chpasswd").show();
                            return(false);
                        })
                        $("#passwdrow div").empty().append("*******").append(spbtn);
                        
                    }
                    var uform = $("#infobox");
                    $.each( data, function( key, val ) {
                        fillvalue(uform, key, val)
                    });
                    //$("#pinfoform input[name='firstname']").val(retobj.firstname);
                }
            })
        });
    
    </script>
</head>
<body>
       <header>
        <div style="" class="navbar navbar-fixed-top navbar-inverse">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                    </button>
                    <a style="padding: 13px 15px" class="navbar-brand" href="index.php">
                        <i class="fa fa-dot-circle-o"></i>
                        <span>Bible Circle</span>
                        <span style="font-size: 12px; font-family: '21st Century', fantasy">(beta)</span>
                    </a>
                </div>

                    <div class="collapse navbar-collapse">                          
                        <ul class="nav navbar-nav">
                            
                            <li>
                                <a id="plan-but" href="index.php">
                                    <i class="glyphicon glyphicon-book"></i>
                                </a>
                            </li>
                        </ul>                                           
                        <ul class="nav navbar-nav navbar-right">  
                            <li id="nav-app" class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-th"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class=""><a href="http://kosolution.net/SongApp"><i class="fa fa-music"></i>&nbsp Song App</a></li>
                                    <li class=""><a href="http://kosolution.net/SongApp"><i class="fa fa-music"></i>&nbsp Song Mgmt</a></li>
                                    <li class="divider"></li>
                                    <li><a href="javascript:;">Help</a></li>
                                </ul>
                            </li>
                            <li id="nav-setting" class="dropdown hide">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-cog"></i>
                                    Settings
                                    <b class="caret"></b>
                                </a>

                                <ul class="dropdown-menu">
                                        <li class="disabled"><a href="#"><i id="track" class="icon-check"></i>&nbsp Track Reading</a></li>
                                        <li><a href="javascript:;"><i class="icon-list-alt"></i>&nbsp Account Settings</a></li>
                                        <li class="hide"><a href="javascript:;"><i class="icon-lock"></i>&nbsp Privacy Settings</a></li>
                                        <li class="divider"></li>
                                        <li><a href="javascript:;">Help</a></li>
                                </ul>
                            </li>

                            <li id="nav-user" class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-user"></i> 
                                            <span></span>
                                            <b class="caret"></b>
                                    </a>

                                    <ul class="dropdown-menu">
                                            <li><a href="profile.php"><i class="fa fa-suitcase"></i>&nbsp Profile</a></li>
                                            <li class="divider"></li>
                                            <li><a href="logout.php"><i class="fa fa-sign-out"></i>&nbsp Sign Out</a></li>
                                    </ul>
                            </li>
                        </ul> 
                    </div><!--/.navbar-collapse -->	

                </div>

            
            
            </div>
        </header>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                     <ul class="ba-sidenav nav nav-pills nav-stacked">
                                <li class="active" ><a href="#" data-toggle="tab" data-target="#ptab1">Personal Information</a></li>
                                <li><a href="#" data-toggle="tab" data-target="#ptab2">Login Information</a></li>
                                <li><a href="#" data-toggle="tab" data-target="#ptab3">Additional Information</a></li>
                     </ul>
                </div>
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-body">
                           
                            <div id="infobox" class="tab-content">
                                <div id="ptab1" style="height:400px;" class="tab-pane active tab-box" >
                                     <form id="pinfoform" class="form-horizontal" role="form">
                                        <div class="form-group">
                                              <label for="firstname" class="col-md-3 control-label">First Name</label>
                                              <div class="col-md-7">
                                                  <input disabled="disabled" title="First Name" type="text" class="form-control" name="firstname" value="<?php echo $firstname; ?>" placeholder="Enter your first name">
                                              </div>
                                        </div>
                                        <div class="form-group">
                                             <label for="firstname" class="col-md-3 control-label">Last Name</label>
                                              <div class="col-md-7">
                                                  <input disabled="" title="Last Name" type="text" class="form-control" name="lastname" value ="<?php echo $lastname; ?>" placeholder="Enter your last name">
                                              </div>
                                             <div class="col-md-2">
                                                 <a href="#">change</a>
                                             </div>
                                              
                                        </div>                                        
                                         
                                        <div class="form-group">
                                              <label for="facebook" class="col-md-3 control-label">Facebook</label>
                                              <div class="col-md-7">
                                                  <span></span>
                                                  <span></span>
                                              </div>
                                        </div>
                                         
                                          <div class="form-group">
                                              <label for="google" class="col-md-3 control-label">Google</label>
                                              <div class="col-md-7">
                                                  <span></span>
                                                  <span></span>
                                              </div>
                                        </div>
                                        
                                        <div id="editrow" class="form-group">
                                            <label for="fn" class="col-md-3 control-label"></label>
                                            <div class="col-md-7">
                                                <a id="profileeditbtn" href="#">edit</a>
                                            </div>
                                            
                                        </div> 
                                         
                                        <div id="saverow" style="display:none" class="form-group">
                                             <label for="fn" class="col-md-3 control-label"></label>
                                              <div class="col-md-7">
                                                  <button title="save" class="btn btn-primary">Save</button>
                                                  <button title="cancel" class="btn btn-primary">Cancel</button>
                                              </div>
                                              
                                        </div>  
                                    </form>
                                </div>   
                                <div id="ptab2" style="height:400px;" class="tab-pane tab-box">
                                    <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                              <label for="email" class="col-md-3 control-label">Username</label>
                                              <div class="col-md-7">
                                                  <input disabled="disabled" class="form-control" name="username" value="1" >
                                              </div>
                                        </div>
                                        
                                        <div class="form-group">
                                              <label for="email" class="col-md-3 control-label">Primary Email</label>
                                              <div class="col-md-7">
                                                  <input disabled="disabled"  class="form-control" name="email">
                                              </div>
                                        </div>
                                        
                                        <div class="form-group">
                                              <label for="email2" class="col-md-3 control-label">Secondary Email</label>
                                              <div class="col-md-7">
                                                  <input disabled="disabled" class="form-control" name="email2" placeholder="email adress">
                                              </div>
                                        </div>
                                    
                                        <div id="passwdrow" class="form-group">
                                              <label for="passwd" class="col-md-3 control-label">Password</label>
                                              <div class="col-md-7">
                                              </div>
                                        </div>
                                        
                                        <div class="form-group chpasswd">
                                              <label for="oldpassword" class="col-md-3 control-label">Old Password</label>
                                              <div class="col-md-7">
                                                  <input class="form-control" type="password" name="oldpasswd" placeholder="old password" >
                                              </div>
                                              <div class="col-md-2">
                                                  <span style="line-height:32px;"><a href="#">change</a><span>
                                              </div>
                                        </div>
                                        
                                        <div class="form-group chpasswd">
                                              <label for="password1" class="col-md-3 control-label">New Password</label>
                                              <div class="col-md-7">
                                                  <input class="form-control" type="password" name="passwd1" placeholder="new password" >
                                              </div>
                                              <div class="col-md-2">
                                                  <span style="line-height:32px;"><a href="#">change</a><span>
                                              </div>
                                        </div>
                                        
                                        <div class="form-group chpasswd">
                                              <label for="password" class="col-md-3 control-label">Verify</label>
                                              <div class="col-md-7">
                                                  <input class="form-control" type="password" name="passwd2" placeholder="Re-enter password" >
                                              </div>
                                              <div class="col-md-2">
                                                  
                                              </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="ptab3" style="height:300px;" class="tab-pane tab-box">
                                    <form class="form-horizontal" role="form">
                                         <div class="form-group">
                                             <label for="password" class="col-md-3 control-label">Your Profile Picture</label>
                                             <div class="col-md-7">
                                                  Picture
                                             </div>
                                         </div>
                                        <div class="form-group">
                                             <label for="password" class="col-md-3 control-label">Your Church</label>
                                             <div class="col-md-7">
                                                  Church
                                             </div>
                                         </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    
</body>
      
</html>