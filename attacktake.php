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

$atkpage=1;
include "globals.php";

$_GET['ID']=abs((int) $_GET['ID']);
$_SESSION['attacking']=0;
$ir['attacking']=0;
$db->query("UPDATE users SET attacking=0 WHERE userid=$userid");
$od=$db->query("SELECT * FROM users WHERE userid={$_GET['ID']}");
if($_SESSION['attackwon'] != $_GET['ID'])
{
die ("Cheaters don't get anywhere.");
}
if($db->num_rows($od))
{
$r=$db->fetch_row($od);
$gq=$db->query("SELECT * FROM gangs WHERE gangID={$r['gang']}");
$ga=$db->fetch_row($gq);
if($r['hp'] == 1)
{
print "What a cheater u are.";
}
else
{
print "

<div id='mainOutput' style='text-align: center; color: white;  width: 600px; border: 1px solid #222222; height: 100px;
margin: 0 auto 10px; clear: both; position: relative; left: -20px; padding: 8px'>

You beat {$r['username']} ";
$qe=$r['level']*$r['level']*$r['level'];
$expgain=rand($qe/2,$qe);
$expperc=(int) ($expgain/$ir['exp_needed']*100);
print "and gained $expperc% EXP!<br />
You hide your weapons and drop {$r['username']} off outside the hospital entrance. Feeling satisfied, you walk home.";
$hosptime=rand(10,20);
$db->query("UPDATE users SET exp=exp+$expgain WHERE userid=$userid");
$db->query("UPDATE users SET hp=1,hospital=$hosptime,hospreason='Left by <a href=\'viewuser.php?u={$userid}\'>{$ir['username']}</a>' WHERE userid={$r['userid']}");
event_add($r['userid'],"<a href='viewuser.php?u=$userid'>{$ir['username']}</a> attacked you and left you lying outside the hospital.",$c,'combat');
$atklog=mysql_escape_string($_SESSION['attacklog']);
$db->query("INSERT INTO attacklogs VALUES(NULL,$userid,{$_GET['ID']},'won',unix_timestamp(),-2,'$atklog');");
$_SESSION['attackwon']=0;
$warq=$db->query("SELECT * FROM gangwars WHERE (warDECLARER={$ir['gang']} AND warDECLARED={$r['gang']}) OR (warDECLARED={$ir['gang']} AND warDECLARER={$r['gang']})");
if ($db->num_rows($warq) > 0)
{
$war=$db->fetch_row($warq);
$db->query("UPDATE gangs SET gangRESPECT=gangRESPECT-1 WHERE gangID={$r['gang']}");
$ga['gangRESPECT']-=1;
$db->query("UPDATE gangs SET gangRESPECT=gangRESPECT+1 WHERE gangID={$ir['gang']}");
print "<br />You earnt 1 respect for your gang!";

}
//Gang Kill
if ($ga['gangRESPECT']<=0 && $r['gang'])
{
$db->query("UPDATE users SET gang=0 WHERE gang={$r['gang']}");

$db->query("DELETE FROM gangs WHERE gangRESPECT<='0'");
$db->query("DELETE FROM gangwars WHERE warDECLARER={$ga['gangID']} or warDECLARED={$ga['gangID']}");
}
$npcs=array(
);


if($r['user_level']==0)
{
$q=$db->query("SELECT * FROM challengebots WHERE cb_npcid={$r['userid']}");
if ($db->num_rows($q)) {
$cb=$db->fetch_row($q);
$qk=$db->query("SELECT * FROM challengesbeaten WHERE userid=$userid AND npcid={$r['userid']}");
if(!$db->num_rows($qk))
{
$m=$cb['cb_money'];
$db->query("UPDATE users SET money=money+$m WHERE userid=$userid");
print "<br /> You gained \$$m for beating the challenge bot {$r['username']}";
$db->query("INSERT INTO challengesbeaten VALUES($userid, {$r['userid']})");
}
}
}


}
}
else
{
print "You beat Mr. non-existant!";
}

$h->endpage();
?>
