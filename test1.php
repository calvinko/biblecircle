
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
    <script>
        
        var crecid = '0';
        function fillvalue(elm, nameattr, value) {
            var e = elm.find("input[name='" + nameattr + "']");
            console.log(nameattr + " = " + e.length);
            if (e.length !== 0) {
                e.val(value);
            }
        }
        
        function refreshShoeDriveData() 
        {
            $("#loading-overlay").removeClass("hide");
            $.getJSON("getshoedrivedata.php", {}, function(retobj) {
                $("#input-goal").val(retobj.goal);
                $("#shoeform span.sd-status").text(retobj.gstatus);
            })
            
            $.getJSON("shoedrivelog.php", {op: "fetch"},  function(retobj) {
                 var rows = retobj.data;
                 $("#loading-overlay").addClass("hide");
                 $("#shoedrivelogtable tbody").empty();
                 $.each(rows, function(index, row) {
                     var tr = $("<tr></tr>");
                     tr.append("<td>" + row.ddate + "</td>");
                     tr.append("<td>" + row.number + "</td>");
                     var ebut = $("<button style='margin-right:5px' class='btn btn-primary btn-sm'><i class='glyphicon glyphicon-pencil'></i> Change</button>");
                     var dbut = $("<button class='btn btn-default btn-sm'><i class='glyphicon glyphicon-trash'></i> Delete</button>");
                     dbut.click(function() {
                         crecid = row.recid;
                         $("#delete-log-modal").modal('show');
                    })
                     tr.append($("<td></td>").append(ebut).append(dbut));
                     $("#shoedrivelogtable tbody").append(tr);
                 })
            })
        }
        
        $(function() {
            
            refreshShoeDriveData();
            
            $("#btn-changegoal").click(function() {
                $(this).hide();
                $("#input-goal").removeAttr('disabled');;
                $("#btn-savegoal").show();
                $("#btn-cancelgoal").show();
                
            });
            
            $("#btn-savegoal").click(function() {
                
                var ngoal = $("#input-goal").val();
                $.post("updateshoedrivedata.php", {goal: ngoal}, function() {
                    $("#btn-savegoal, #btn-cancelgoal").hide();
                    $("#btn-changegoal").show();
                    refreshShoeDriveData();
                });
            });
            
            $("#btn-cancelgoal").click(function() {
                $("#btn-savegoal, #btn-cancelgoal").hide();
                $("#btn-changegoal").show();
                refreshShoeDriveData();
            });
            
            $("#delete-log-modal .btn-confirm").click(function() {
                $("#delete-log-modal").modal('hide');
                $("#loading-overlay").removeClass("hide");
                $.getJSON("shoedrivelog.php", {op: 'delete', recid: crecid}, function(retobj) {
                    refreshShoeDriveData();
                   
                });
            });
            
            $('#btn-addrecord').click(function() {
                var dt = $("#input-date").val();
                var n = $("#input-number").val();
                if (n !== "Select") {
                    $.getJSON("shoedrivelog.php", {op: "insert", date: dt, number: n}, function(retobj) {
                        refreshShoeDriveData();
                    });
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
                    <li class="active" ><a href="#" data-toggle="tab" data-target="#ptab1">Goal & Status</a></li>
                    <li><a href="#" data-toggle="tab" data-target="#ptab2">Record</a></li>
                       
                </ul>
            </div>
            <div class="col-md-9">
                <div class="panel panel-default">
                    
                    <div class="panel-heading">
                        <div class="panel-title">Soles4souls - Shoe Drive</div>
                    </div>     
                    <div class="panel-body">
                        <div class="tab-content">
                            <div id="ptab1" style="height:400px;" class="tab-pane active tab-box" >
                                <form id="shoeform" class="col-md-12" role="form">
                                    <div class="row form-group">
                                        <label for="goal" style="line-height: 28px"  class="col-md-3 control-label">Goal (# pairs of shoes)</label>
                                        <div class="col-md-6">
                                            <input id="input-goal" disabled="disabled" title="Goal" type="text" class="form-control" name="goal" value="Loading ..." placeholder="Enter Your Goal">
                                        </div>
                                        <div class="col-md-3">
                                            <a id="btn-changegoal" style="line-height: 28px" href="#">change</a>
                                            <span><button id="btn-savegoal" style="display:none" class="btn btn-sm btn-primary">save</button>&nbsp &nbsp<button id="btn-cancelgoal" style="display:none" class="btn btn-sm btn-default">cancel</button></span>
                                        </div>
                                    </div>
                                    
                                     <div style="line-height: 28px" class="row form-group">
                                              <label for="status" class="col-md-3 control-label">Current Status</label>
                                              <div class="col-md-7">
                                                  <span title="Status" class="sd-status">n/a</span>
                                              </div>
                                              
                                    </div>
                                   
                                </form>
                            </div>
                            <div id="ptab2" style="height:600px;" class="tab-pane tab-box" >
                                <div style="margin: 5px 3px;" class="row">
                                    <table id="shoedrivelogtable" class="table col-md-10">
                                        <thead>
                                            <tr><th class="col-md-4">Date</th><th class="col-md-4">No. of Pair Submitted</th><th>Operation</th></tr>
                                            <tr>
                                            <td>
                                                 <div class="input-group date" data-date="<?php echo $todaystr; ?>" data-date-format="yyyy-mm-dd">
                                                      <input id='input-date' class="form-control" size="10" type="text" value="<?php echo $todaystr; ?>">
                                                      <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                                 </div>    
                                            </td>
                                            <td>
                                                <select id='input-number' class="form-control">
                                                    <option>Select</option>
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                    <option>5</option>
                                                    <option>6</option>
                                                    <option>7</option>
                                                    <option>8</option>
                                                    <option>9</option>
                                                    <option>10</option>
                                                </select>
                                            </td>
                                            <td>
                                                <button id='btn-addrecord' class='btn btn-success'><i class='glyphicon glyphicon-plus'></i> Add Shoe Donation Record</button>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        
                                    </table>
                                </div>
                                
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
     <div id="loading-overlay" style="text-align: center" class="hide overlay">    
            <div style="display:inline-block; margin: 220px auto">
                <div style="display: none; margin-bottom: 31px; background-color: #eee; padding: 10px 60px; border-radius: 5px;  font-size: 16px; color: black;">Loading</div>
                <img src="images/loading-b1.gif" height="50">
                <br/>
                
            </div>
        </div>
    
    <div id='delete-log-modal' class='modal'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class="modal-header">
                
                    <h4 class="modal-title">Delete Donation Log</h4>
                </div>
                    <br/>
                    <p>Are you sure you want to delete the record?</p>
                    <br/>
                <div class="modal-footer">
                    <button type="button" class="btn-confirm btn btn-primary">Confirm</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    
    <div id="edit-log-modal" class="modal">
            <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Shoe Donation Log/h4>
              </div>
              <div class="modal-body">
                  <table class="table">
                      <tr><th width="150px">Date</th>
                          <td>
                          <div class="input-group date" data-date="<?php echo $todaystr; ?>" data-date-format="yyyy-mm-dd">
                              <input class="form-control" size="10" type="text" value="<?php echo $todaystr; ?>">
                              <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                          </div>    
                          
                          </td>
                          <td></td></tr>
                    <tr><th># of pair of shoe</th>
                        <td>
                        </td>
                        <td><button type="button" class="hidden btn btn-default">customize</button></td>
                    </tr>
                    
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="bta-confirm btn btn-primary">Confirm</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </div>
            </div><!-- /.modal-content -->
            </div>
        </div><!-- /.modal -->
        
</body>
</html>