
<?php
    require_once("authutil.php");
    require_once("bcutil.php");
    
    $userid = 0;
    $instid = 1;
    $userprofile = array();
    $astatus = Authenticate::validateAuthCookie();
    if ($astatus) {
        $userid = Authenticate::getUserId();
    } 
    
    $todaystr = date("Y-m-d");
    
    if ($userid != 0) {
        $userprofile = getUserProfile($userid);
        $usertype = $userprofile['type'];
        $username = $userprofile['username'];
        if ($usertype == 'user') {
            $instid = getUserCurrentPlan($userid);
        } else {
            $instid = 1;
        }
    } else {
        $usertype = 'null';
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bible Circle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap-theme.min.css" rel="stylesheet" >
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

    <script src="//code.jquery.com/jquery.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    <style>
            
        body {
            padding-top: 52px;
            padding-bottom: 30px;      
        }      

        .bg-metallic-plate {
            background: -webkit-radial-gradient(center, circle farthest-corner, rgba(255,255,255,0) 50%, rgba(200,200,200,1)), -webkit-radial-gradient(center, circle, rgba(255,255,255,.35), rgba(255,255,255,0) 20%, rgba(255,255,255,0) 21%), -webkit-radial-gradient(center, circle, rgba(0,0,0,.2), rgba(0,0,0,0) 20%, rgba(0,0,0,0) 21%), -webkit-radial-gradient(center, circle farthest-corner, #f0f0f0, #c0c0c0);
            background-size: 100% 100%, 10px 10px, 10px 10px, 100% 100%;
            background-repeat: no-repeat, repeat, repeat, no-repeat;
            background-position: top center, 1px 1px, 0px 0px, top center;
        }
        
        .bg-pink-circle {
            background: url(/images/pinkcircle-bg.jpg);
        }
            
        .bg-metal {
            background: url(/images/metallic-1.jpg);
        }
        
        .bg-bluesilver {
            background: url(/images/darkbluesilver-bg.jpg);
            
        }
        .metal {
            border-image: url(/images/darkbluesilver-bg.jpg) 30 30 round;
            border-width: 15px;
            border-radius: 10px;
        }
        
        .biblepanel {
            border: 0px silver solid;
            border-radius: 10px;
            background: url(/images/darkbluesilver-bg.jpg);
            
            padding: 25px 12px 12px 12px;
            width: 680px;
            height: 816px;
        }
        
        .bible1 {
            background-color: whitesmoke;
            opacity: 1.0;
            border: 0px black solid;
            border-radius: 8px;
        }
    </style>
    <script>
        var userid = <?php echo $userid ?>;
        $(function() {      
            if (userid !== 0) {
                $("#nav-setting, #nav-user").removeClass("hide");
            } else {
                $("#nav-login, #nav-signup").removeClass("hide");
            }
        })
    </script>
        
</head>

<body class="bg-pink-circle">
    <header>
        <div style="background-color: whitesmoke" class="navbar navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                    </button>
                        <a class="navbar-brand" href="index.php"><i style="color:orangered" class="ko-icon-bible"></i>&nbsp; Bible Circle</a>
                    </div>

                    <div class="collapse navbar-collapse">                          
                        <ul class="nav navbar-nav">
                            
          
                            <li class="hide">
                                <a id="bs-but" class="ktooltip" rel="tooltip" data-toggle="tooltip" title="Toggle BookShelf" href="#">
                                    
                                    <img style="position:relative; top: 50%; width: 22px;" alt="shelve" class="img-rounded brightness" src="//kosolution.net/images/icon-bookshelf-24.png"/>
                                </a>
                            </li>
                            <li>
                                <a id="plan-but" href="#">
                                    <i class="glyphicon glyphicon-calendar"></i>
                                </a>
                            </li>
                            <li>
                                <a id="track-but" href="#">
                                    <i class="glyphicon glyphicon-bullhorn"></i>
                                </a>
                            </li>
                        </ul>                                           
                        <ul class="nav navbar-nav navbar-right">  
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

                            <li id="nav-user" class="hide dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="icon-user"></i> 
                                            Calvin Ko
                                            <b class="caret"></b>
                                    </a>

                                    <ul class="dropdown-menu">
                                            <li><a href="profile.php"><i class="icon-user"></i>&nbsp Profile</a></li>
                                            <li class="divider"></li>
                                            <li><a href="logout.php"><i class="icon-signout"></i>&nbsp Logout</a></li>
                                    </ul>
                            </li>
                            
                            <li id="nav-signup" class="hide"><a href="http://www.biblecircle.org/signin.php?tab=signup" class="ktooltip" data-toggle="tooltip" title="Sign up to track Reading">Sign Up</a></li>
                            <li id="nav-login" class="hide"><a href="http://www.biblecircle.org/signin.php" class="ktooltip" data-toggle="tooltip" title="Login">Login</a></li>
                        </ul> 
                    </div><!--/.navbar-collapse -->	

                </div>

            
            
            </div>
        </header>
    
    <div style="font-size: 13px; line-height: 15px; float: left" id="singleviewpane" class="col-md-12">
            <div class=" biblepanel panel panel-default">
                <div style="width: 100%; height: 100%"  class="panel-body bible1">
               
                    <div style="overflow-y: scroll;" class="textbox"></div>
           
                </div>                            
            </div>
        </div>
    <!-- Place this asynchronous JavaScript just before your </body> tag -->
    <script type="text/javascript">
      (function() {
       var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
       po.src = 'https://apis.google.com/js/client:plusone.js';
       var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
     })();
    </script>    
</body>
</html>

