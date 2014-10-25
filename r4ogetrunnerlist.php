<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
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

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysqli->query("SET NAMES 'utf8'");

$data = array();

$org = 'teamsf';

if (isset($_POST['org'])) {
    $org = $_POST['org'];
}

if ($org == 'all') {
    $sql = "SELECT fbuid,name,wpuid,fundraisegoal from r4o_user WHERE type='Charity Runner' ";
} else {
    $sql = "SELECT fbuid,name,wpuid,fundraisegoal from r4o_user WHERE type='Charity Runner' and org='$org'";
}

$result = $mysqli->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);

