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
 * 
 * User Authentication
 * 
 * 
 * 
 */

require_once 'config.php';
require_once 'password.php';

define( 'COOKIE_DOMAIN', 'biblecircle.org'); 
define( 'COOKIE_PATH', '/' ); 
define( 'COOKIE_AUTH', 'biblecircleapp'); 

define( 'AUTH_STATUS_FALSE', 0);
define( 'AUTH_STATUS_EXPIRED', 1);
define( 'AUTH_STATUS_ACTIVE', 3); 

class UserManager {

    private $mysqli;
    private $authstatus;
    private $profile;
    private $errormsg;
    
    private function loadUserProfile($fieldname, $value) {
        $result = $this->mysqli->query("SELECT * FROM " . DB_USERTABLE . "WHERE $fieldname=$value");
        if ($result && $result->num_rows > 0) {
            $this->profile = $result->fetch_assoc();  
        } else {
            $this->profile = null;
            throw new Exception("User:$fieldname not found");
        }
        
    }
    
    public function __construct($userid=0, $username='', $authcookie='') {
        
        $this->errormsg = '';
        $this->authstatus = AUTH_STATUS_FALSE;
        
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($this->mysqli->connect_error) {
            $this->errormsg = 'Connect Error: ' . $this->mysqli->connect_error;
            throw new Exception("Connection Error");
        }
        if ($userid != 0) {
            $this->loadUserProfile("userid", $userid);
        } else if ($username != '') {
            $this->loadUserProfile("username", $username);
        } else if ($authcookie != '') {
        } else {
            throw new Exception("Function Error");
        }
    }
    
    public function authbypasswd($passwd) {
        
    }
    
    public function verifyAuthCookie($ecookie) {
        $rawcookie = openssl_decrypt($ecookie, "aes128", COOKIE_SKEY);
        
        list( $id, $expiration, $hmac ) = explode( '|', $rawcookie ); 

        $key = hash_hmac( 'md5', $id . $expiration, SECRET_KEY ); 
        $hash = hash_hmac( 'md5', $id . $expiration, $key ); 
        ///echo "Hash is ", $hash;
        //echo "-- hmac is ", $hmac;        

        if ( $hmac != $hash ) {
            return 0; 
        } else {
            $this->loadUserProfile("userid", $id);
            if ( $expiration > time() ) {
                $this->authstatus = AUTH_STATUS_ACTIVE;
                return $this->authstatus;
            } else {
                return $this->authstatus;
            }
        } 
    }
    
    private function setAuthCookie( $id, $remember = false ) { 

        if ( $remember ) { 
            $expiration = time() + 604800; // num of secs - 7 days 
        } else { 
            $expiration = time() + 86400; // 24 hours 
        } 
        $cookie = $this->generateAuthCookie( $id, $expiration ); 
        
        if ( !setcookie( COOKIE_AUTH, $cookie, $expiration, COOKIE_PATH, COOKIE_DOMAIN, false, true ) ) { 
            throw new AuthException( "Could not set cookie." ); 
        } 
    } 
    
    // $expiration - seconds since epoch 
    public function generateAuthCookie($id, $expiration) { 

        $key = hash_hmac( 'md5', $id . $expiration, SECRET_KEY ); 
        $hash = hash_hmac( 'md5', $id . $expiration, $key ); 

        $cookie = $id . '|' . $expiration . '|' . $hash; 
        
        $ecookie = openssl_encrypt($cookie, "aes128", COOKIE_SKEY);
        
        return $ecookie; 
        
    }
    
    public function verifypasswd($passwd) {
        
        if ($this->profile != null) {
            $hash = openssl_decrypt($this->profile['passwd'], "aes128", DB_USERPASSWDKEY);
            return password_verify($passwd, $hash);
        } return
            false;
    }
    
    public function setpasswd($passwd) {
        $dbusertbl = DB_USERTABLE;
        $userid = $this->profile['userid'];
        $options = ['cost' => 11];
        $hash = password_hash("$passwd", PASSWORD_BCRYPT, $options);
        $epw = openssl_encrypt ($hash, "aes128", DB_USERPASSWDKEY);
        if ($this->mysqli->query("UPDATE $dbusertbl SET passwd='$epw' WHERHE userid=$userid")) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public function userLogin($passwd) {
        
    }
    
}

class AuthException extends Exception {}

try {
    $auth = new UserManager(1001);
    print_r($auth);
} catch (Exception $e) {
    echo $e->getMessage();
}
 

