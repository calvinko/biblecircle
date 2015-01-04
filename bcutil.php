<?php
/* 
 * bcutil:  common functions for bible circle
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
  
function getUseridFromFbuid($fbuid) {
    global $mysqli; 
    if ($mysqli == null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
    $sql = "SELECT `userid` FROM `usertbl` WHERE `fbuserid` = $fbuid"; 
     
    $result = $mysqli->query($sql);
    if ($result) {
        $row = $result->fetch_row();
        if ($row) {
            return $row[0];
        }
    }
    return 0;
}

function getUseridFromGuid($guid) {
    global $mysqli; 
    if ($mysqli == null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
    $sql = "SELECT `userid` FROM `usertbl` WHERE `guserid` = '$guid'"; 
     
    $result = $mysqli->query($sql);
    if ($result) {
        $row = $result->fetch_row();
        if ($row) {
            return $row[0];
        }
    }
    return 0;
}

function getUseridFromUsername($username) {
    global $mysqli; 
    if ($mysqli == null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
    $sql = "SELECT `userid` FROM `usertbl` WHERE `username` = '$username'"; 
     
    $result = $mysqli->query($sql);
    if ($result) {
        $row = $result->fetch_row();
        if ($row) {
            return $row[0];
        };
    }
    return 0;
}

function getUseridFromEmail($email) {
    global $mysqli; 
    if ($mysqli == null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
    $sql = "SELECT `userid` FROM `usertbl` WHERE `email` = '$email'"; 
     
    $result = $mysqli->query($sql);
    if ($result) {
        $row = $result->fetch_row();
        if ($row) {
            return $row[0];
        };
    }
    return 0;
}

function getUserProfile($userid) {
    global $mysqli; 
    if ($mysqli == null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
    $sql = "SELECT `userid`, `username`, `email`, `email2`, `passwd`, `firstname`, `lastname`, `fbuserid`, `guserid`, `twuserid`, `type` FROM `usertbl` WHERE `userid` = $userid"; 
     
    $result = $mysqli->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
            $r1 = $mysqli->query("SELECT `curplanid` from bibleuserdata WHERE userid=$userid");
            if ($r1 && $r1->num_rows > 0) {
                $row1 = $r1->fetch_assoc();
                $row['curplanid'] = $row1['curplanid'];
            } else {
                $row['curplanid'] = '1';
            }
            return $row;
        }
    }
    return null;
}

function getUserPlans($uid)
{
    global $mysqli; 
    if ($mysqli == null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
    $sql = "SELECT from bibleplaninstance WHERE userid = '$uid' and status='active'";
    $result = $mysqli->query($sql);
    $rows = array();
    if ($result) {
        while ($row = $mysqli->fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

/* return 0 if user has no plan */
function getUserCurrentPlan($userid) 
{
    global $mysqli; 
    if ($mysqli == null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
     
    $result = $mysqli->query("SELECT curplanid from bibleuserdata WHERE userid = $userid");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_row();
        return $row[0];        
    } else {
        return 1;
    }
}

function getUserMainData($userid) 
{
    global $mysqli; 
    if ($mysqli == null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
    $result = $mysqli->query("SELECT * from bibleuserdata WHERE userid = $userid");
    if ($result) {
        $row = $result->fetch_array();
        return $row;      
    }
    return array();
}

function updatePlanReadLog($instid, $day, $section, $status) {
    global $mysqli;
    if ($mysqli == null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
    $sql = "UPDATE bibleplanreadlog SET status=$status WHERE instid=$instid AND day=$day AND section=$section";
    $result = $mysqli->query($sql);
    return $result;
}

function getReadLogSectionStatus($instid, $day, $section) {
    global $mysqli;
    if ($mysqli == null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
    $sql = "SELECT status from bibleplanreadlog WHERE instid=$instid and day=$day and section=$section";
    $result = $mysqli->query($sql);
    if ($result) {
        $row = $result->fetch_row();
        return $row[0];
    } else 
        return 0;
}

function dateadd($date, $day)
{
        $newdatets = strtotime("$date + $day days");
        if ($newdatets) {
            return date("Y-m-d", $newdatets);
        } else {
            return date("Y-m-d");
        }
}

// return instid on success, 0 otherwise
function createPlanInstance($userid, $planid, $startdate, $gpid=0)

{
    global $mysqli;
    if ($mysqli == null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
    $sql = "INSERT into bibleplaninstance (userid,planid,start,gpid) VALUES('$userid','$planid','$startdate', '$gpid')";
    $result = $mysqli->query($sql);
    if ($result) {
        return $mysqli->insert_id;
    } else {
        return 0;
    }
}

function changePlanInstanceScope($instid, $scope, $scopedata=0) {
    global $mysqli;
    if ($mysqli == null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
    if ($scope == "WholeBible") {
        $sql = "UPDATE bibleplaninstance SET state=1";
        $result = $mysqli->query($sql);
    } elseif ($scope == "OT") {
        $sql = "UPDATE bibleplaninstance state = SET CASE WHEN day <= 39 THEN 1 ELSE 0 END WHERE instid=$instid";
        $result = $mysqli->query($sql);
    } elseif ($scope == "OT") {
        $sql = "UPDATE bibleplaninstance state = SET CASE WHEN day <= 39 THEN 0 ELSE 1 END WHERE instid=$instid";
        $result = $mysqli->query($sql);
    } else {
        $sql = "";
    }
    
    
}


function createPlanForUser($userid, $planid, $startdate, $gpid=0)
{ 
    global $mysqli;
    if ($mysqli == null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
    $instid = createPlanInstance($userid, $planid, $startdate);
    if ($instid != 0) {
        $sql = "SELECT day,section from bibleplandata WHERE planid=$planid ORDER BY day, section";
        $result = $mysqli->query($sql);
        if ($result) {
            while ($row = $result->fetch_row()) {
                $day = $row[0];
                $section = $row[1];
                $datetoread = dateadd($startdate, $day-1);
                $insql = "INSERT INTO bibleplanreadlog (instid, day, section, rdate) VALUES($instid, $day, $section, '$datetoread') ";
                $mysqli->query($insql);
            }
            return $instid;
        } else {
            return 0;
        }
    }
    return 0;
}

function isShineManager($userid) 
{
    return $userid == 1001;
}

class BibleUser {
    
    public $userid = 0;
    private $mysqli = NULL;
    public function __construct() {
        if ($this->mysqli == NULL)
            $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $this->userid = Authenticate::getUserId();
        
    } 
    
    public function getBiblePlans()
    
    {
        
        $userid = $this->userid;
        $rows = array();
        $result = $this->mysqli->query("SELECT * from bibleplaninst WHERE userid=$userid"); 
        if ($result) {
            while ( ($row = $result->fetch_assoc()) != NULL) {
                $rows[] = $row;
            }
        }
        return $rows;
    }
    
    public function getCurrentPlan() 
            
    {
        
        $userid = $this->userid;
        $result = $this->mysqli->query("SELECT curplanid from bibleuserdata WHERE userid = $userid");
        if ($result) {
            $row = $result->fetch_row();
            return $row[0];        
        } else {
            return 1;
        }
    }
    
    // return instid on success, 0 otherwise
    private function createBiblePlanInstance($planid, $startdate, $gpid=0)

    {   
        $sql = "INSERT into bibleplaninstance (fbuserid,planid,start,gpid) VALUES('$this->userid','$planid','$startdate', '$gpid')";
        $result = $this->mysqli->query($sql);
        if ($result) {
            return $this->mysqli->insert_id;
        } else {
            return 0;
        }
    }
    
    public function newPlan($planid, $startdate) 
            
    {
        if ($this->userid != 0) {
            $instid = $this->createBiblePlanInstance($planid, $startdate);
            $sql = "SELECT day,section from bibleplandata WHERE planid=$planid ORDER BY day, section";
            $result = $this->mysqli->query($sql);
            if ($result) {
                while ($row = $result->fetch_row()) {
                    $day = $row[0];
                    $section = $row[1];
                    $datetoread = dateadd($startdate, $day-1);
                    $insql = "INSERT INTO bibleplanreadlog (instid, day, section, rdate) VALUES($instid, $day, $section, '$datetoread') ";
                    $this->mysqli->query($insql);
                }
            } else {
                echo "Error" . $this->mysqli->error;
            }
            return $instid;
        } else {
            return 0;
        }
    }
}
