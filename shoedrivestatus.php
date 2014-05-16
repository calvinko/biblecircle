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


require_once 'config.php';

$mysqli = null;

function initmysqli() {
    global $mysqli;
    if ($mysqli == null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $mysqli->query("SET NAMES 'utf8'");
    }
}

function getgoal($userid) {
    global $mysqli;
    $sql = "SELECT * from miscuserdata WHERE userid=$userid and fieldname='shoegoal2014'";
    $result = $mysqli->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
            return $row['fieldvalue'];
        } else {
            return "30";
        }
    }
}
  
function getusertable() {
    global $mysqli;
    $ret = array();
    $result = $mysqli->query("SELECT userid,username,firstname,lastname from usertbl where type='user' and ugroup=17");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $userid = $row['userid'];
            $row['cnumber'] = 0;
            $ret[$userid] = $row;
            
        }
    } else {
        return $ret;
    }
    $sql = "SELECT * from shoedonationlog WHERE dflag=0 and ugroup='Oakland'";
    $res = $mysqli->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $userid = $row['userid'];
            $n = $ret[$userid]['cnumber'] + $row['number'];
            $ret[$userid]['cnumber'] = $n;
        }
    }
    return $ret;
}

function gettotal() {
    global $mysqli;
    $sql = "SELECT SUM(number) from shoedonationlog WHERE dflag=0 and ugroup='Oakland'";
    $result = $mysqli->query($sql);
    if ($result) {
        $row = $result->fetch_row();
        return $row[0];
    } else {
        return 0;
    }
}

initmysqli();

$totgoal = 500;
$total = gettotal();
$usertbl = getusertable();

$percent = (int) ($total * 100 / $totgoal);
$dpercent = $percent < 8 ? 8 : $percent;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Shoe Drive</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css" rel="stylesheet" >
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    
    <script src="//code.jquery.com/jquery.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script src="js/chart.min.js"></script>
    <script>
        
        $(function() {
            
            
            
        });
    </script>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                    
                <div class="panel-heading">
                    <div class="panel-title">Current Status</div>
                </div>     
                <div class="panel-body">
                    <h5>Goal: <span>500</span> pairs </h5>
                    <h6>Raised: <span><?php echo $total; ?></span> pairs</h6> 
                    <div style="width:300px" class="progress">
                      <div class="progress-bar" role="progressbar" style="width: <?php echo $dpercent; ?>%;">
                        <?php echo $percent ?>%
                      </div>
                    </div>
                    <br/>
                    <br/>
                    <table id="statustable" class="table-bordered" style="width:80%">
                        <thead><tr><th>Name</th><th>Goal</th><th>Progress</th></tr></thead>
                        <tbody>
                            <?php
                                foreach ($usertbl as $key => $row) {
                                    $goal = getgoal($key);
                                    $pg = 10;
                                    echo "<tr userid='" , $key, "'>";
                                    echo "<td>", $row['firstname'], ' ', $row['lastname'], "</td>";
                                    echo "<td>", $goal, "</td>";
                                    echo "<td>", $row['cnumber'], "</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</body>
</html>

