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

include "sglobals.php";
if($ir['user_level'] > 2)
{
die("403");
}
//This contains course stuffs
$action = isset($_GET['action']) ? $_GET['action'] : '';
switch($action)
{
case 'newcrime': new_crime_form(); break;
case 'newcrimesub': new_crime_submit(); break; 
case 'editcrime': edit_crime_begin(); break;
case 'editcrimeform': edit_crime_form(); break;
case 'editcrimesub': edit_crime_sub(); break;
case 'delcrime': delcrime(); break; 
case 'newcrimegroup': new_crimegroup_form(); break;
case 'newcrimegroupsub': new_crimegroup_submit(); break;
case 'editcrimegroup': edit_crimegroup_begin(); break;
case 'editcrimegroupform': edit_crimegroup_form(); break;
case 'editcrimegroupsub': edit_crimegroup_sub(); break;
case 'delcrimegroup': delcrimegroup(); break;
case 'reorder': reorder_crimegroups(); break;
default: print "Error: This script requires an action."; break;
}
function new_crime_form()
{
global $ir, $c, $db;
print "

<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'> Adding a new crime</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div> </div>
<div class='generalinfo_simple'><br> <br><br>

<form action='staff_crimes.php?action=newcrimesub' method='post'>
Name: <input type='text' STYLE='color: black;  background-color: white;' name='name' /><br />
Brave Cost: <input type='text' STYLE='color: black;  background-color: white;' name='brave' /><br />
Success % Formula: <input type='text' STYLE='color: black;  background-color: white;' name='percform' value='((WILL*0.8)/2.5)+(LEVEL/4)' /><br />
Success Money: <input type='text' STYLE='color: black;  background-color: white;' name='money' /><br />
Success Crystals: <input type='text' STYLE='color: black;  background-color: white;' name='crys' /><br />
Success Item: ".item2_dropdown($c, 'item')."<br />
Group: ".crimegroup_dropdown($c,'group')."<br />
Initial Text: <textarea rows=4 cols=40 name='itext'  /></textarea><br />
Success Text: <textarea rows=4 cols=40 name='stext' /></textarea><br />
Failure Text: <textarea rows=4 cols=40 name='ftext' /></textarea><br />
Jail Text: <textarea rows=4 cols=40 name='jtext' /></textarea><br />
Jail Time: <input type='text' STYLE='color: black;  background-color: white;' name='jailtime' /><br />
Jail Reason: <input type='text' STYLE='color: black;  background-color: white;' name='jailreason' /><br />
Crime XP Given: <input type='text' STYLE='color: black;  background-color: white;' name='crimexp' /><br />
<input type='submit' STYLE='color: black;  background-color: white;' value='Create Crime' /></form></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
}
function new_crime_submit()
{
global $ir,$c,$userid, $db;
if($_POST['itemon'] != "on") { $_POST['item']=0; }
$db->query("INSERT INTO crimes (crimeNAME, crimeBRAVE, crimePERCFORM, crimeSUCCESSMUNY, crimeSUCCESSCRYS, crimeSUCCESSITEM, crimeGROUP, crimeITEXT, crimeSTEXT, crimeFTEXT, crimeJTEXT, crimeJAILTIME, crimeJREASON, crimeXP) VALUES( '{$_POST['name']}', '{$_POST['brave']}', '{$_POST['percform']}', '{$_POST['money']}', {$_POST['crys']}, {$_POST['item']}, '{$_POST['group']}', '{$_POST['itext']}', '{$_POST['stext']}', '{$_POST['ftext']}', '{$_POST['jtext']}', {$_POST['jailtime']}, '{$_POST['jailreason']}', {$_POST['crimexp']})");
print "Crime created!";
stafflog_add("Created crime {$_POST['name']}");
} 
function edit_crime_begin()
{
global $ir,$c,$h,$userid,$db;
print "

<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'> Editing Crime</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div> </div>
<div class='generalinfo_simple'><br> <br><br>

You can edit any aspect of this crime. <br />
<form action='staff_crimes.php?action=editcrimeform' method='post'>
Crime: ".crime_dropdown($c,'crime')."<br />
<input type='submit' STYLE='color: black;  background-color: white;' value='Edit Crime' /></form></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
}
function edit_crime_form()
{
global $ir,$c,$h,$userid,$db;

$d=$db->query("SELECT * FROM crimes WHERE crimeID={$_POST['crime']}");
$itemi=$db->fetch_row($d);
print "


<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'> Editing Crime</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div> </div>
<div class='generalinfo_simple'><br> <br><br>

<form action='staff_crimes.php?action=editcrimesub' method='post'>
<input type='hidden' name='crimeID' value='{$_POST['crime']}' />

Name: <input type='text' STYLE='color: black;  background-color: white;' name='crimeNAME' value='{$itemi['crimeNAME']}' /><br />
Brave Cost: <input type='text' STYLE='color: black;  background-color: white;' name='crimeBRAVE' value='{$itemi['crimeBRAVE']}' /><br />
Success % Formula: <input type='text' STYLE='color: black;  background-color: white;' name='crimePERCFORM' value='{$itemi['crimePERCFORM']}' /><br />
Success Money: <input type='text' STYLE='color: black;  background-color: white;' name='crimeSUCCESSMUNY' value='{$itemi['crimeSUCCESSMUNY']}' /><br />
Success Crystals: <input type='text' STYLE='color: black;  background-color: white;' name='crimeSUCCESSCRYS' value='{$itemi['crimeSUCCESSCRYS']}' /><br />
Success Item: ".item2_dropdown($c, 'crimeSUCCESSITEM', $itemi['crimeSUCCESSITEM'])."<br />
Group: ".crimegroup_dropdown($c,'crimeGROUP', $itemi['crimeGROUP'])."<br />
Initial Text: <textarea rows=4 cols=40 name='crimeITEXT'  />{$itemi['crimeITEXT']}'</textarea><br />
Success Text: <textarea rows=4 cols=40 name='crimeSTEXT' />{$itemi['crimeSTEXT']}</textarea><br />
Failure Text: <textarea rows=4 cols=40 name='crimeFTEXT' />{$itemi['crimeFTEXT']} </textarea><br />
Jail Text: <textarea rows=4 cols=40 name='crimeJTEXT' />{$itemi['crimeJTEXT']} </textarea><br />
Jail Time: <input type='text' STYLE='color: black;  background-color: white;' name='crimeJAILTIME' value='{$itemi['crimeJAILTIME']}' /><br />
Jail Reason: <input type='text' STYLE='color: black;  background-color: white;' name='crimeJREASON' value='{$itemi['crimeJREASON']}' /><br />
Crime XP Given: <input type='text' STYLE='color: black;  background-color: white;' name='crimeXP' value='{$itemi['crimeXP']}' /><br />
<input type='submit' STYLE='color: black;  background-color: white;' value='Edit Crime' /></form></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
}
function edit_crime_sub()
{
global $ir,$c,$h,$userid, $db;
$db->query("UPDATE crimes SET  crimeNAME='{$_POST['crimeNAME']}', crimeBRAVE='{$_POST['crimeBRAVE']}', crimePERCFORM='{$_POST['crimePERCFORM']}', crimeSUCCESSMUNY='{$_POST['crimeSUCCESSMUNY']}', 
crimeSUCCESSCRYS='{$_POST['crimeSUCCESSCRYS']}', 
crimeSUCCESSITEM='{$_POST['crimeSUCCESSITEM']}', crimeGROUP='{$_POST['crimeGROUP']}', crimeITEXT='{$_POST['crimeITEXT']}', crimeSTEXT='{$_POST['crimeSTEXT']}', crimeFTEXT='{$_POST['crimeFTEXT']}', crimeJTEXT='{$_POST['crimeJTEXT']}', crimeJAILTIME={$_POST['crimeJAILTIME']}, crimeJREASON='{$_POST['crimeJREASON']}', crimeXP={$_POST['crimeXP']} WHERE crimeID={$_POST['crimeID']}");
print "Crime edited...";
stafflog_add("Edited crime {$_POST['crimeNAME']}");

} 
function delcrime()
{
global $ir,$c,$h,$userid, $db;
switch ($_GET['step'])
{
   default:
      echo "
      
<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'> Deleting Crime</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div> </div>
<div class='generalinfo_simple'><br> <br><br>

      Here you can delete a crime. <br />
      <form action='staff_crimes.php?action=delcrime&step=2' method='post'>
      Crime: ".crime_dropdown($c,'crime')."<br />
      <input type='submit' STYLE='color: black;  background-color: white;' value='Delete Crime' /></form></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
   break;
   case 2:
      $target = $_POST['crime'];
      $d=$db->query("SELECT crimeNAME FROM crimes WHERE crimeID='$target'");
      $itemi=$db->fetch_row($d);
      print "
      
<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'> Confirm</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div> </div>
<div class='generalinfo_simple'><br> <br><br>
      
      
      Delete crime -  ".$itemi["crimeNAME"]."?
      <form action='staff_crimes.php?action=delcrime&step=3' method='post'>
      <input type='hidden' name='crimeID' value='$target' />
      <input type='submit' STYLE='color: black;  background-color: white;' name='yesorno' value='Yes' />
      <input type='submit' STYLE='color: black;  background-color: white;' name='yesorno' value='No' onclick=\"window.location='staff_crimes.php?action=delcrime';\" /></form></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
   break;
   case 3:
      $target = $_POST['crimeID'];
      if($_POST['yesorno']=='No')
      {
         die("Crime not deleted.<br><a href='staff_crimes.php?action=delcrime'>&gt;Back to main delete crimes page.</a>");
      }
      if ($_POST['yesorno'] != ("No" || "Yes")) die('Eh');
      $d=$db->query("SELECT crimeNAME FROM crimes WHERE crimeID='$target'");
      $itemi=$db->fetch_row($d);
      $db->query("DELETE FROM crimes WHERE crimeID='$target'");
      echo "Crime {$itemi['crimeNAME']} Deleted.<br><a href='staff_crimes.php?action=delcrime'>&gt;Back to main delete crimes page.</a>"; 
stafflog_add("Deleted crime {$itemi['crimeNAME']}");    
   break;
}
} 
function new_crimegroup_form()
{
global $ir, $c,$db;
print "

<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'> Adding a new crime group</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div> </div>
<div class='generalinfo_simple'><br> <br><br>

<form action='staff_crimes.php?action=newcrimegroupsub' method='post'>
Name: <input type='text' STYLE='color: black;  background-color: white;' name='cgNAME' /><br />
Order Number: <input type='text' STYLE='color: black;  background-color: white;' name='cgORDER' /><br />
<input type='submit' STYLE='color: black;  background-color: white;' value='Create Crime Group' /></form></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
}
function new_crimegroup_submit()
{
global $ir,$c,$userid,$db;
if(!isset($_POST['cgNAME']) || !isset($_POST['cgORDER']))
{
print "You missed one or more of the required fields. Please go back and try again.<br />
<a href='staff_crimes.php?action=newcrimegroup'>&gt; Back</a>";
$h->endpage();
exit;
}
$db->query("INSERT INTO `crimegroups` (`cgNAME`, `cgORDER`) VALUES('{$_POST['cgNAME']}','{$_POST['cgORDER']}')");
print "Crime Group created!";
stafflog_add("Created Crime Group {$_POST['cgNAME']}");
}

function edit_crimegroup_begin()
{
global $ir,$c,$h,$userid,$db;
print "


<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'> Editing A Crime Group</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div> </div>
<div class='generalinfo_simple'><br> <br><br>

<form action='staff_crimes.php?action=editcrimegroupform' method='post'>
Crime Group: ".crimegroup_dropdown($c,'crimeGROUP')."<br />
<input type='submit' STYLE='color: black;  background-color: white;' value='Edit Crime Group' /></form></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
}

function edit_crimegroup_form()
{
global $ir,$c,$h,$userid,$db;

$d=$db->query("SELECT * FROM crimegroups WHERE cgID={$_POST['crimeGROUP']}");
$itemi=$db->fetch_row($d);
print "

<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'> Editing A Crime Group</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div> </div>
<div class='generalinfo_simple'><br> <br><br>

<form action='staff_crimes.php?action=editcrimegroupsub' method='post'>
<input type='hidden' name='cgID' value='{$_POST['crimeGROUP']}' />
Name: <input type='text' STYLE='color: black;  background-color: white;' name='cgNAME' value='{$itemi['cgNAME']}' /><br />
Order Number: <input type='text' STYLE='color: black;  background-color: white;' name='cgORDER' value='{$itemi['cgORDER']}' /><br />
<input type='submit' STYLE='color: black;  background-color: white;' value='Edit Crime Group' /></form></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
}

function edit_crimegroup_sub()
{
global $ir,$c,$h,$userid, $db;
if(!isset($_POST['cgORDER']) || !isset($_POST['cgNAME']))
{
print "You missed one or more of the required fields. Please go back and try again.<br />
<a href='staff_crimes.php?action=editcrimegroup'>&gt; Back</a>";
$h->endpage();
exit;
}
else
{
$db->query("UPDATE crimegroups SET  cgNAME='{$_POST['cgNAME']}', cgORDER='{$_POST['cgORDER']}' WHERE cgID='{$_POST['cgID']}'");
print "Crime Group edited...";
stafflog_add("Edited Crime Group {$_POST['cgNAME']}");
}
}

function delcrimegroup()
{
global $ir,$c,$h,$userid, $db;
switch ($_GET['step'])
{
   default:
      echo "
      
      
<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'> Deleting Crime Group</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div> </div>
<div class='generalinfo_simple'><br> <br><br>
      

<script type='text/javascript'>
function checkme()
{
if(document.theform.crimeGROUP.value==document.theform.crimeGROUP2.value)
{
alert('You cannot select the same crime group to move the crimes to.');
return false;
}
return true;
}
</script>
      <form action='staff_crimes.php?action=delcrimegroup&step=2' method='post' name='theform' onsubmit='return checkme();'>
      Crime Group: ".crimegroup_dropdown($c,'crimeGROUP')."<br />
Move crimes in deleted group to: ".crimegroup_dropdown($c, 'crimeGROUP2')."<br />
      <input type='submit' STYLE='color: black;  background-color: white;' value='Delete Crime Group' /></form></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
   break;
   case 2:
      $target = $_POST['crimeGROUP'];
$target2 = $_POST['crimeGROUP2'];
if($target==$target2) { die("You cannot select the same crime group to move the crimes to."); }
      $d=$db->query("SELECT cgNAME FROM crimegroups WHERE cgID='$target'");
      $itemi=$db->fetch_row($d);
      print "
      
<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'> Confirm</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div> </div>
<div class='generalinfo_simple'><br> <br><br>
      
      
      Delete crime group -  ".$itemi["cgNAME"]."?
      <form action='staff_crimes.php?action=delcrimegroup&step=3' method='post'>
      <input type='hidden' name='cgID' value='$target' />
<input type='hidden' name='cgID2' value='$target2' />
      <input type='submit' STYLE='color: black;  background-color: white;' name='yesorno' value='Yes' />
      <input type='submit' STYLE='color: black;  background-color: white;' name='yesorno' value='No' onclick=\"window.location='staff_crimes.php?action=delcrimegroup';\" /></form></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
   break;
   case 3:
      $target = $_POST['cgID'];
$target2 = $_POST['cgID2'];
      if($_POST['yesorno']=='No')
      {
         die("Crime Group not deleted.");
      }
      if ($_POST['yesorno'] != ("No" || "Yes")) die('This shouldnt happen');
      $d=$db->query("SELECT cgNAME FROM crimegroups WHERE cgID='$target'");
      $itemi=$db->fetch_row($d);
      $db->query("DELETE FROM crimegroups WHERE cgID='{$_POST['cgID']}'");
$db->query("UPDATE crimes SET crimeGROUP={$target2} WHERE crimeGROUP={$target}");
stafflog_add("Deleted crime group {$itemi['cgNAME']}");
      echo "Crime Group deleted.";     
   break;
}
}
function reorder_crimegroups()
{
global $db,$ir,$c,$h,$userid;
if($_POST['submit'])
{
unset($_POST['submit']);
$used=array();
foreach($_POST as $v)
{
if(in_array($v, $used))
{
print "You have used the same order number twice! Go back and try again.";
$h->endpage();
exit;
}
$used[]=$v;
}
foreach($_POST as $k => $v)
{
$cg=str_replace("order","", $k);
if(is_numeric($cg))
{
$db->query("UPDATE crimegroups SET cgORDER={$v} WHERE cgID={$cg}");
}
}
print "Crime group order updated!";
stafflog_add("Reordered crime groups");
}
else
{
$q=$db->query("SELECT * FROM crimegroups ORDER BY cgORDER ASC, cgID ASC");
$rows=$db->num_rows($q);
$i=0;
print "

<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'> Re-ordering Crime Groups</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div> </div>
<div class='generalinfo_simple'><br> <br><br>

<table width='80%' cellspacing='1' class='table'>
<tr>
<th>Crime Group</th>
<th>Order</th>
</tr>
<form action='staff_crimes.php?action=reorder' method='post'>
<input type='hidden' name='submit' value='1' />";
while($r=$db->fetch_row($q))
{
$i++;
print "<tr>
<td>{$r['cgNAME']}</td>
<td><select name='order{$r['cgID']}' type='dropdown'>";
for($j=1;$j<=$rows;$j++)
{
if($j == $i)
{
print "<option value='{$j}' selected='selected'>{$j}</option>";
}
else
{
print "<option value='{$j}'>{$j}</option>";
}
}
print "</select></td></tr>";
}
print "<tr>
<td colspan='2' align='center'><input type='submit' STYLE='color: black;  background-color: white;' value='Reorder' /></td>
</tr></form></table></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
}
}
function report_clear()
{
global $db,$ir,$c,$h,$userid;
if($ir['user_level'] > 3)
{
die("403");
}
$_GET['ID'] = abs((int) $_GET['ID']);
stafflog_add("Cleared player report ID {$_GET['ID']}");
$db->query("DELETE FROM preports WHERE prID={$_GET['ID']}");
print "Report cleared and deleted!<br />
<a href='staff_users.php?action=reportsview'>&gt; Back</a>";
}
$h->endpage();
?>
