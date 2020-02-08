<?php
	
	function clean($string) {
   		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}

	function secure($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return($data);
    }

    function encrypt_decrypt($action, $string)
	 {
	     /* =================================================
	      * ENCRYPTION-DECRYPTION
	      * =================================================
	      * ENCRYPTION: encrypt_decrypt('encrypt', $string);
	      * DECRYPTION: encrypt_decrypt('decrypt', $string) ;
	      *HELP URL   : https://hotexamples.com/examples/-/-/openssl_decrypt/php-openssl_decrypt-function-examples.htmlss
	      */
	     $output = false;
	     $encrypt_method = "AES-256-CBC";
	     $secret_key = 'WS-SERVICE-KEY';
	     $secret_iv = 'WS-SERVICE-VALUE';
	     // hash
	     $key = hash('sha256', $secret_key);
	     // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	     $iv = substr(hash('sha256', $secret_iv), 0, 16);
	     if ($action == 'encrypt') {
	         $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
	     } else {
	         if ($action == 'decrypt') {
	             $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	         }
	     }
	     return $output;
	 }

	  // Function to get the client IP address
	function get_client_ip() {
	    $ipaddress = '';
	    if (isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}
?>