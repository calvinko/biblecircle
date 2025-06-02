<?php

  require_once 'Google/Client.php';
  require_once 'config.php';
  require_once 'authutil.php';
  require_once 'bcutil.php';
  
  $client = new Google_Client();
  $client->setApplicationName("BibleCircle");
  $client->setClientId(GC_CLIENT_ID);
  $client->setClientSecret(GC_CLIENT_SECRET);
  $client->setRedirectUri("postmessage");

  
  //$code = $request->getContent();
  //$gPlusId = $request->get['gplus_id'];
  // Exchange the OAuth 2.0 authorization code for user credentials.
  
  $ret['status'] = "failure";
  if (isset($_POST['code'])) {
    $code = $_POST['code'];
    
    if ( intval(filter_input(INPUT_POST, "remember")) != 0) {
        $remember = true;
    } else {
        $remember = false;
    }
    
    $client->authenticate($code);

 
    $token = json_decode($client->getAccessToken());
 
    //echo $client->getAccessToken();
  
    //$reqUrl = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $token->access_token;
    //$req = new Google_Http_Request($reqUrl);
    //$reqprofile = 'https://www.googleapis.com/plus/v1/people/me?access_token=' . $token->access_token;
  
    $reqUrl = 'https://www.googleapis.com/userinfo/v2/me?access_token=' . $token->access_token;
   
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $reqUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $retstr = curl_exec($ch);
    $profile = json_decode($retstr);
    
    $guid = $profile->id;
    $email = $profile->email;
    initmysqli();
    $userid = getUseridFromGuid($guid);
    if ($userid != 0) {
         setBACookie($userid, $remember);
         $ret['email'] = $email;
         $ret['status'] = "success";
    } else {
        
        if (($uid = getUseridFromUsername($email)) != 0) {
            $mysqli->query("UPDATE usertbl SET guserid='$guid' where email='$email'");
            setBACookie($uid, $remember);
            $ret['username'] = $email;
            $ret['status'] = "accountlinked";
        } elseif (($uid = getUseridFromEmail($email)) != 0) {
            $mysqli->query("UPDATE usertbl SET guserid='$guid' where email='$email'");
            setBACookie($uid, $remember);
            $ret['email'] = $email;
            $ret['status'] = "accountlinked";
            $ret['guid'] = $guid;
        } else {
            $sql = "INSERT into usertbl set created='$tstamp', username='$email', email='$email', passwd='none', guserid=$guid, type='user'";
            $result = $mysqli->query($sql);
            if ($result) {
                $userid = $mysqli->insert_id;
                setBACookie($userid, $remember);
                $ret['status'] = "accountcreated";
                $ret['email'] = $email;
            }
        }
    }   
    echo json_encode($ret);
  }
  