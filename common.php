<?php

session_start();

if (empty($_SESSION['csrf'])) {
	if (function_exists('random_bytes')) {
		$_SESSION['csrf'] = bin2hex(random_bytes(32));
	} else if (function_exists('mcrypt_create_iv')) {
		$_SESSION['csrf'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
	} else {
		$_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
	}
}

/**
 * Escapes HTML for output
 *
 */

function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


function testtekst($input, $errorempty, $errorregex) {
  $error = '';
	$output = "$input";
  if (empty($input)) {
    $error = $errorempty;
  } elseif (!preg_match("/^[0-9a-zA-Z-' ]{5,}$/",$input)) {
    $error = $errorregex;
  }
  return  $error;
}
function testURL($input, $errorempty, $errorregex) {
	$error = '';
  $output = "$input";
  if (empty($input)) {
    $error = $errorempty;
  } elseif (!preg_match("/^.*$/",$input)) {
    $error = $errorregex;
  }
  return  $error;
}
function testIP($input, $errorempty, $errorregex) {
	$error = '';
  $output = "$input";
  if (empty($input)) {
    $error = $errorempty;
  } elseif (!preg_match("/^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.|$)){4}$/",$input)) {
    $error = $errorregex;
  }
  return  $error;
}
function testPORT($input, $errorempty, $errorregex) {
	$error = '';
  $output = "$input";
  if (empty($input)) {
    $error = $errorempty;
  } elseif (!preg_match("/^[0-9]{2,5}$/",$input)) {
    $error = $errorregex;
  }
  return  $error;
}
////////

function imgproxy($key, $salt,  $url, $extension) {


	$keyBin = pack("H*" , $key);
	if(empty($keyBin))  { die('Key expected to be hex-encoded string'); }
	$saltBin = pack("H*" , $salt);
	if(empty($saltBin)) { die('Salt expected to be hex-encoded string');	}

	$resize = 'fill';
	$width = 300;
	$height = 300;

	$encodedUrl = rtrim(strtr(base64_encode($url), '+/', '-_'), '=');

	$path = "/{$resize}/{$width}/{$height}/{$encodedUrl}.{$extension}";

	$signature = rtrim(strtr(base64_encode(hash_hmac('sha256', $saltBin.$path, $keyBin, true)), '+/', '-_'), '=');

	return (sprintf("/%s%s", $signature, $path));
}
