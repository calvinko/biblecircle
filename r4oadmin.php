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
    
/*
$userprofile = array();
$astatus = Authenticate::validateAuthCookie();
if ($astatus) {
    $userid = Authenticate::getUserId();
} else {
    header("location: https://www.biblecircle.org/signin.php");    
} 
*/     

?>

<!DOCTYPE html>
<html>
<head>
    <title>Run4Orphans Admin</title>
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
        
        var runnertable = [];
        var runnerlist = [];
        function fillvalue(elm, nameattr, value) {
            var e = elm.find("input[name='" + nameattr + "']");
            console.log(nameattr + " = " + e.length);
            if (e.length !== 0) {
                e.val(value);
            }
        }
        
        function loadrunnerlist() {
            $.getJSON("r4ogetrunnerlist.php", {}, function(obj) {
               
                $.each(obj, function(index, row) {
                    runnertable[row.name] = row;
                    runnerlist.push(row.name);
                    $("select.rlist").append("<option value='" + row.fbuid + "'>" + row.name + "</option>");
                });
            });
        }
        
        function loaddonationtable() {
            var rid = $("#drselect").val();
            $.getJSON("r4ogetdonationdata.php", {runnerid: rid}, function(obj) {
                $("#donationtable tbody").empty();
                $.each(obj, function(index, row) {
                    var tr = $("<tr></tr>");
                    tr.append("<td>" + row.ID + "</td>");
                    tr.append("<td>" + row.donorname + "</td>");
                    tr.append("<td>" + row.runner + "</td>");
                    tr.append("<td>" + row.amount + "</td>");
                    tr.append("<td>" + row.paymenttype + "</td>");
                    tr.append("<td>" + row.date + "</td>");
                    $("#donationtable tbody").append(tr);
                });
            });
        }
        
        $(function() {
            loadrunnerlist();
            loaddonationtable();
            
            $("#drselect").change(function() {
                
                loaddonationtable();
                
            });
            
            $("#btn-adddonation").click(function() {
                $("#adddonationalert span").empty();
                $("#adddonationalert").hide();
                $("#add-donation-modal").modal('show');
            });
            
            $("#btn-confirmadd").click(function () {
                var opts = $("#form-adddonation").serialize();
                //var runnerid = $("#form-adddonation select[name='runnerid']").val();
                var errorul = $("<ul></ul>");
                var error = 0;
                if ($("#form-adddonation input[name='donorname']").val() === "") {
                    errorul.append("<li>Empty Donorname address</li>"); 
                    error = 1;
                };
                if ($("#form-adddonation input[name='email']").val() === "") {
                    errorul.append("<li>Empty email address</li>"); 
                    error = 1;
                };
                if ($("#form-adddonation input[name='amount']").val() === "") {
                    errorul.append("<li>Empty amount</li>"); 
                    error = 1;
                };
                if ($("#form-adddonation select[name='runnerid']").val() === "") {
                    errorul.append("<li>Please choose a runner</li>");
                    error = 1;
                };
                
                if (error === 0) {
                    $.post("r4oinsertdonation.php", opts, function(retdata) {
                        if (retdata === "1") {
                            loaddonationtable();
                            $("#add-donation-modal").modal('hide');
                        } else {
                            errorul.append("<li>Error: cannot add donation</li>")
                            $("#adddonationalert span").append();
                            $("#adddonationalert").show();
                        }    
                    });
                } else {
                    $("#adddonationalert span").append(errorul);
                    $("#adddonationalert").show();
                }
            });
            
            
            
            
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
                    <a style="padding: 13px 15px" class="navbar-brand" href="/">
                        <i class="fa fa-dot-circle-o"></i>
                        <span>R4O Admin</span>
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
                            
                            <li id="nav-setting" class="dropdown hide">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-cog"></i>
                                    Settings
                                    <b class="caret"></b>
                                </a>

                                <ul class="dropdown-menu">
                                        
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
                                <li class="active" ><a href="#" data-toggle="tab" data-target="#ptab1">Donation Report</a></li>
                                <li><a href="#" data-toggle="tab" data-target="#ptab2">Runner Information</a></li>
                                <li><a href="#" data-toggle="tab" data-target="#ptab3">Additional Information</a></li>
                     </ul>
                </div>
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-body">
                           
                            <div id="infobox" class="tab-content">
                                <div id="ptab1" style="height:400px;" class="tab-pane active tab-box" >
                                    <div class="col-md-6">
                                        <select id="drselect" class="rlist form-control">
                                            <option value="0">All Runners</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3"> 
                                    </div>
                                    <div class="col-md-3">    
                                        <button id="btn-adddonation" class="btn btn-primary">Add Donation</button>
                                    </div>
                                    <table id="donationtable" class="table">
                                        <thead>
                                            <tr><th>ID</th><th>Donor Name</th><th>Runner</th><th>Amount</th><th>Payment Method</th><th>Date-Time</th></tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>   
                                <div id="ptab2" style="height:400px;" class="tab-pane tab-box">
                                    <div class="col-md-6">
                                        <select id="runnerselect" class="rlist form-control">
                                            <option value="0">Select Runner</option>
                                        </select>
                                    </div>
                                </div> 
                                <div id="ptab3" style="height:300px;" class="tab-pane tab-box">
                                
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    
    <div id="add-donation-modal" class="modal">
            <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Donation</h4>
              </div>
              <div class="modal-body">
                  
                  <div id="adddonationalert" style="display:none" class="alert alert-danger">
                    <p>Error:</p>
                    <span></span>                
                  </div>
                  <form id="form-adddonation">
                  <table class="nb-table">
                      
                    <tr><th width="150px">Donor Name</th>
                        <td>                            
                            <input class="form-control" type="text" name="donorname" placeholder="donor name" >                          
                        </td>
                        <td></td>
                    </tr>
                    <tr><th width="150px">Donor Email</th>
                        <td>
                            <input class="form-control" type="text" name="email" placeholder="Email address" >
                        </td>
                    </tr>   
                    <tr><th width="150px">Runner</th>
                        <td>
                            <select class="rlist form-control" name="runnerid">
                                <option value="">None</option>
                            </select>
                            
                        </td>
                    </tr>  
                    <tr><th width="150px">Payment Type</th>
                        <td>
                            <select class="form-control" name="paymenttype">
                                <option>Paypal</option>
                                <option>Cash</option>
                                <option>Check</option>
                            </select>
                            
                        </td>
                    </tr>
                    <tr><th width="150px">Amount</th>
                        <td>
                            <input class="form-control" type="text" name="amount" value="" placeholder="Enter Amount" >
                        </td>
                    </tr>   
                    
                    </table>
                  </form>
              </div>
              <div class="modal-footer">
                <button id="btn-confirmadd" type="button" class="bta-confirm btn btn-primary">Confirm</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </div>
            </div><!-- /.modal-content -->
            </div>
        </div><!-- /.modal -->
    
</body>
      
</html>

