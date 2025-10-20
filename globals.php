<?php

/**************************************************************************************************
| Software Name        : Ravan Scripts Online Mafia Game
| Software Author      : Ravan Soft Tech
| Software Version     : Version 2.0.1 Build 2101
| Website              : http://www.ravan.info/
| E-mail               : support@ravan.info
|**************************************************************************************************
| The source files are subject to the Ravan Scripts End-User License Agreement included in License Agreement.html
| The files in the package must not be distributed in whole or significant part.
| All code is copyrighted unless otherwise advised.
| Do Not Remove Powered By Ravan Scripts without permission .
|**************************************************************************************************
| Copyright (c) 2010 Ravan Scripts . All rights reserved.
|**************************************************************************************************/

// PHP 8.x compatibility: Fix for encoded files (codelock)
require_once(__DIR__ . '/codelock_fix.php');

// PHP 8.x: eregi() was removed in PHP 7.0, add polyfills BEFORE header.php
if (!function_exists('eregi')) {
    function eregi($pattern, $string) {
        return preg_match('/' . $pattern . '/i', $string);
    }
}
if (!function_exists('ereg')) {
    function ereg($pattern, $string) {
        return preg_match('/' . $pattern . '/', $string);
    }
}
if (!function_exists('ereg_replace')) {
    function ereg_replace($pattern, $replacement, $string) {
        return preg_replace('/' . $pattern . '/', $replacement, $string);
    }
}
if (!function_exists('eregi_replace')) {
    function eregi_replace($pattern, $replacement, $string) {
        return preg_replace('/' . $pattern . '/i', $replacement, $string);
    }
}

session_start();
ob_start();
// PHP 8.x compatibility: get_magic_quotes_gpc() was removed in PHP 8.0
// Magic quotes are always off in PHP 8.x, so we always apply addslashes
if(!function_exists('get_magic_quotes_gpc') || get_magic_quotes_gpc() == 0)
{
  foreach($_POST as $k => $v)
  {
    $_POST[$k]=addslashes($v);
  }
  foreach($_GET as $k => $v)
  {
    $_GET[$k]=addslashes($v);
  }
}


function anti_inject($campo)
{
foreach($campo as $key => $val)
{
//remove words that contains syntax sql
$val = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"),"",$val);

//Remove empty spaces
$val = trim($val);

//Removes tags html/php
$val = strip_tags($val);

//Add inverted bars to a string
$val = addslashes($val);

// store it back into the array
$campo[$key] = $val;
}
return $campo; //Returns the the var clean
} 

$_GET = anti_inject($_GET);
$_POST = anti_inject($_POST);



require "global_func.php";
if($_SESSION['loggedin']==0) { header("Location: login.php");exit; }
$userid=$_SESSION['userid'];
require "header.php";
include "config.php";
include "language.php";
global $_CONFIG;
if (!defined('MONO_ON')) { define("MONO_ON", 1); }
require "class/class_db_{$_CONFIG['driver']}.php";
$db=new database;
$db->configure($_CONFIG['hostname'],
 $_CONFIG['username'],
 $_CONFIG['password'],
 $_CONFIG['database'],
 $_CONFIG['persistent']);
$db->connect();
$c=$db->connection_id;

// PHP 8.x: mysql_* polyfills
if (!function_exists('mysql_real_escape_string')) {
    function mysql_real_escape_string($string) {
        global $db;
        if (isset($db) && isset($db->connection_id)) {
            return mysqli_real_escape_string($db->connection_id, $string);
        }
        return addslashes($string);
    }
}
if (!function_exists('mysql_escape_string')) {
    function mysql_escape_string($string) {
        return mysql_real_escape_string($string);
    }
}

$set=array();
$settq=$db->query("SELECT * FROM settings");
while($r=$db->fetch_row($settq))
{
$set[$r['conf_name']]=$r['conf_value'];
}
$domain=$_SERVER['HTTP_HOST'];
  

global $jobquery, $housequery;
if($jobquery)
{
$is=$db->query("SELECT u.*,us.*,j.*,jr.* FROM users u LEFT JOIN userstats us ON u.userid=us.userid LEFT JOIN jobs j ON j.jID=u.job LEFT JOIN jobranks jr ON jr.jrID=u.jobrank WHERE u.userid=$userid");
}
else if($housequery)
{
$is=$db->query("SELECT u.*,us.*,h.* FROM users u LEFT JOIN userstats us ON u.userid=us.userid LEFT JOIN houses h ON h.hWILL=u.maxwill WHERE u.userid=$userid");
}
else
{
$is=$db->query("SELECT u.*,us.* FROM users u LEFT JOIN userstats us ON u.userid=us.userid WHERE u.userid=$userid");
}
$ir=$db->fetch_row($is);
if($ir['force_logout'])
{
$db->query("UPDATE users SET force_logout=0 WHERE userid=$userid");
session_unset();
session_destroy();
header("Location: login.php");
exit;
}
global $macropage;
if($macropage && !$ir['verified'] && $set['validate_on']==1)
{
header("Location: macro1.php?refer=$macropage");
exit;
} 
check_level();
$h = new headers;
$h->startheaders();
$fm=money_formatter($ir['money']);
$cm=money_formatter($ir['crystals'],'');
$lv=date('F j, Y, g:i a',$ir['laston']);
global $atkpage;
if($atkpage)
{
$h->userdata($ir,$lv,$fm,$cm,0);
}
else
{
$h->userdata($ir,$lv,$fm,$cm);
}
global $menuhide;
if(!$menuhide)
{
$h->menuarea();
}
?>
