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


$durl = "//www.biblecircle.org";

if (filter_has_var(INPUT_GET, 'tab')) {
    $tab = filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_STRING);
} else {
    $tab = 'login';
}

if (filter_has_var(INPUT_GET, 'dpage')) {
    $dpage = filter_input(INPUT_GET, $tab, FILTER_SANITIZE_STRING);
}

 
 if ($dpage == "songmgmt") {
     $durl = "/BibleApp/songmgmt.php";
 } elseif ($dpage == "shoe") {
     $durl = "shoedrive.php";
 }

?>

<!DOCTYPE html>
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Bible Circle Sign in</title>
        <!-- Latest compiled and minified CSS -->

        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

        <style>
            .overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 10;
                background-color: rgba(0,0,0,0.5); /*dim the background*/
            }
            
            .btn-google {
                background-color: #dd4b39; 
                color: #fff;
                box-shadow: 0 1px 0 rgba(0,0,0,0.10);
            }
            
        </style>

    </head>
    
   
    <body>
        <div id="fb-root"></div>
        <script>
            
          var fbloginstatus = ""; 
          var fbuid;
          // Additional JS functions here
          window.fbAsyncInit = function() {
              FB.init({
                      appId      : '72255585723', // App ID
                      status     : true, // check login status
                      cookie     : true, // enable cookies to allow the server to access the session
                      xfbml      : true  // parse XFBML
              });
              $("btn-fblogin").removeClass("disabled");
          };

          // Load the SDK Asynchronously
          (function(d){
             var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
             if (d.getElementById(id)) {return;}
             js = d.createElement('script'); js.id = id; js.async = true;
             js.src = "//connect.facebook.net/en_US/all.js";
             ref.parentNode.insertBefore(js, ref);
           }(document));
        </script>
        <script>
        
        $(function() {
            
            
            $('#login-passwd').keypress(function(e) {
                if (e.which === '13') {
                    //submitLogin();
                    alert("return");
                }
                alert("press ret");
                e.preventDefault();
                return false;
            });
            
            $("#btn-login").click(function(e) {
                
                var username = $("#login-username").val();
                if (username === "") {
                    $('#login-alert').text("Invalid Username");
                    $('#login-alert').show();
                } else {
                    $("#login-overlay").removeClass("hide");
                    $.post("bcsignin.php", 
                            {   username:   $("#login-username").val(), 
                                passwd:     $("#login-password").val(), 
                                remember:   $("#login-remember").is(':checked') ? 1 : 0
                            }, 
                            function(retdata) {
                                var retobj = $.parseJSON(retdata);
                                console.log(retdata);
                                if (retobj.status === 1) {
                                    
                                    window.location = "/";
                                } else {
                                    $("#login-overlay").addClass("hide");
                                    $('#login-alert').text(retobj.errormsg);
                                    $('#login-alert').show();

                                } 
                            }
                     )
                }
                 return false;
            })
            
            $("#btn-fblogin").click(function (e) {
               
                $("#login-overlay").removeClass("hide");
                e.preventDefault();
                
               
                  
                FB.getLoginStatus(function(response) {
                    if (response.status === 'connected') {
                        fbuid = response.authResponse.userID;
                        $.post("fbsignin.php", {fbuid: fbuid}, function(retdata) {
                            var retobj = $.parseJSON(retdata);
                            console.log(retdata)
                            if (retobj.status === "failure") {
                                $("#login-overlay").addClass("hide");
                                $("#login-alert").html("<p>Facebook login failure</p>");
                                $("#login-alert").show();
                            } else {
                                window.location = "//www.biblecircle.org";
                            } 
                            
                        })
                    } else {
                        FB.login(function(response) {
                            if (response.authResponse) {
                                fbuid = response.authResponse.userID;
                                $.post("fbsignin.php", 
                                    {   fbuid: fbuid,
                                        remember:   $("#login-remember").is(':checked') ? 1 : 0
                                    }, 
                                    function(retdata) {
                                         var retobj = $.parseJSON(retdata);
                                        console.log(retdata)
                                        if (retobj.status === "failure") {
                                            $("#login-overlay").addClass("hide");
                                            $("#login-alert").html("<p>Facebook login failure</p>");
                                            $("#login-alert").show();
                                        } else {
                                            window.location = "//www.biblecircle.org";
                                        }
                                    }
                               )
                            } else {
                                $("#login-overlay").addClass("hide");
                                $("#login-alert").html("<p>Facebook login canceled</p>");
                                $("#login-alert").show();
                            }
                        }, {scope: 'email'});
                    }
                });
            })     
            
            $("#btn-signup").click(function (e) {
                    
                    var errorul = $("<ul></ul>");
                    if ($("#signupform input[name='email'").val() === "") {
                        errorul.append("<li>Empty email address</li>") 
                    };
                    if ($("#signupform input[name='passwd'").val() === "") {
                        errorul.append("<li>Empty password</li>"); 
                    } else if ($("#signupform input[name='passwd'").val() !== $("#signupform input[name='passwd1'").val()) {
                        errorul.append("<li>Password not match</li>") ;
                    };
                    if (errorul.find("li").length !== 0) {
                        $("#signupalert span").html(errorul)
                        $("#signupalert").show();
                    } else {
                        //$('#btn-signup, #btn-fbsignup').attr("disabled", "disabled");
                        //$(this).find("i").addClass("icon-spinner");
                        var params = $("#signupform").serialize();
                        $("#login-overlay").removeClass("hide");
                        $.post("bcsignup.php", params, function(retdata) {
                            var retobj = $.parseJSON(retdata);
                            console.log(retdata)
                            if (retobj.status === 1) {
                                $("#login-overlay").addClass("hide");
                                $("#signupbox").hide();
                                $("#signupalert").hide();
                                //$("#welcomebox div.panel-body").append("<p>userid: " + retobj.userid + "</p>");
                                //$("#welcomebox div.panel-body").append(retobj.sql)
                                //$("#welcomebox span.actcode").append(retobj.actcode);
                                $("#welcomebox").show();
                            } else if (retobj.status === 2) {
                                $("#login-overlay").addClass("hide");
                                $("#signupbox").hide();
                                $("#signupalert").hide();
                                $("#welcomebox-2").show(); 
                            } else {
                                $("#login-overlay").addClass("hide");
                                errorul.empty();
                                errorul.append(retobj.errormsg); 
                                $("#signupalert span").html(errorul)
                                $("#signupalert").show();
                                
                            }                            
                        })
                    }
                    
                })
            
        });
        
        var gcsigningin = 0;
        function signinCallback(authResult) {
          
            if (authResult['code']) {
                // Hide the sign-in button now that the user is authorized, for example:
                // $('#signinButton').attr('style', 'display: none');
                //alert("Send code to server " + authResult['code']);
                // Send the code to the server
                if (gcsigningin === 0) {
                    $("#login-overlay").removeClass("hide");
                    gcsigningin = 1;    
                    $.post("gcsignin.php", {code: authResult['code']}, function(retdata) {
                        var retobj = $.parseJSON(retdata);
                        $("#login-overlay").addClass("hide");
                        if (retobj.status === "accountcreated") {
                            $("#setup-modal p.emailaddr").text(retobj.email);
                            $("#setup-modal .btn-continue").click(function() {
                                var first = $("#setup-modal input[name='firstname']").val();
                                var last = $("#setup-modal input[name='lastname']").val();
                                $.post("userapi.php", {firstname: first, lastname: last}, function(ret) {
                                    window.location = "<?php echo $durl ?>";
                                });
                            });
                            $("#setup-modal").modal();
                        } else {
                            window.location = "<?php echo $durl ?>";
                        }
                        gcsigningin = 0;
                    });
                };

            } else if (authResult['error']) {
                // There was an error.
                // Possible error codes:
                //   "access_denied" - User denied access to your app
                //   "immediate_failed" - Could not automatially log in the user
                // console.log('There was an error: ' + authResult['error']);
                $("#login-overlay").addClass("hide");
                $("#login-alert").html("<p>Cannot login using google</p>");
                $("#login-alert").show();
            }
            return false;
        }
        
    
    </script>
        <div class="container">

            <div class="row">
                <div id="welcomebox" style="display:none; margin-top:50px" class="mainbox col-md-offset-2 col-md-8">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Bible Circle</div>
                        </div>
                        <div class="panel-body">
                            <p>Thank you for signing up with Bible Circle. You will receive an email to activate your account.</p>
                            <span class="actcode"></span>
                        </div>
                    </div>
                </div>
                <div id="welcomebox-2" style="display:none; margin-top:50px" class="mainbox col-md-offset-2 col-md-8">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Bible Circle</div>
                        </div>
                        <div class="panel-body">
                            <p>You have successfully sign up for biblecircle.org</p>
                            <a href='http://biblecircle.org/signin.php<?php if ($dpage =='shoe') echo '?dpage=shoe'; ?>' >login here</a>
                        </div>
                    </div>
                </div>
                
                <div id="loginbox" style="margin-top:75px; width: 416px; <?php if ($tab != 'login') { echo 'display:none'; }?>" class="container mainbox">                    
                    <div class="panel panel-info" >
                        <div class="panel-heading">
                            <div class="panel-title">Bible Circle Sign In</div>
                            <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>
                        </div>     

                        <div style="padding: 30px 5px 10px 5px" class="panel-body" >

                            <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

                            <form id="loginform" class="col-sm-12" role="form">
                                <div class="form-group">

                                    <div class="col-sm-12 input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="login-username" type="text" class="form-control" name="username" value="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="login-password" type="password" class="form-control" name="password" placeholder="password">
                                    </div>
                                </div>    
                               
                                <div style="margin-top: 30px" class="form-group">
                                    <!-- Button -->
                                    <div class="controls">
                                        <button id="btn-login" style="width: 150px" class="btn btn-sm btn-info">Login  </button>
                                        <a id="btn-cancel" href="#" style="width: 80px" class="hide btn btn-sm btn-warning">Cancel </a>
                                        <span style="padding-left: 10px; padding-top:10px">
                                           
                                                <input id="login-remember" type="checkbox" name="remember" value="1"> 
                                                <span style="font-size: 90%">Keep me signed in</span>
                                           
                                        </span>
                                        
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div style="font-size:85%;" >
                                            Don't have an account! 
                                            <a href="#" onClick="$('#loginbox').hide(); $('#signupbox').show()">
                                                Sign Up Here
                                            </a>
                                            <span style="margin-left: 30px">- OR -</span>
                                    </div>
                                </div>
                
                                <div style="form-group">
                                    
                                   <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >   
                                       <div style="float: left">
                                       <button  id="btn-fblogin" style="padding: 0px 5px" class="btn btn-sm btn-primary">
                                           <span style="display: table-cell; padding: 9px 10px 8px 6px; border-right: 1px solid #177;"><i class="fa fa-facebook fa-lg"></i></span>
                                            <span style="display: table-cell; font-size: 12px; width:124px; padding: 5px 2px 5px 4px;">Sign in with Facebook</span>
                                       </button>
                                       </div>
                                       <div style="float: right">
                                       <button  id="btn-gclogin" style="margin-left: 8px; padding: 0px 5px" class="btn btn-sm btn-danger disabled btn-google">
                                           <span style="display: table-cell; padding: 9px 7px 8px 3px;border-right: 1px solid #a43;"><i class="fa fa-google-plus fa-lg"></i></span>
                                            <span style="display: table-cell; font-size: 12px; width:124px; padding: 5px 1px 5px 4px;">Sign in with Google</span>
                                       </button>
                                       </div>        
                                    </div>
                                </div>
                            </form>     
                            
                            
                         </div>                     
                    </div>  
                </div>  
           </div>
                
                <div id="signupbox" style="margin-top:50px; <?php if ($tab != 'signup') { echo 'display:none'; }?>" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Bible Circle Sign Up</div>
                            <div style="float:right; font-size: 85%; position: relative; top:-10px">
                                <a id="signinlink" onClick="$('#loginbox').show(); $('#signupbox').hide()" href="#">Sign In</a>
                            </div>
                        </div>  
                        <div class="panel-body" >
                            <form id="signupform" class="form-horizontal" role="form">

                                <div id="signupalert" style="display:none" class="alert alert-danger">
                                    <p>Error:</p>
                                    <span></span>
                                </div>



                                <div class="form-group">
                                    <label for="email" class="col-md-3 control-label">Email</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="email" placeholder="Email Address">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="firstname" class="col-md-3 control-label">First Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="firstname" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lastname" class="col-md-3 control-label">Last Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="lastname" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-md-3 control-label">Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" name="passwd" placeholder="Password">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password2" class="col-md-3 control-label">Verify</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" name="passwd1" placeholder="Re-enter Password">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="icode" class="col-md-3 control-label">Invitation Code</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="icode" placeholder="Invitation Code">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <!-- Button -->                                        
                                    <div class="col-md-offset-3 col-md-9">
                                        <button id="btn-signup" type="button" class="btn btn-sm btn-info" style="width:200px"><i class="glyphicon glyphicon-hand-right"></i> &nbsp Sign Up</button>
                                        
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>


        </div>
        
        <div id="login-overlay" style="text-align: center" class="hide overlay">    
            <div style="display:inline-block; margin: 220px auto">
                <div style="display: none; margin-bottom: 31px; background-color: #eee; padding: 10px 60px; border-radius: 5px;  font-size: 16px; color: black;">Signing  in</div>
                <img src="images/loading-b1.gif" height="50">
                <br/>
                
            </div>
        </div>
    
    <div id="setup-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Initial Sign Up</h4>
              </div>
              <div class="modal-body">
                  <p>We notice that it is the first time signing in with <span>Google</span>. Please update you information.</p>
                    <form id="signupform" class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="email" class="col-md-3 control-label">Email</label>
                            <div class="col-md-9">
                                <p class="form-control-static emailaddr">Your email</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-md-3 control-label">First Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="firstname" placeholder="First Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="col-md-3 control-label">Last Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="lastname" placeholder="Last Name">
                            </div>
                        </div>
                    </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-continue">Continue</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
    </div>
    
    <!-- Place this asynchronous JavaScript just before your </body> tag -->
    <script type="text/javascript">
        (function() {
            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
            po.src = 'https://apis.google.com/js/client:plusone.js?onload=render';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
        })();
        
        

   /* Executed when the APIs finish loading */
         function render() {

           // Additional params including the callback, the rest of the params will
           // come from the page-level configuration.
           var additionalParams = {
             'callback': signinCallback,
             'clientid': "437968404257-pqvsr2f5j71v4u6ptho0tc3s7vtl3qo8.apps.googleusercontent.com",
             'cookiepolicy': "single_host_origin",
             'requestvisibleactions' : "",
             'accesstype': "offline",
             'scope': "https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.me https://www.googleapis.com/auth/userinfo.email"
           };

           $("#btn-gclogin").removeClass("disabled");
           // Attach a click listener to a button to trigger the flow.
           $("#btn-gclogin").click(function(e) {
                gapi.auth.signIn(additionalParams);
                e.preventDefault();
                
           });
           
         }
     
        (function(d){
             var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
             if (d.getElementById(id)) {return;}
             js = d.createElement('script'); js.id = id; js.async = true;
             js.src = "//connect.facebook.net/en_US/all.js";
             ref.parentNode.insertBefore(js, ref);
        }(document));
        
        
     
    </script>    
    </body>
</html>  