<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$durl = "http://www.biblecircle.org";
?>

<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Bible Circle Sign in</title>
        <!-- Latest compiled and minified CSS -->

        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

        <style>
            .overlay{
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 10;
                background-color: rgba(0,0,0,0.5); /*dim the background*/
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
            
            $("#btn-login").click(function(e) {
                e.preventDefault();
                $.post("bcsignin.php", 
                        {   username:   $("#login-username").val(), 
                            passwd:     $("#login-password").val(), 
                            remember:   $("#login-remember").is(':checked') ? 1 : 0
                        }, 
                        function(retdata) {
                            var retobj = $.parseJSON(retdata);
                            alert(retdata);
                            if (retobj.status === 1)
                                window.location = "<?php echo $durl ?>";
                            else {
                                $('#login-alert').text(retobj.errormsg);
                                $('#login-alert').show();

                            } 
                        }
                 )
            })
            
            $("#btn-fblogin").click(function (e) {
                $("#login-overlay").removeClass("hide");
                e.preventDefault();
                FB.getLoginStatus(function(response) {
                    if (response.status === 'connected') {
                        fbuid = response.authResponse.userID;
                        $.post("fbsignin.php", {fbuid: fbuid}, function(retdata) {
                            //alert(retdata);
                            window.location = "http://www.biblecircle.org";
                        })
                    } else {
                        FB.login(function(response) {
                            if (response.authResponse) {
                                fbuid = response.authResponse.userID;
                                $.post("fbsignin.php", {fbuid: fbuid}, function(retdata) {
                                    //alert(retdata);
                                    window.location = "http://www.biblecircle.org";
                                })
                            } else {
                                $("#login-overlay").addClass("hide");
                                $("#login-alert").html("<p>Facebook login canceled</p>");
                                $("#login-alert").show();
                            }
                        });
                    }
                });
            })     
        });
        
        function signinCallback(authResult) {
          if (authResult['status']['signed_in']) {
            // Update the app to reflect a signed in user
            // Hide the sign-in button now that the user is authorized, for example:
            //document.getElementById('signinButton').setAttribute('style', 'display: none');
            alert(authResult);
            //$.get("https://www.googleapis.com/plus/v1/people/", {});
          } else {
            // Update the app to reflect a signed out user
            // Possible error values:
            //   "user_signed_out" - User is signed-out
            //   "access_denied" - User denied access to your app
            //   "immediate_failed" - Could not automatically log in the user
            console.log('Sign-in state: ' + authResult['error']);
          }
        }
    
    </script>
        <div class="container">

            <div class="row">
                <div id="loginbox" style="margin-top:50px; width: 400px" class="container mainbox">                    
                    <div class="panel panel-info" >
                        <div class="panel-heading">
                            <div class="panel-title">Bible Circle Sign In</div>
                            <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>
                        </div>     

                        <div style="padding-top:30px; padding-left: 15px; padding-right: 15px" class="panel-body" >

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
                                        <a id="btn-login" href="#" style="width: 80px" class="btn btn-sm btn-info">Login  </a>
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
                                    
                                    <span style="z-index: 10" id="signinButton">
                                      <span
                                        class="g-signin"
                                        data-callback="signinCallback"
                                        data-clientid="437968404257-pqvsr2f5j71v4u6ptho0tc3s7vtl3qo8.apps.googleusercontent.com"
                                        data-cookiepolicy="single_host_origin"
                                        data-requestvisibleactions=""
                                        data-scope="profile"
                                        data-width="wide">
                                      </span>
                                    </span>
                            
                                   <button  id="btn-fblogin" style="padding: 0px 10px 0px 10px; margin-top: 15px" class="btn btn-sm btn-primary">
                                        <span style="display: table-cell; font-size: 18px; padding: 0px 13px 0px 4px;float: left;border-right: 1px solid #177;">f</span>
                                        <span style="display: table-cell; font-size: 12px; padding: 5px 5px 5px 12px;">Sign in with Facebook</span>
                                   </button>
                                </div>

                                
                                </form>     
                            </div>                     
                        </div>  
                    </div>
                    
                    
                </div>
                
               
                <div id="signupbox" style="display:none; margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
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
                                    <label for="icode" class="col-md-3 control-label">Invitation Code</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="icode" placeholder="">
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
        </div> 
        
        <div id="login-overlay" style="text-align: center" class="hide overlay">
            
            <div style="display:inline-block; margin: 220px auto">
                <img src="images/ajax-loader.gif" height="80px">
                <br/>
                <span style="margin-top: 30px; font-size: 20px; color: black">Logging in</span>
            </div>
            
            
        </div>
    <!-- Place this asynchronous JavaScript just before your </body> tag -->
    <script type="text/javascript">
        (function() {
            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
            po.src = 'https://apis.google.com/js/client:plusone.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
        })();
     
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