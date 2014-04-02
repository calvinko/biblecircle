
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
        $uname = $userprofile['firstname'] . ' ' . $userprofile['lastname'];
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
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css" rel="stylesheet" >
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link href="css/bcstyle.css" rel="stylesheet">
    
    <script src="//code.jquery.com/jquery.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <style>
            
        body {
            padding-top: 52px;
            padding-bottom: 30px;      
        }      

        .biblepanel-silver {
            border: 0px silver solid;
            border-radius: 10px;
            background: url(/images/darkbluesilver-bg.jpg);
            
            padding: 25px 12px 12px 12px;
            
            height: 816px;
        }
        
        .panel-bible {
            height: 740px;
            
            background-image: linear-gradient(to bottom, #f8f8f8 0%, #fefefe 100%);
            background-repeat: repeat-x;
            border: 1px solid #aaaaaa;
            
            bbox-shadow: 0 0 6px 6px #D4AF37;  
            margin-left: auto;
            margin-right: auto;
            padding: 0px;
        }
        
        .panel-body-round {
            background-color: whitesmoke;
            opacity: 1.0;
            border: 0px black solid;
            border-radius: 8px;
        }
        
        .panel-status {
            -webkit-border-radius: 3px;
            border-radius: 3px;
            -webkit-transition: left .5s cubic-bezier(0.250,0.460,0.450,0.940),width .4s ease,padding .4s ease,box-shadow .4s ease,margin .4s ease,border .4s ease;
            transition: left .5s cubic-bezier(0.250,0.460,0.450,0.940),width .4s ease,padding .4s ease,box-shadow .4s ease,margin .4s ease,border .4s ease;
            background-color: #f5f5f5;
            border: 1px solid #d8d8d8;
            border-bottom-width: 2px;
            left: 0;
            margin: 10px 0 20px 0;
            padding: 20px;
            position: relative;
            z-index: 2;
        }
        
        .panel-heading-sm {
            padding: 5px 10px;
        }
        
        h3, h4, h5 {
            border: 0 solid #efefef;
            border-bottom-width: 1px;
            padding-bottom: 10px;
        }
        
        .box {
            background-color: #FFFFFF;
            -moz-border-radius: 1px 1px 1px 1px;
            -webkit-border-radius: 1px 1px 1px 1px;
            border-radius: 1px 1px 1px 1px;
            box-shadow: 0 0 0 1px rgba(0,0,0,0.05);
        }
        
        .widget-head {
            font-size: 15px;
            font-weight: bold;
            line-height: 50px;
            margin: 0;
            text-align: center;
            text-transform: uppercase;
            box-shadow: 0 1px 0px 0px rgba(0,0,0,0.10);
            color: #eb7a77;
        
        }
            
        .widget-head {
            background: #ffffff;
        }
        
    </style>
    <script>
        var userid = <?php echo $userid ?>;
        
        function biblestore() {
            this.getChapter = function(version, booknum, chapter) {
                
            };
            
            this.fetchChapter = function(version, booknum, chapter) {
                if (version === "CUV" || version === "KJV") {
                    $.getJSON("bible/" + version + "/", {book: booknum, chapter: chapter}, function() {
                        
                    });
                } else if (version === "NET") {
                    $.getJSON("http://labs.bible.org/api/?callback=?", {passage: "John 2", type: "json"}, 
                        function(robj) {
                            $.each(robj, function(index, rec) {
                                $("#singleviewpane .textbox").append(rec.text);
                            })
                        }
                    );
                }
            } 
           
        };
        
        $(function() {      
            if (userid !== 0) {
                $("#nav-setting, #nav-user").removeClass("hide");
            } else {
                $("#nav-login, #nav-signup").removeClass("hide");
            }
            var bstore = new biblestore();
            bstore.fetchChapter("NET", 40, 2);
        });
        
        
        var bibleReader = {
            version: 'KJV',
            activerange: [40, 66],
            plantitle: "Whole Bible",
            scope: "WholeBible",
            book: 1,
            chapter: 1,
            startdate: "2014-01-01",
            planinstid: <?php echo $instid; ?>,
            texttable: new Array(66),
            statustable: new Array(66),
            totalchapter: 0,
            donechapter: 0,

            getcurrbookname: function() {
                return biblebooks[this.chapter];
            },
            setversion: function(version) {
                this.version = version;
            },
                                        
                fetchchapter: function(bk, ch){
                    if (this.version === "CUV" || this.version === "KJV") {
                        $.post("getBibleText.php", {version: this.version, book: bk, chapter: ch}, function(data) {   
                            var ret = $.parseJSON(data);
                            var rows = ret.rows;
                        }); 
                    }
                },
                
            displaychaptersv: function(bkname, chapter, rows, status) {  /* 1 chapter, rows - list of verses */
        
                $("#singleviewpane .textbox").empty();
                $("#singleviewpane .bookname span").text(bkname + " " + chapter);
                $.each(rows, function(i, row){
                    if (row.verse == 1) {
                        $("#singleviewpane .textbox").append("<h4>" + bkname + " " + chapter + "</h4>");
                    }
                    $("#singleviewpane .textbox").append("<p>" + row.verse + ". " + row.text + "</p>");
                })
                if (userid === 0) {
                    $("#singleviewpane .textbox").append("<br/><a href='login.php'>Login</a> or <a href='login.php?tab=signup'>Sign up</a> to track reading");
                } else if (this.planinstid <= 1) {

                } else if (this.planistid !== 0 && this.planinstid !== 1) {
                    var donebox = $("<label class='checkbox'><input type='checkbox' name='donebox'>Mark as read</label>");
                    if (status === '1') {
                        donebox.find("input").attr("checked", "checked");
                    }
                    donebox.find("input").click(function() {
                        bibleReader.toggledonebox($(this));
                    });
                    $("#singleviewpane .textbox").append(donebox);
                }
            },
                showstatuspanel: function() {
                    $("#singleviewpane .textbox").hide();
                    var percentage = Math.round(this.donechapter * 100 / this.totalchapter);
                    $("#status-progress .complete").html(percentage + "% Completed (" + this.donechapter + "/" + this.totalchapter + ")");
                    $("#status-progress .progress-bar").attr("aria-valuenow", ' ' + percentage);
                    $("#status-progress .progress-bar").css("width", percentage + '%');
                    
                    
                    
                    $("#singleviewpane .statuspanel").show();
                    $("#singleviewpane .statuspanel span.startdate").text(bibleReader.startdate);
                    $("#singleviewpane .statuspanel .plangoal").text(bibleReader.plantitle);
                    
                    if ( ! $("#singleviewpane .statuspanel .msheet").length ) {
                        $("#singleviewpane .statuspanel .status").empty();
                        $.each(bibleReader.statustable, function(index, bkrows) {
                            /* statistable is a associate array, index is the key, which is string, not 1,2,3,4 */
                            var bname = biblebooklist[index][0];
                            var ul = $("<ul class='brow'></ul>");
                            $.each(bkrows, function(ind, row) {
                                var i = ind + 1;
                                var li = $("<li class='chbox' data-toggle='tooltip' data-title='Click to Mark; Double Click jump to Chapter'>" + i + "</li>");
                                if (row.status === '1') {
                                    li.addClass("boxchecked");
                                }
                                li.click(function() {
                                    var box = $(this);
                                    bibleReader.togglestatusdonebox(box, index, i);
                                       
                                });
                                li.dblclick(function() {
                                    bibleReader.switchchapter(index, i);
                                    $("#btn-status").removeClass("active");
                                    bibleReader.hidestatuspanel();

                                });
                                ul.append(li);

                           });

                           var div = $("<div style='margin: 5px 0px 0px 2px' class='row msheet'></div>");
                           div.append("<div style='padding-left: 5px; padding-right: 5px' class='col-md-2 booktitle'>" + bname + "</div>");
                           div.append($("<div class='col-md-10'></div>").append(ul));
                           $(".chbox").tooltip({delay: 1000});
                           $("#singleviewpane .statuspanel .status").append(div);
                        });
                    };
                    
                },
                hidestatuspanel: function() {
                    $("#singleviewpane .textbox").show();
                    $("#singleviewpane .statuspanel").hide();
                },
                loadchapter: function(bk, ch) {
                    var myplanid = this.planinstid;
                    
                    if (this.version === "CUV" || this.version === "KJV") {
                        $.post("getBibleText.php", {version: this.version, book: bk, chapter: ch}, function(data) {   
                            var ret = $.parseJSON(data);
                            var rows = ret.rows;
                            if (myplanid !== 0 && myplanid !== 1) {
                                $.post("getPlanInstData.php", {instid: this.planinstid, day: bk}, function(data) {
                                    var iret = $.parseJSON(data);
                                    var irows = iret.rows;
                                    dtable = new Array();
                                    $.each(irows, function(i, elm) {
                                        dtable.push(elm);
                                    });
                                    var status = dtable[ch-1].status;
                                    //showpassage2(rows, ret.title, status);
                                    bibleReader.displaychaptersv(ret.bookname, ch, rows, status);

                                });
                            } else {
                                bibleReader.displaychaptersv(ret.bookname, ch, rows, 0);
                                $("#bibleleftpage .textbox").empty();
                                $("#biblerightpage .textbox").empty();
                                
                                
                                var cpage = 0;
                                $.each(rows, function(index, row) {
                                    if (cpage === 0) {
                                        if (row.verse === "1") {
                                            $("#bibleleftpage .textbox").append("<h4>" + biblebooklist[bk][0] + " " + row.chapter + "</h4>");
                                        }
                                        $("#bibleleftpage .textbox").append("<p>" + row.verse + ". " + row.text + "</p>");
                                        if ($("#bibleleftpage .textbox").height() > 700) {
                                            cpage = 1;
                                            $("#bibleleftpage .textbox p:last-child").remove();
                                            $("#biblerightpage .textbox").append("<p>" + row.verse + ". " + row.text + "</p>");
                                        }
                                    } else {
                                        if (row.verse === "1") {
                                            $("#biblerightpage .textbox").append("<h4>" + biblebooklist[bk][0] + " " + row.chapter + "</h4>");
                                        }
                                        $("#biblerightpage .textbox").append("<p>" + row.verse + ". " + row.text + "</p>");
                                    }
                                });
                            }
                        });
                    } else {
                        var pdata = biblebooklist[bk][0] + "+" + ch;
                        $.post("getBibleTextInet.php", {passage: pdata, version: this.version}, function(data) {
                            var text = $.parseJSON(data);
                            $("#singleviewpane .textbox").empty();
                            $("#singleviewpane .textbox").append(text);
                            //var donebox = $("<div>Done <input type='checkbox' name='donebox'></div>");
                            //
                            //$("#bibletextbox").append(donebox);
                        });
                    }
                },
                switchchapter: function(bnum, ch) {
                    this.book = bnum;
                    this.chapter = ch;
                    this.loadchapter(bnum, ch);
                    var bookname = biblebooklist[bnum][0];
                    $("#bibleindex input").val(bookname + " " + ch);         
                },
                loadcurrentchapter: function () {
                    this.loadchapter(this.book, this.chapter);
                },
                loadnextchapter: function () {
                    if (this.chapter < biblebooklist[this.book][1]) {
                        this.chapter = this.chapter + 1;
                        this.switchchapter(this.book, this.chapter);
                    } else {
                        if (this.book < 66) {
                            this.book += 1;
                            this.chapter = 1;
                            this.switchchapter(this.book, this.chapter);
                        }
                    }
                },
                loadprevchapter: function () {
                    this.chapter -= 1;
                    if (this.chapter <= 0) {
                        if (this.book > 1) {
                            this.book -= 1;
                            this.chapter = biblebooklist[this.book][1];
                            this.switchchapter(this.book, this.chapter);
                        }
                    } else {
                        this.switchchapter(this.book, this.chapter);
                    }
                },
                loadprevbook: function () {
                    if (this.book > 1) {
                        this.book -= 1;
                        this.chapter = 1;
                        this.switchchapter(this.book, this.chapter);
                    }
                },
                loadnextbook: function () {
                    if (this.book < 66) {
                        this.book += 1;
                        this.chapter = 1;
                        this.switchchapter(this.book, this.chapter);
                    }
                },
                toggledonebox: function(thisbox) {
                    
                    if (thisbox.attr("checked") !== "checked") {
                      
                        $.post("updateUserPlanReadLog.php", {instid: this.planinstid, day: this.book, section: this.chapter, status: 1}, function(data) {
                                if (data === '1') {
                                thisbox.attr("checked", "checked");
                            } else {
                                thisbox.attr("checked", "");
                            }
                        });
                                    
                    } else {
                        
                        $.post("updateUserPlanReadLog.php", {instid: this.planinstid, day: this.book, section: this.chapter, status: 0}, function(data) {
                            if (data === '1') {
                                thisbox.attr("checked", "checked");
                            } else {
                                thisbox.attr("checked", "");
                            }
                        });
                    }
                },
                togglestatusdonebox: function(box, day, section) {  /* day-book, section-chapter 8*/
                    if (box.hasClass("boxchecked")) {
                        box.removeClass("boxchecked");
                        val = "0";
                    } else {
                        val = "1";
                    }
                    $.post("updateUserPlanReadLog.php", {instid: bibleReader.planinstid, day: day, section: section, status: val}, function(data) {
                        if (data === '1') {
                            box.addClass("boxchecked");
                        } else {
                            box.removeClass("boxchecked");
                        }
                    });
                }
            }    
    </script>
        
</head>

<body class="bg-metallic-plate">
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
                            <li id="nav-app" class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-th"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class=""><a href="http://kosolution.net/SongApp"><i class="fa fa-music"></i>&nbsp Song App</a></li>
                                    <li class=""><a href="http://kosolution.net/SongApp"><i class="fa fa-music"></i>&nbsp Song Mgmt</a></li>
                                    <li class=""><a href="shoedrive.php"><i class="fa fa-rocket"></i>&nbsp Shoe Drive</a></li>
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

                            <li id="nav-user" class="hide dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-user"></i> 
                                            <?php echo $uname; ?>
                                            <b class="caret"></b>
                                    </a>

                                    <ul class="dropdown-menu">
                                            <li><a href="profile.php"><i class="fa fa-suitcase"></i>&nbsp Profile</a></li>
                                            <li class="divider"></li>
                                            <li><a href="logout.php"><i class="fa fa-sign-out"></i>&nbsp Sign Out</a></li>
                                    </ul>
                            </li>
                            
                            <li id="nav-signup" class="hide"><a href="http://www.biblecircle.org/signin.php?tab=signup" class="ktooltip" data-toggle="tooltip" title="Sign up to track Reading">Sign Up</a></li>
                            <li id="nav-login" class="hide"><a href="http://www.biblecircle.org/signin.php" class="ktooltip" data-toggle="tooltip" title="Sign In">Sign In</a></li>
                        </ul> 
                    </div><!--/.navbar-collapse -->	

                </div>

            
            
            </div>
        </header>
    
    <div class="container">
        <div style="padding-top: 7px" class="row">
            <div style="font-size: 13px; line-height: 15px; float: left" id="singleviewpane" class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="panel panel-default panel-bible">
                        <div class="panel-body">
                            <div style="overflow-y: scroll;" class="textbox"></div>

                        </div>                            
                    </div>
            </div>
            <div class="col-md-4 col-lg-4">
                <div class="panel">
                    <div class="panel-heading">
                        <a href="#" style="font-size: 75%" class="pull-right">View all <b class="caret"></b></a>
                        <h5>
                            <i class="fa fa-circle-o"></i>
                            <span>Your Bible Circles</span>
                        </h5>
                    </div>
                        <div style="height: 300px;" class="panel-body">
                            
                        </div>  
                    <div class="panel-heading panel-heading-sm">
                        <div style="padding: 2px 4px;" class="panel-title">
                            <i class="fa fa-circle-o"></i>
                            <span>Your Notes</span>
                        </div>
                    </div>
                        <div style="height: 400px" class="panel-body">
                            <h3>Your Stuff</h3>
                        </div>  
                    </div>
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

