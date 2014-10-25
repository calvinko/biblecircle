<?php
/* 
 * dbutil:  sql database utility functions
 */

define('DB_NAME', 'dbko1');

/** MySQL database username */
define('DB_USER', 'dbko1');

/** MySQL database password */
define('DB_PASSWORD', 'Spart@123');

/** MySQL hostname */
define('DB_HOST', 'dbko1.db.3694379.hostedresource.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');


$gdefaultpitch = "Welcome to my fundraising page! I feel grateful that I live in land where " .
                "I can work hard and have the opportunity to prosper.  Realizing there are still many children and orphans who " .
                "does not have enough to eat, I joined the Thousand Miles for the Poor campaign to raise fund for them. " .
                "Your support is highly appreciated." ;

function initdb() {
    mysql_pconnect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error());
    mysql_select_db(DB_NAME) or die('Unable to select database');
}


function createUser($fbuid, $fbname, $type)

{
     $result = mysql_query("INSERT INTO r4o_user (fbuid,name,type) VALUES($fbuid,'$fbname','$type')");

     // insert the default value into r4o_userdata
     mysql_query("INSERT INTO r4o_userdata VALUES($fbuid, 'generalprivacy', 'Everyone')");
     mysql_query("INSERT INTO r4o_userdata VALUES($fbuid, 'raceprivacy', 'Friends')");
     mysql_query("INSERT INTO r4o_userdata VALUES($fbuid, 'trainprivacy', 'Only Me')");
     return $result;
}


function createRunnerFromWPuid ($wpuid, $name, $org)

{
    $result = mysql_query("INSERT INTO r4o_user (fbuid,name,type,wpuid,org) VALUES($wpuid,'$name','Charity Runner', $wpuid, '$org')");
    return $result;
    
}

function getUserData($fbuid, $key) {
    $result = mysql_query("SELECT value FROM r4o_userdata WHERE fbuid=$fbuid AND okey='$key'");
    if ($result) {
        $ret = mysql_fetch_array($result);
        return $ret[0];
    } else {
        return "";
    }

}

function getAllRunnerFundRaised()

{
    $sql = "SELECT SUM(amount) FROM donation WHERE remark != 'invalid'";
    $result = mysql_query($sql);
    if ($result) {
        $row = mysql_fetch_array($result);
        if ($row[0] == null)
            return 0;
        else
            return $row[0];
    } else {
        return 0;
    }
}

function getAllRunnerMileRaced()
{
    $sql = "SELECT SUM(r4o_race.distance) FROM r4o_userrace JOIN r4o_race on r4o_userrace.raceid=r4o_race.ID WHERE status='Finished'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    if ($row[0] != null)
        return $row[0];
    else
        return 0;
}

function getAllRunnerMileScheduled() 

{
    $sql = "SELECT SUM(r4o_race.distance) FROM r4o_userrace JOIN r4o_race on r4o_userrace.raceid=r4o_race.ID WHERE status='Upcomming'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    if ($row[0] != null)
        return $row[0];
    else
        return 0;
    
}

function getPayPalCodeWpuid($wpuid)

{
    
    //$fbuid = getRunnerFBuid($wpuid);
    $sql = "SELECT name,type,org FROM r4o_user where wpuid=$wpuid ";
    $result = mysql_query($sql);
    if ($result) {  
        $row = mysql_fetch_array($result);
        if ($row[2] == 'teamsf') {
            return "28G7TEFRBPBJ4";
        } else {
            return "4QUWQTHP6D9WA";
        }       
    }
    return "28G7TEFRBPBJ4";
}

function getFundRaisedByOrg($orgname)

{
    $sql = "SELECT SUM(donation.amount) FROM donation join r4o_user on donation.runnerid = r4o_user.fbuid WHERE r4o_user.org='$orgname'";
    $result = mysql_query($sql);
    if ($result) {
        $row = mysql_fetch_array($result);
        if ($row[0] == null)
            return 0;
        else
            return $row[0];
    } else {
        return 0;
    }
}

function getRunnerFundGoal($fbuid)

{
    $sql = "SELECT fundraisegoal FROM r4o_user where fbuid=$fbuid ";
    $result = mysql_query($sql);
    if ($result) {
        $row = mysql_fetch_array($result);
        if ($row[0] != null)
            return $row[0];
    }
    return 500;
}

function getRunnerFundRaised($ruid)

{
    $sql = "SELECT SUM(amount) FROM donation WHERE runnerid=$ruid AND remark != 'invalid'";
    $result = mysql_query($sql);
    if ($result) {
        $row = mysql_fetch_array($result);
        if ($row[0] == null)
            return 0;
        else
            return $row[0];
    } else {
        return 0;
    }
}

function getRunnerMileRaced($ruid)
{
    $sql = "SELECT SUM(r4o_race.distance) FROM r4o_userrace JOIN r4o_race on r4o_userrace.raceid=r4o_race.ID WHERE r4o_userrace.fbuid=$ruid AND status='Finished'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    if ($row[0] != null)
        return $row[0];
    else
        return 0;
}

function getRunnerUpcomingRaceMile($ruid)
{
    $sql = "SELECT SUM(r4o_race.distance) FROM r4o_userrace JOIN r4o_race on r4o_userrace.raceid=r4o_race.ID WHERE r4o_userrace.fbuid=$ruid AND status='Upcoming'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    return $row[0];
}

function getRunnerRaceGoal($ruid)
{
    $sql = "SELECT SUM(r4o_race.distance) FROM r4o_userrace JOIN r4o_race on r4o_userrace.raceid=r4o_race.ID WHERE r4o_userrace.fbuid=$ruid";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    if ($row[0] != null)
        return $row[0];
    else
        return 0;
    
}

function getRunnerFirstRunLog($ruid)

{
    $sql = "SELECT rundate FROM r4o_runlog where fbuid=$ruid ORDER BY rundate LIMIT 1";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    if ($row[0] != null)
        return $row[0];
    else
        return 0;

}

function getRunnerMileTrained($ruid)
{
    $sql = "SELECT SUM(distance) FROM r4o_runlog where fbuid=$ruid";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    if ($row[0] != null)
        return $row[0];
    else
        return 0;
}


// Given wordpress wpuid find the coresponding facebook id
// return 0 if not found
function getRunnerFBuid($wpuid)

{
    $sql = "SELECT fbuid FROM r4o_user where wpuid = $wpuid";
    $result = mysql_query($sql);
    if ($result) {
        $row = mysql_fetch_array($result);
        if ($row[0] != null)
            return $row[0];
    }
    return 0;
}


function getRunnerPhotoUrlFromWPuid($wpuid)

{
    $fbuid = getRunnerFBuid($wpuid);
    if($fbuid != 0) {
        return "http://graph.facebook.com/$fbuid/picture?type=large";
    } else {
        return "http://run4orphans.org/img/runner.gif";
    }
}

function getTotalNumOfRunner($org='all') 
{
    if ($org == 'all') {
        $sql = "SELECT COUNT(*) from r4o_user WHERE type='Charity Runner'";
        
    } else {
        $sql = "SELECT COUNT(*) from r4o_user WHERE type='Charity Runner' and org = '$org'";
    }
    $result = mysql_query($sql);
    if ($result) {
        $row = mysql_fetch_array($result);
        if ($row[0] != null)
            return $row[0];
    }
    return 0;
}

function getRunnerName($fbuid)
{
    $sql = "SELECT name FROM r4o_user where fbuid=$fbuid ";
    $result = mysql_query($sql);
    if ($result) {
        $row = mysql_fetch_array($result);
        return $row[0];
    } else {
        return "Name";
    }
}

function getRunnerData($fbuid)

{
    $sql = "SELECT name,type,fundraisegoal,traingoal FROM r4o_user where fbuid=$fbuid ";
    $result = mysql_query($sql);
    if ($result) {  
        $row = mysql_fetch_assoc($result);
        if ($row['type'] == 'Charity Runner') {
            $v = getUserData($fbuid, 'generalprivacy');
            $row['generalprivacy'] = $v == "" ? "Everyone" : $v;
            $v1 = getUserData($fbuid, 'raceprivacy');
            $row['raceprivacy'] = $v1 == "" ? "Friends" : $v1;
            $v2 = getUserData($fbuid, 'trainprivacy');
            $row['trainprivacy'] = $v2 == "" ? "Only Me" : $v2;
            $row['miletrained'] = getRunnerMileTrained($fbuid);
            $row['mileraced'] = getRunnerMileRaced($fbuid);
            $row['fundraised'] = getRunnerFundRaised($fbuid);
            $row['racegoal'] = getRunnerRaceGoal($fbuid);

        }

        return $row;
    } else {
        return null;
    }
}



function getRunnerMetaData($fbuserid, $key)

{
    $sql = "SELECT okey,value from r4o_userdata WHERE okey='$key' AND fbuid=$fbuserid";

    $result = mysql_query($sql);
    if ($result) {
        $row = mysql_fetch_array($result);
        return $row[1];
    } else {
        return "";
    }
}

function getRunnerPitch($fbuid)
{
    $defaultpitch = "Welcome to my fundraising page! I feel grateful that I live in land where " .
                "I can work hard and have the opportunity to prosper.  Realizing there are still many children and orphans who " .
                "do not have enough to eat, I joined the Thousand Miles for the Poor campaign to raise fund for them. " .
                "Your support is highly appreciated." ;

    $val = getRunnerMetaData($fbuid, "fundraisepitch");
    if ($val == "") {
        $ret = $defaultpitch;
    } else {
        $ret = $val;
    }
    return $ret;
}

function getRunnerPitchWPuid($wpuid)
{

    $fbuid = getRunnerFBuid($wpuid);
    return getRunnerPitch($fbuid);
}


//is guestid a friend of fbuserid
function isFriend($fbuserid, $guestid)
{
    return true;
}

function isAdmin($fbuserid)

{
    if ($fbuserid == 547918697) {
        return true;
    } else {
        return false;
    }
}

function isSFAdmin($fbuserid) 
{
    if ($fbuserid == 502168477 || $fbuserid == 547918697 || $fbuserid == 1000304291) {
        return true;
    } else { 
        return false;
    }
}

function allowAccess($fbuserid, $ownerid, $key) {
    if ($key == "generalinfo") {
        $generalprivacy = getRunnerMetaData($ownerid, "generalprivacy");
        if ($generalprivacy == "Everyone") {
            return true;
        } else if ($generalprivacy == "Only Me") {
            return $ownerid == $fbuserid;
        } else if ($generalprivacy == "Friends") {
            return isFriend($fbuserid, $ownerid);
        }
    }


}

function getRunnerRaces($runnerid)

{
    $sql = "SELECT r4o_race.*,r4o_userrace.status,r4o_userrace.racetime,r4o_userrace.ID FROM r4o_userrace join r4o_race on r4o_userrace.raceid = r4o_race.ID WHERE r4o_userrace.fbuid=$runnerid ORDER BY r4o_race.racedate";
    $result = mysql_query($sql);
    $racedata = array();
    if ($result) {
        while ($r = mysql_fetch_assoc($result)) {
            $racedata[] = $r;
        }

    }
    return $racedata;
}

function getDonorList($runnerid) {

     $result = mysql_query("SELECT donorname from donation WHERE runnerid=$runnerid and (paymentstatus !='invalid' or paymentstatus != 'deleted') ORDER BY ID DESC");
     $ret = array();
     if ($result) {
        while ($row = mysql_fetch_row($result)) {
            $ret[] = $row[0];
        }
        return $ret;
     } else {
        $ret[] = mysql_error();
        return $ret;
     }

     
}


function getWPRunnerList () 

{
    $sql = "SELECT r4o_user.fbuid,r4o_user.wpuid from r4o_user join wp_users on r4o_user.wpuid = wp_users.ID";
    $result = mysql_query($sql);
    $data = array();
    if ($result) {
        while ($row = mysql_fetch_assoc($result)) {
            $data[$row['wpuid']] = $row;
        }
    }

    $result1 = mysql_query("SELECT ID,display_name,user_email FROM wp_users");
    if ($result1) {
        while ($row = mysql_fetch_assoc($result)) {
            if ( !isset( $data[$row['ID']]) ) {
                $ret[] = $row;
            }
        }
    }

    return $ret;
    
}

