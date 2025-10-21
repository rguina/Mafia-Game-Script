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



function Clean($Var) {
// PHP 8.x: get_magic_quotes_gpc was removed, check if function exists
if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() == 1) {
$Var = stripslashes($Var);
}
// Use addslashes instead of mysql_real_escape_string (which needs DB connection)
$Var = addslashes(htmlentities($Var));
return $Var;
}


function get_ranks($stat, $mykey)
{
global $ir,$userid,$c;
$q=mysql_query("SELECT count(*) FROM userstats us LEFT JOIN users u ON us.userid=u.userid WHERE us.$mykey > $stat AND us.userid != $userid AND u.user_level != 0", $c) ;
return mysql_result($q,0,0)+1;
}
function get_gameranks($level, $housevalue, $stats)
{
$tp=($level*$level) * 3000 + ($housevalue) + (($stats['strength']+$stats['agility']+$stats['guard']+$stats['labour']+$stats['IQ']) * 10);
if ( $tp < 100000 ) { return " First Newbie"; }
else if ( $tp < 400000 ) { return " Newbie"; }
else if ( $tp < 1600000 ) { return " Beginner"; }
else if ( $tp < 6400000 ) { return " Not Experienced"; }
else if ( $tp < 25600000 ) { return " Rookie"; }
else if ( $tp < 102400000 ) { return " Average"; }
else if ( $tp < 409600000 ) { return " Good"; }
else if ( $tp < 819200000 ) { return " Very Good"; }
else if ( $tp < 3276800000 ) { return " Greater Than Average"; }
else if ( $tp < 13107200000 ) { return " Experienced"; }
else if ( $tp < 52428800000 ) { return " Highly Experienced"; }
else if ( $tp < 209715200000 ) { return " Honoured"; }
else if ( $tp < 838860800000 ) { return " Highly Hounoured"; }
else if ( $tp < 3355443200000 ) { return " Respect King"; }
else if ( $tp < 6655886400000 ) { return " True Champion"; }
else if ( $tp > 6655886400000 ) { return " God Father"; }
}

function money_formatter($muny,$symb='$')
{
return $symb.number_format($muny);
}
function itemtype_dropdown($connection,$ddname="item_type",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM itemtypes ORDER BY itmtypename ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['itmtypeid']}'";
if ($selected == $r['itmtypeid'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.=">{$r['itmtypename']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function item_dropdown($connection,$ddname="item",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM items ORDER BY itmname ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['itmid']}'";
if ($selected == $r['itmid'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.=">{$r['itmname']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function item2_dropdown($connection,$ddname="item",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM items ORDER BY itmname ASC");
if($selected < 1) { $ret.="<option value='0' selected='selected'>-- None --</option>"; }
else { $ret.="<option value='0'>-- None --</option>"; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['itmid']}'";
if ($selected == $r['itmid']) { $ret.=" selected='selected'";$first=1; } 
$ret.=">{$r['itmname']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function location_dropdown($connection,$ddname="location",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM cities ORDER BY cityname ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['cityid']}'";
if ($selected == $r['cityid'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.= ">{$r['cityname']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function shop_dropdown($connection,$ddname="shop",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM shops ORDER BY shopNAME ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['shopID']}'";
if ($selected == $r['shopID'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.= ">{$r['shopNAME']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function user_dropdown($connection,$ddname="user",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM users ORDER BY username ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['userid']}'";
if ($selected == $r['userid'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.= ">{$r['username']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function challengebot_dropdown($connection,$ddname="bot",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT cb.*,u.* FROM challengebots AS cb LEFT JOIN users AS u ON cb.cb_npcid=u.userid ORDER BY u.username ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['userid']}'";
if ($selected == $r['userid'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.= ">{$r['username']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function fed_user_dropdown($connection,$ddname="user",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM users WHERE fedjail=1 ORDER BY username ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['userid']}'";
if ($selected == $r['userid'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.= ">{$r['username']}</option>";
}
$ret.="\n</select>";
return $ret;
}


function business_alert($business, $text) 
{
mysql_query(sprintf("INSERT INTO `businesses_alerts` (`alertId`, `alertBusiness`, `alertText`, `alertTime`) VALUES ('NULL', '%u', '%s', '%d')", $business, $text, time()));
}


function ladder_dropdown($DropBox='Ladder', $Selected='-1')
{
global $db, $c;
$Ret = '<select name="'.$DropBox.'" type="dropdown">';
$Q = $db->query("SELECT `ladderId`, `ladderName` FROM `battle_ladders` ORDER BY `ladderId` DESC");

if($Selected == '-1')
{
$First = '0';
}
else
{
$First = '1';
}

while($r=$db->fetch_row($Q))
{
$Ret .= '\n<option value='.$r['ladderId'].'" ';
if ($Selected == $r['ladderId'] || $First == '0') 
{ 
$Ret .= 'selected="selected"';
$First = '1'; 
} 
$Ret .= '>'.stripslashes($r['ladderName']).'</option>';
}

$Ret .= '\n</select>';
return $Ret;
}


function mailb_user_dropdown($connection,$ddname="user",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM users WHERE mailban > 0 ORDER BY username ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['userid']}'";
if ($selected == $r['userid'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.= ">{$r['username']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function forumb_user_dropdown($connection,$ddname="user",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM users WHERE forumban > 0 ORDER BY username ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['userid']}'";
if ($selected == $r['userid'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.= ">{$r['username']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function job_dropdown($connection,$ddname="job",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM jobs ORDER BY jNAME ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['jID']}'";
if ($selected == $r['jID'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.=">{$r['jNAME']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function jobrank_dropdown($connection,$ddname="jobrank",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT jr.*,j.* FROM jobranks jr LEFT JOIN jobs j ON jr.jrJOB=j.jID  ORDER BY j.jNAME ASC, jr.jrNAME ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['jrID']}'";
if ($selected == $r['jrID'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.=">{$r['jNAME']} - {$r['jrNAME']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function house_dropdown($connection,$ddname="house",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM houses   ORDER BY hNAME ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['hID']}'";
if ($selected == $r['hID'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.=">{$r['hNAME']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function house2_dropdown($connection,$ddname="house",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM houses   ORDER BY hNAME ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['hWILL']}'";
if ($selected == $r['hWILL'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.=">{$r['hNAME']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function course_dropdown($connection,$ddname="course",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM courses   ORDER BY crNAME ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['crID']}'";
if ($selected == $r['crID'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.=">{$r['crNAME']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function crime_dropdown($connection,$ddname="crime",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM crimes   ORDER BY crimeNAME ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['crimeID']}'";
if ($selected == $r['crimeID'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.=">{$r['crimeNAME']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function crimegroup_dropdown($connection,$ddname="crimegroup",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM crimegroups   ORDER BY cgNAME ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['cgID']}'";
if ($selected == $r['cgID'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.=">{$r['cgNAME']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function event_add($userid,$text,$connection=0)
{
global $db;
$text=mysql_escape($text);
$db->query("INSERT INTO events VALUES('',$userid,UNIX_TIMESTAMP(),0,'$text')");
$db->query("UPDATE users SET new_events=new_events+1 WHERE userid={$userid}");
return 1;
}
function mysql_escape($str)
{
return str_replace("'","''",$str);
}
function check_level()
{
global $db;
global $ir,$c,$userid;
$ir['exp_needed']=(int) (($ir['level']+1)*($ir['level']+1)*($ir['level']+1)*2.2);
if($ir['exp'] >= $ir['exp_needed'])
{
$expu=$ir['exp']-$ir['exp_needed'];
$ir['level']+=1;
$ir['exp']=$expu;
$ir['energy']+=2;
$ir['brave']+=2;
$ir['maxenergy']+=2;
$ir['maxbrave']+=2;
$ir['hp']+=50;
$ir['maxhp']+=50;
$ir['exp_needed']=(int) (($ir['level']+1)*($ir['level']+1)*($ir['level']+1)*2.2);
$db->query("UPDATE users SET level=level+1,exp=$expu,energy=energy+2,brave=brave+2,maxenergy=maxenergy+2,maxbrave=maxbrave+2,
hp=hp+50,maxhp=maxhp+50 where userid=$userid");
}
}
function get_rank($stat, $mykey)
{
global $db;
global $ir,$userid,$c;
// Handle NULL stat values
if ($stat === null || $stat === '') {
    $stat = 0;
}
$q=$db->query("SELECT count(*) FROM userstats us LEFT JOIN users u ON us.userid=u.userid WHERE us.$mykey > $stat AND us.userid != $userid AND u.user_level != 0") ;
return $db->fetch_single($q)+1;
}
function item_add($user, $itemid, $qty, $notid=0)
{
global $db;
if($notid > 0)
{
$q=$db->query("SELECT * FROM inventory WHERE inv_userid={$user} and inv_itemid={$itemid} AND inv_id != {$notid}");
}
else
{
$q=$db->query("SELECT * FROM inventory WHERE inv_userid={$user} and inv_itemid={$itemid}");
}
if($db->num_rows($q) > 0)
{
$r=$db->fetch_row($q);
$db->query("UPDATE inventory SET inv_qty=inv_qty+{$qty} WHERE inv_id={$r['inv_id']}");
}
else
{
$db->query("INSERT INTO inventory (inv_itemid, inv_userid, inv_qty) VALUES ({$itemid}, {$user}, {$qty})");
}
}
function item_remove($user, $itemid, $qty)
{
global $db;
$q=$db->query("SELECT * FROM inventory WHERE inv_userid={$user} AND inv_itemid={$itemid}");
if($db->num_rows($q) > 0)
{
$r=$db->fetch_row($q);
if($r['inv_qty']>$qty)
{
$db->query("UPDATE inventory SET inv_qty=inv_qty-{$qty} WHERE inv_id={$r['inv_id']}");
}
else
{
$db->query("DELETE FROM inventory WHERE inv_id={$r['inv_id']}");
}
}
}
function forum_dropdown($connection,$ddname="forum",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM forum_forums ORDER BY ff_name ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['ff_id']}'";
if ($selected == $r['ff_id'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.= ">{$r['ff_name']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function forum2_dropdown($connection,$ddname="forum",$selected=-1)
{
global $db;
$ret="<select name='$ddname' type='dropdown'>";
$q=$db->query("SELECT * FROM forum_forums WHERE ff_auth != 'gang' ORDER BY ff_name ASC");
if($selected == -1) { $first=0; } else { $first=1; }
while($r=$db->fetch_row($q))
{
$ret.="\n<option value='{$r['ff_id']}'";
if ($selected == $r['ff_id'] || $first == 0) { $ret.=" selected='selected'";$first=1; } 
$ret.= ">{$r['ff_name']}</option>";
}
$ret.="\n</select>";
return $ret;
}
function make_bigint($str, $positive=1)
{
$str = (string) $str;
$ret = "";
for($i=0;$i<strlen($str);$i++)
{
if((ord($str[$i]) > 47 && ord($str[$i]) < 58) or ($str[$i]=="-" && $positive == 0)) { $ret.=$str[$i]; }
}
if(strlen($ret) == 0) { return "0"; }
return $ret;
}
function stafflog_add($text)
{
global $db, $ir;
$IP = $_SERVER['REMOTE_ADDR'];
$IP=addslashes($IP);
$IP=mysql_real_escape_string($IP);
$IP=strip_tags($IP);
$text=$db->escape($text);
$db->query("INSERT INTO stafflog VALUES(NULL, {$ir['userid']}, unix_timestamp(), '$text', '$IP')");
} 
?>
