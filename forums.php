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

// Session will be started by globals.php (no need to start it here)
$forums=1;
class bbcode {
var $engine="";
function bbcode()
{
require "bbcode_engine.php";
$this->engine= new bbcode_engine;
$this->engine->cust_tag("/</","&lt;");
$this->engine->cust_tag("/>/","&gt;");
//Since \n and <br> screw up preg, convert them out.
$this->engine->cust_tag("/\n/","&nbrlb;");
$this->engine->simple_bbcode_tag("b");
$this->engine->simple_bbcode_tag("i");  
$this->engine->simple_bbcode_tag("u");  
$this->engine->simple_bbcode_tag("s");
$this->engine->simple_bbcode_tag("sub");  
$this->engine->simple_bbcode_tag("sup");
$this->engine->simple_bbcode_tag("big");
$this->engine->simple_bbcode_tag("small"); 
$this->engine->adv_bbcode_tag("list","ul");
$this->engine->adv_bbcode_tag("olist","ol");
$this->engine->adv_bbcode_tag("item","li");
$this->engine->adv_option_tag("font","font","family");
$this->engine->adv_option_tag("size","font","size"); 
$this->engine->adv_option_tag("url","a","href");
$this->engine->adv_option_tag("color","font","color");
$this->engine->adv_option_tag("style","span","style");
$this->engine->simp_option_notext("img","src");
$this->engine->simp_bbcode_att("img","src");
$this->engine->cust_tag("/\(c\)/","&copy;");
$this->engine->cust_tag("/\(tm\)/","&#153;");
$this->engine->cust_tag("/\(r\)/","&reg;");
$this->engine->adv_option_tag_em("email","a","href");
$this->engine->adv_bbcode_att_em("email","a","href");

$this->engine->cust_tag("/\[left\](.+?)\[\/left\]/","<div align='left'>\\1</div>");
$this->engine->cust_tag("/\[center\](.+?)\[\/center\]/","<div align='center'>\\1</div>");
$this->engine->cust_tag("/\[right\](.+?)\[\/right\]/","<div align='right'>\\1</div>");
$this->engine->cust_tag("/\[quote=(.+?)\](.+?)\[\/quote\]/","<div class='quotetop'>QUOTE(\\1)</div><div class='quotemain'>\\2</div>");
$this->engine->cust_tag("/\[quote\](.+?)\[\/quote\]/","<div class='quotetop'>QUOTE</div><div class='quotemain'>\\1</div>");
$this->engine->cust_tag("/\[code\](.+?)\[\/code\]/","<div class='codetop'>CODE</div><div class='codemain'><code>\\1</code></div>");
$this->engine->cust_tag("/\[codebox\](.+?)\[\/codebox\]/","<div class='codetop'>CODE</div><div class='codemain' style='height:200px;white-space:pre;overflow:auto'>\\1</div>");
$this->engine->cust_tag("/&nbrlb;/","<br />\n"); 
}
function bbcode_parse($html)
{
return $this->engine->parse_bbcode($html);
}

}
function strip_html_tags($text)
{
return preg_replace("/<(.+?)>/is","", $text);
}
function forums_rank($tp)
{

if ( $tp < 3 ) { return "#1 Absolute Newbie"; }
else if ( $tp < 7 ) { return "#2 Newbie"; }
else if ( $tp < 12 ) { return "#3 Beginner"; }
else if ( $tp < 18 ) { return "#4 Not Experienced"; }
else if ( $tp < 25 ) { return "#5 Rookie"; }
else if ( $tp < 50 ) { return "#6 Average"; }
else if ( $tp < 100 ) { return "#7 Good"; }
else if ( $tp< 200 ) { return "#8 Very Good"; }
else if ( $tp < 350 ) { return "#9 Greater Than Average"; }
else if ( $tp < 500 ) { return "#10 Experienced"; }
else if ( $tp < 750 ) { return "#11 Highly Experienced"; }
else if ( $tp < 1200 ) { return "#12 Honoured"; }
else if ( $tp < 1800 ) { return "#13 Highly Hounoured"; }
else if ( $tp < 2500 ) { return "#14 Respect King"; }
else if ( $tp < 5000) { return "#15 True Champion"; }
}
require "globals.php";
$bbc = new bbcode;
print "
<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'> Forums </h2></div>
<div><img src='images/info_right.jpg' alt='' /></div> </div>
<div class='generalinfo_simple'>
";
if($ir['forumban'])
{
die("<font color=red><h3>! ERROR</h3>
You have been forum banned for {$ir['forumban']} days.<br />
<br />
<b>Reason: {$ir['fb_reason']}</font></b>");
}
$_GET['viewforum'] = isset($_GET['viewforum']) ? (int) $_GET['viewforum'] : 0;
if(isset($_GET['viewtopic']) && $_GET['viewtopic'] && (!isset($_GET['act']) || $_GET['act'] != 'quote')) { $_GET['act']='viewtopic'; }

if(isset($_GET['viewforum']) && $_GET['viewforum']) { $_GET['act']='viewforum'; }
if(isset($_GET['reply']) && $_GET['reply']) { $_GET['act']='reply'; }
if(isset($_GET['empty']) && $_GET['empty']==1 && isset($_GET['code']) && $_GET['code']=='kill' && isset($_SESSION['owner']) && $_SESSION['owner'])
{
emptyallforums();
}
switch(isset($_GET['act']) ? $_GET['act'] : '')
{
case 'viewforum':
viewforum();
break;
case 'viewtopic':
viewtopic();
break;
case 'reply':
reply();
break;
case 'newtopicform':
newtopicform();
break;
case 'newtopic':
newtopic();
break;
case 'quote':
quote();
break;
case 'edit':
edit();
break;
case 'move':
move();
break;
case 'editsub':
editsub();
break;
case 'lock':
lock();
break;
case 'delepost':
delepost();
break;
case 'deletopic':
deletopic();
break;
case 'pin':
pin();
break;
case 'recache':
recache_forum($_GET['forum']);
break;
default:
idx();
break;
}
function idx() {
global $ir, $c, $userid, $h, $bbc, $db;
global $db; 
$q=$db->query("SELECT * FROM forum_forums WHERE ff_auth='public' ORDER BY ff_id ASC") or die(mysql_error());
print "<table class='table' width='100%' border='0' cellspacing='1'>
<tr><th>Forum</th> <th>Posts</th> <th>Topics</th> <th>Last Post</th> </tr>\n";
while($r=$db->fetch_row($q))
{
$t=date('F j Y, g:i:s a',$r['ff_lp_time']);
print "<tr> <td align='center'><a href='forums.php?viewforum={$r['ff_id']}' style='font-weight: 800'>{$r['ff_name']}</a><br /><small>{$r['ff_desc']}</small></td> <td align='center'>{$r['ff_posts']}</td> <td align='center'>{$r['ff_topics']}</td> <td align='center'>$t<br />
In: <a href='forums.php?viewtopic={$r['ff_lp_t_id']}&lastpost=1' style='font-weight: 800'>{$r['ff_lp_t_name']}</a><br />
By: <a href='viewuser.php?u={$r['ff_lp_poster_id']}'>{$r['ff_lp_poster_name']}</a> </td> </tr>\n";
}
print "\n</table></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
if($ir['user_level'] <> 1)
{
print "<a name='staff'>

<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'>Staff Only Forums </h2></div>
<div><img src='images/info_right.jpg' alt='' /></div> </div>
<div class='generalinfo_simple'>

";
$q=$db->query("SELECT * FROM forum_forums WHERE ff_auth='staff' ORDER BY ff_id ASC") or die(mysql_error());
print "<table cellspacing='1' class='table' width='100%' border='0'>
<tr><th>Forum</th> <th>Posts</th> <th>Topics</th> <th>Last Post</th> </tr>\n";
while($r=$db->fetch_row($q))
{
$t=date('F j Y, g:i:s a',$r['ff_lp_time']);
print "<tr> <td align='center'><a href='forums.php?viewforum={$r['ff_id']}' style='font-weight: 800'>{$r['ff_name']}</a><br /><small>{$r['ff_desc']}</small></td> <td align='center'>{$r['ff_posts']}</td> <td align='center'>{$r['ff_topics']}</td> <td align='center'>$t<br />
In: <a href='forums.php?viewtopic={$r['ff_lp_t_id']}&lastpost=1' style='font-weight: 800'>{$r['ff_lp_t_name']}</a><br />
By: <a href='viewuser.php?u={$r['ff_lp_poster_id']}'>{$r['ff_lp_poster_name']}</a> </td> </tr>\n";
}
print "\n</table></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
}
}
function viewforum()
{
global $ir, $c, $userid, $h, $bbc, $db;
global $db; 
$q=$db->query("SELECT * FROM forum_forums WHERE ff_id={$_GET['viewforum']}");
$r=$db->fetch_row($q);
if(($r['ff_auth']=='gang' AND $ir['gang'] != $r['ff_owner'] AND $ir["user_level"] < 2) OR ($r['ff_auth'] == 'staff' AND $ir['user_level'] < 2))
{
print "You have no permission to view this forum.<br />
&gt; <a href='forums.php'>Back</a>";
$h->endpage();
exit;
}
if($_GET['viewforum'] <> 1 OR $ir['user_level']==2)
{
$ntl="&nbsp;[<a href='forums.php?act=newtopicform&forum={$_GET['viewforum']}'>New Topic</a>]";
}
else
{
$ntl="";
}
print "<big><a href='forums.php'>Forums Home</a> &gt;&gt; <a href='forums.php?viewforum={$_GET['viewforum']}'>{$r['ff_name']}</a>$ntl</big><br /><br />
<table cellspacing='1' class='table' width='100%' border='0'>
<tr> <th>Topic</th> <th>Posts</th> <th>Started</th> <th>Last Post</th></tr>\n";
$q=$db->query("SELECT * FROM forum_topics WHERE ft_forum_id={$_GET['viewforum']} ORDER BY ft_pinned DESC, ft_last_time DESC") or die(mysql_error());
while($r2=$db->fetch_row($q))
{
$t1=date('F j Y, g:i:s a',$r2['ft_start_time']);
$t2=date('F j Y, g:i:s a',$r2['ft_last_time']);
if($r2['ft_pinned']) { $pt="<b>Pinned:</b>&nbsp;"; } else { $pt=""; }
if($r2['ft_locked']) { $lt="&nbsp;<b>(Locked)</b>"; } else { $lt=""; }
print "<tr> <td align='center'>$pt<a href='forums.php?viewtopic={$r2['ft_id']}&lastpost=1'>{$r2['ft_name']}</a>$lt<br />
<small>{$r2['ft_desc']}</small></td> <td align='center'>{$r2['ft_posts']}</td> <td align='center'>$t1<br />
By: <a href='viewuser.php?u={$r2['ft_owner_id']}'>{$r2['ft_owner_name']}</a></td> <td align='center'>$t2<br />
By: <a href='viewuser.php?u={$r2['ft_last_id']}'>{$r2['ft_last_name']}</a></td> </tr>\n";
}
print "</table></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
}
function viewtopic()
{
global $ir, $c, $userid, $h, $bbc, $db;
global $db; 
$precache=array();
$q=$db->query("SELECT * FROM forum_topics WHERE ft_id={$_GET['viewtopic']}");
$topic=$db->fetch_row($q);
$q2=$db->query("SELECT * FROM forum_forums WHERE ff_id={$topic['ft_forum_id']}");
$forum=$db->fetch_row($q2);
if(($forum['ff_auth']=='gang' AND $ir['gang'] != $forum['ff_owner'] and $ir["user_level"] < 2) OR ($forum['ff_auth'] == 'staff' AND $ir['user_level'] < 2))
{
print "You have no permission to view this forum.<br />
&gt; <a href='forums.php'>Back</a>";
$h->endpage();
exit;
}
print "<big><a href='forums.php'>Forums Home</a> &gt;&gt; <a href='forums.php?viewforum={$forum['ff_id']}'>{$forum['ff_name']}</a> &gt;&gt; <a href='forums.php?viewtopic={$_GET['viewtopic']}'>{$topic['ft_name']}</a></big><br /><br />";
$posts_per_page=20;
$posts_topic=$topic['ft_posts'];
$pages=ceil($posts_topic/$posts_per_page);
$st= ($_GET['st']) ? $_GET['st'] : 0;
if($_GET['lastpost']) { $st=($pages-1)*20; }
$pst=-20;
print "Pages: ";
for($i=1;$i<=$pages;$i++)
{
$pst+=20;
print "<a href='forums.php?viewtopic={$topic['ft_id']}&st=$pst'>";
if($pst == $st) { print "<b>"; }
print $i;
if($pst == $st) { print "</b>"; }
print "</a>&nbsp;";
if($i % 25 == 0) { print "<br />"; }
}
print "<br />";
if($ir['user_level'] > 1)
{
print "
<form action='forums.php?act=move&amp;topic={$_GET['viewtopic']}' method='post'><b>Move topic to:</b> ".forum_dropdown($c, 'forum', -1)."<input type='submit' STYLE='color: black;  background-color: white;' value='Move' /></form><br />
<a href='forums.php?act=pin&topic={$_GET['viewtopic']}''><img src='images/sticky.jpg' alt='Pin/Unpin Topic' title='Pin/Unpin Topic' />Pin/Unpin Topic  </a><a href='forums.php?act=lock&topic={$_GET['viewtopic']}''><img src='images/lock.jpg' alt='Lock/Unlock Topic' title='Lock/Unlock Topic' />Lock/Unlock Topic</a><a href='forums.php?act=deletopic&topic={$_GET['viewtopic']}''><img src='images/delete.gif' alt='Delete Topic' title='Delete Topic' />Delete Topic</a><br />";
}
print "
<table cellspacing='1' class='table' width='100%' border='0'>\n";
$q3=$db->query("SELECT * FROM forum_posts WHERE fp_topic_id={$topic['ft_id']} ORDER BY fp_time ASC LIMIT $st, 20");
$no=$st;
while($r=$db->fetch_row($q3))
{
$qlink="[<a href='forums.php?act=quote&viewtopic={$_GET['viewtopic']}&quotename=". strip_html_tags($r['fp_poster_name']). "&quotetext=".urlencode($r['fp_text']). "'>Quote Post</a>]";
if($ir['user_level'] > 1 || $ir['userid']==$r['fp_poster_id'])
{
$elink="[<a href='forums.php?act=edit&post={$r['fp_id']}&topic={$_GET['viewtopic']}'>Edit Post</a>]";
}
else { $elink=""; }
$no++;
if($no > 1 and $ir['user_level'] > 1) { $dlink="[<a href='forums.php?act=delepost&post={$r['fp_id']}'>Delete Post</a>]"; }
else { $dlink=""; }
$t=date('F j Y, g:i:s a',$r['fp_time']);
if($r['fp_edit_count'] > 0) { $edittext="\n<br /><i>Last edited by <a href='viewuser.php?u={$r['fp_editor_id']}'>{$r['fp_editor_name']}</a> at ".date('F j Y, g:i:s a',$r['fp_editor_time']).", edited <b>{$r['fp_edit_count']}</b> times in total.</i>"; } else { $edittext=""; }
if(!$precache[$r['fp_poster_id']]['userid'])
{
$membq=$db->query("SELECT * FROM users WHERE userid={$r['fp_poster_id']}");
$memb=$db->fetch_row($membq);
$precache[$memb['userid']]=$memb;
}
else
{
$memb=$precache[$r['fp_poster_id']];
}

$rank=forums_rank($memb['posts']);

if($memb['forums_avatar']) { $av="<img src='{$memb['forums_avatar']}' width='100' height='100' />"; } else { $av="<img src='noav.gif' />"; }
if(!$memb['forums_signature']) { $memb['forums_signature']="No Signature"; } else {$memb['forums_signature']=$bbc->bbcode_parse($memb['forums_signature']); }
$r['fp_text']=$bbc->bbcode_parse($r['fp_text']);
print "<tr>
<th align='center'>Post #{$no}</th> <th align='center'>Subject: {$r['fp_subject']}<br />
Posted at: $t $qlink$elink$dlink</th>
</tr>
<tr>
<td valign=top><a href='viewuser.php?u={$r['fp_poster_id']}'>{$r['fp_poster_name']}</a> [{$r['fp_poster_id']}]<br />
$av<br />
Level: {$memb['level']}<br />
Post Count: $rank<br>
Posts:  ".$memb['posts']."</td>
<td valign=top>{$r['fp_text']}
{$edittext}<br />
-------------------<br />
{$memb['forums_signature']}</td>
</tr>";
}

print "</table></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>";
$pst=-20;
print "Pages: ";
for($i=1;$i<=$pages;$i++)
{
$pst+=20;
print "<a href='forums.php?viewtopic={$topic['ft_id']}&st=$pst'>";
if($pst == $st) { print "<b>"; }
print $i;
if($pst == $st) { print "</b>"; }
print "</a>&nbsp;";
if($i % 25 == 0) { print "<br />"; }
}
if(!$topic['ft_locked'])
{
print <<<EOF
<br /><br />
<b>Post a reply to this topic:</b><br />
<form action='forums.php?reply={$topic['ft_id']}' method='post'>
<table cellspacing='1' class='table' width='80%' border='0'>
<tr>
<td align='right'>Subject:</td>
<td align='left'><input type='text' STYLE='color: black;  background-color: white;' name='fp_subject' /></td>
</tr>
<tr>
<td align='right'>Post:</td>
<td align='left'><textarea rows='7' cols='40' name='fp_text'></textarea></td>
</tr>
<tr>
<th colspan='2'><input type='submit' STYLE='color: black;  background-color: white;' value='Post Reply'></th>
</tr>
</table>
</form>
EOF;
}
else
{
print "<br /><br />
<i>This topic has been locked, you cannot reply to it.</i>";
}
}
function reply()
{
global $ir, $c, $userid, $h, $bbc, $db;
global $db; 
$q=$db->query("SELECT * FROM forum_topics WHERE ft_id={$_GET['reply']}");
$topic=$db->fetch_row($q);
$q2=$db->query("SELECT * FROM forum_forums WHERE ff_id={$topic['ft_forum_id']}");
$forum=$db->fetch_row($q2);
if(($forum['ff_auth']=='gang' AND $ir['gang'] != $forum['ff_owner']) OR ($forum['ff_auth'] == 'staff' AND $ir['user_level'] < 2))
{
print "You have no permission to reply to this topic.<br />
&gt; <a href='forums.php'>Back</a>";
$h->endpage();
exit;
}
if(!$topic['ft_locked'])
{
$u=$ir['username'];
if($ir['ul_color']) {
$uname="<font color='{$ir['ul_color']}'>";
if($ir['ul_isbold']) { $uname.="<b>"; }
$uname.=$ir['username'];
if($ir['ul_isbold']) { $uname.="</b>"; }
$uname.="</font>";
$u=$uname;
}
else if($ir['donatordays']) {
$u = "<font color=red>{$ir['username']}</font>";
} 
$u=mysql_escape($u);

$db->query("INSERT INTO forum_posts VALUES(NULL, {$_GET['reply']}, {$forum['ff_id']}, $userid, '$u', unix_timestamp(), '{$_POST['fp_subject']}', '{$_POST['fp_text']}', 0, '', 0, 0)") or die(mysql_error());
$db->query("UPDATE forum_topics SET ft_last_id=$userid, ft_last_name='$u', ft_last_time=unix_timestamp(), ft_posts=ft_posts+1 WHERE ft_id={$_GET['reply']}");
$db->query("UPDATE forum_forums SET ff_lp_time=unix_timestamp(), ff_posts=ff_posts+1, ff_lp_poster_id=$userid, ff_lp_poster_name='$u', ff_lp_t_id={$_GET['reply']}, ff_lp_t_name='{$topic['ft_name']}' WHERE ff_id={$forum['ff_id']}");
$db->query("UPDATE users SET posts=posts+1 WHERE userid=$userid");
print "<br><b>Reply Posted!</b><br />";
$_GET['lastpost']=1;
$_GET['viewtopic']=$_GET['reply'];
viewtopic();
}
else
{
print "
<i>This topic has been locked, you cannot reply to it.</i><br />
<a href='forums.php?viewtopic={$_GET['reply']}'>Back</a>";
}
}
function newtopicform() {
global $ir, $c, $userid, $h, $bbc, $db;
global $db; 
$q=$db->query("SELECT * FROM forum_forums WHERE ff_id={$_GET['forum']}");
$r=$db->fetch_row($q);
if(($r['ff_auth']=='gang' AND $ir['gang'] != $r['ff_owner']) OR ($r['ff_auth'] == 'staff' AND $ir['user_level'] < 2))
{
print "You have no permission to view this forum.<br />
&gt; <a href='forums.php'>Back</a>";
$h->endpage();
exit;
}

print <<<EOF
<big><a href='forums.php'>Forums Home</a> &gt;&gt; <a href='forums.php?viewforum={$_GET['forum']}'>{$r['ff_name']}</a> &gt;&gt; New Topic Form</big>
<form action='forums.php?act=newtopic&forum={$_GET['forum']}' method='post'>
<table cellspacing='1' class='table' width='80%' border='0'>
<tr>
<td align=right>Topic Name:</td>
<td align=left><input type='text' STYLE='color: black;  background-color: white;' name='ft_name' value='' /></td>
</tr>
<tr>
<td align=right>Topic Description:</td>
<td align=left><input type='text' STYLE='color: black;  background-color: white;' name='ft_desc' value='' /></td>
</tr>
<tr>
<td align=right>Topic Text:</td>
<td align=left><textarea rows='8' cols='45' name='fp_text'></textarea></td>
</tr>
<tr>
<th colspan=2><input type='submit' STYLE='color: black;  background-color: white;' value='Post Topic' /></th>
</tr>
</table></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>
EOF;
}
function newtopic()
{
global $ir, $c, $userid, $h, $bbc, $db;
global $db;
$q=$db->query("SELECT * FROM forum_forums WHERE ff_id={$_GET['forum']}");
$r=$db->fetch_row($q);
if(($r['ff_auth']=='gang' AND $ir['gang'] != $r['ff_owner']) OR ($r['ff_auth'] == 'staff' AND $ir['user_level'] < 2))
{
print "You have no permission to view this forum.<br />
&gt; <a href='forums.php'>Back</a>";
$h->endpage();
exit;
}
$u=$ir['username'];
if($ir['ul_color']) {
$uname="<font color='{$ir['ul_color']}'>";
if($ir['ul_isbold']) { $uname.="<b>"; }
$uname.=$ir['username'];
if($ir['ul_isbold']) { $uname.="</b>"; }
$uname.="</font>";
$u=$uname;
}
else if($ir['donatordays']) {
$u = "<font color=red>{$ir['username']}</font>";
} 
$u=mysql_escape($u);

$db->query("INSERT INTO forum_topics VALUES(NULL, {$_GET['forum']}, '{$_POST['ft_name']}', '{$_POST['ft_desc']}', 0, $userid, '$u', unix_timestamp(), 0, '', 0, 0, 0)");
$i=$db->insert_id();
$db->query("INSERT INTO forum_posts VALUES(NULL, {$i}, {$r['ff_id']}, $userid, '$u', unix_timestamp(), '{$_POST['ft_desc']}', '{$_POST['fp_text']}', 0, '', 0, 0)") or die(mysql_error());
$db->query("UPDATE forum_topics SET ft_last_id=$userid, ft_last_name='$u', ft_last_time=unix_timestamp(), ft_posts=ft_posts+1 WHERE ft_id={$i}");
$db->query("UPDATE forum_forums SET ff_lp_time=unix_timestamp(), ff_posts=ff_posts+1, ff_topics=ff_topics+1, ff_lp_poster_id=$userid, ff_lp_poster_name='$u', ff_lp_t_id={$i}, ff_lp_t_name='{$_POST['ft_name']}' WHERE ff_id={$r['ff_id']}");
$db->query("UPDATE users SET posts=posts+1 WHERE userid=$userid");
print "<b>Topic Posted!</b><hr /><br />";
$_GET['viewtopic']=$i;
viewtopic();
}

function emptyallforums()
{
global $ir, $c, $userid, $h, $bbc, $db;
global $db; 
$db->query("update forum_forums set ff_lp_time=0, ff_lp_poster_id=0, ff_lp_poster_name='N/A', ff_lp_t_id=0, ff_lp_t_name='N/A',ff_posts=0, ff_topics=0");
$db->query("truncate forum_topics");
$db->query("truncate forum_posts");
}
function quote()
{
global $ir, $c, $userid, $h, $bbc, $db;
global $db; 
$q=$db->query("SELECT * FROM forum_topics WHERE ft_id={$_GET['viewtopic']}");
$topic=$db->fetch_row($q);
$q2=$db->query("SELECT * FROM forum_forums WHERE ff_id={$topic['ft_forum_id']}");
$forum=$db->fetch_row($q2);
if(($forum['ff_auth']=='gang' AND $ir['gang'] != $forum['ff_owner']) OR ($forum['ff_auth'] == 'staff' AND $ir['user_level'] < 2))
{
print "You have no permission to reply to this topic.<br />
&gt; <a href='forums.php'>Back</a>";
$h->endpage();
exit;
}
print "<big><a href='forums.php'>Forums Home</a> &gt;&gt; <a href='forums.php?viewforum={$forum['ff_id']}'>{$forum['ff_name']}</a> &gt;&gt; <a href='forums.php?viewtopic={$_GET['viewtopic']}'>{$topic['ft_name']}</a> &gt;&gt; Quoting a Post</big><br /><br />";
if(!$topic['ft_locked'])
{
print <<<EOF
<br /><br />
<b>Post a reply to this topic:</b><br />
<form action='forums.php?reply={$topic['ft_id']}' method='post'>
<table cellspacing='1' class='table' width='80%' border='0'>
<tr>
<td align='right'>Subject:</td>
<td align='left'><input type='text' STYLE='color: black;  background-color: white;' name='fp_subject' /></td>
</tr>
<tr>
<td align='right'>Post:</td>
<td align='left'><textarea rows='7' cols='40' name='fp_text'>[quote={$_GET['quotename']}]{$_GET['quotetext']}[/quote]
</textarea></td>
</tr>
<tr>
<th colspan='2'><input type='submit' STYLE='color: black;  background-color: white;' value='Post Reply'></th>
</tr>
</table></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>
</form>
EOF;
}
else
{
print "
<i>This topic has been locked, you cannot reply to it.</i><br />
<a href='forums.php?viewtopic={$_GET['viewtopic']}'>Back</a>";
}
}
function edit()
{
global $ir, $c, $userid, $h, $bbc, $db;
$q=$db->query("SELECT * FROM forum_topics WHERE ft_id={$_GET['topic']}");
$topic=$db->fetch_row($q);
$q2=$db->query("SELECT * FROM forum_forums WHERE ff_id={$topic['ft_forum_id']}");
$forum=$db->fetch_row($q2);
if(($forum['ff_auth']=='gang' AND $ir['gang'] != $forum['ff_owner']) OR ($forum['ff_auth'] == 'staff' AND $ir['user_level'] < 2))
{
print "You have no permission to view this forum.<br />
&gt; <a href='forums.php'>Back</a>";
$h->endpage();
exit;
}
$q3=$db->query("SELECT * FROM forum_posts WHERE fp_id={$_GET['post']}");
$post=$db->fetch_row($q3);
if(!($ir['user_level'] > 1 || $ir['userid']==$post['fp_poster_id']))
{
print "You have no permission to edit this post.<br />
&gt; <a href='forums.php'>Back</a>";
$h->endpage();
exit;
}
print "<big><a href='forums.php'>Forums Home</a> &gt;&gt; <a href='forums.php?viewforum={$forum['ff_id']}'>{$forum['ff_name']}</a> &gt;&gt; <a href='forums.php?viewtopic={$_GET['topic']}'>{$topic['ft_name']}</a> &gt;&gt; Editing a Post</big><br /><br />";
print <<<EOF
<form action='forums.php?act=editsub&topic={$topic['ft_id']}&post={$_GET['post']}' method='post'>
<table cellspacing='1' class='table' width='80%' border='0'>
<tr>
<td align='right'>Subject:</td>
<td align='left'><input type='text' STYLE='color: black;  background-color: white;' name='fp_subject' value='{$post['fp_subject']}' /></td>
</tr>
<tr>
<td align='right'>Post:</td>
<td align='left'><textarea rows='7' cols='40' name='fp_text'>{$post['fp_text']}
</textarea></td>
</tr>
<tr>
<th colspan='2'><input type='submit' STYLE='color: black;  background-color: white;' value='Edit Post'></th>
</tr>
</table></div><div><img src='images/generalinfo_btm.jpg' alt='' /></div><br></div></div></div></div></div>
</form>
EOF;
}
function editsub()
{
global $ir, $c, $userid, $h, $bbc, $db;
global $db; 
$q=$db->query("SELECT * FROM forum_topics WHERE ft_id={$_GET['topic']}");
$topic=$db->fetch_row($q);
$q2=$db->query("SELECT * FROM forum_forums WHERE ff_id={$topic['ft_forum_id']}");
$forum=$db->fetch_row($q2);
if(($forum['ff_auth']=='gang' AND $ir['gang'] != $forum['ff_owner']) OR ($forum['ff_auth'] == 'staff' AND $ir['user_level'] < 2))
{
print "You have no permission to view this forum.<br />
&gt; <a href='forums.php'>Back</a>";
$h->endpage();
exit;
}
$q3=$db->query("SELECT * FROM forum_posts WHERE fp_id={$_GET['post']}");
$post=$db->fetch_row($q3);
if(!($ir['user_level'] > 1 || $ir['userid']==$post['fp_poster_id']))
{
print "You have no permission to edit this post.<br />
&gt; <a href='forums.php'>Back</a>";
$h->endpage();
exit;
}
$db->query("UPDATE forum_posts SET fp_subject='{$_POST['fp_subject']}', fp_text='{$_POST['fp_text']}', fp_editor_id=$userid, fp_editor_name='{$ir['username']}', fp_editor_time=unix_timestamp(), fp_edit_count=fp_edit_count+1 WHERE fp_id={$_GET['post']}");
print "<b>Post Edited!</b><hr /><br />";
$_GET['viewtopic']=$_GET['topic'];
viewtopic();

}
function recache_forum($forum)
{
global $ir, $c, $userid, $h, $bbc, $db;
global $db; 
print "Recaching forum ID $forum ... ";
$q=$db->query("SELECT p.*,t.* FROM forum_posts p LEFT JOIN forum_topics t ON p.fp_topic_id=t.ft_id WHERE p.fp_forum_id=$forum ORDER BY p.fp_time DESC LIMIT 1");
if(!$db->num_rows($q))
{
$db->query("update forum_forums set ff_lp_time=0, ff_lp_poster_id=0, ff_lp_poster_name='N/A', ff_lp_t_id=0, ff_lp_t_name='N/A',ff_posts=0, ff_topics=0 where ff_id={$forum}");
print " ... Done";
}
else
{
$r=$db->fetch_row($q);
$tn=mysql_escape($r['ft_name']);
$pn=mysql_escape($r['fp_poster_name']);
$posts=$db->num_rows($db->query("SELECT fp_id FROM forum_posts WHERE fp_forum_id=$forum"));
$topics=$db->num_rows($db->query("SELECT ft_id FROM forum_topics WHERE ft_forum_id=$forum"));
$db->query("update forum_forums set ff_lp_time={$r['fp_time']}, ff_lp_poster_id={$r['fp_poster_id']}, ff_lp_poster_name='$pn', ff_lp_t_id={$r['ft_id']}, ff_lp_t_name='$tn',ff_posts=$posts, ff_topics=$topics where ff_id={$forum}");
print " ... Done<br />";
}
}
function recache_topic($forum)
{
global $db; 
global $ir, $c, $userid, $h, $bbc;
print "Recaching topic ID $forum ... ";
$q=$db->query("SELECT p.* FROM forum_posts p  WHERE p.fp_topic_id=$forum ORDER BY p.fp_time DESC LIMIT 1");
if(!$db->num_rows($q))
{
$db->query("update forum_topics set ft_last_id=0, ft_last_time=0, ft_last_name='N/A',ft_posts=0 where ft_id={$forum}");
print " ... Done";
}
else
{
$r=$db->fetch_row($q);
$pn=mysql_escape($r['fp_poster_name']);
$posts=$db->num_rows($db->query("SELECT fp_id FROM forum_posts WHERE fp_topic_id=$forum"));
$db->query("update forum_topics set ft_last_id={$r['fp_poster_id']}, ft_last_time={$r['fp_time']}, ft_last_name='$pn',ft_posts=$posts where ft_id={$forum}");
print " ... Done<br />";
}
}
function move()
{
global $ir, $c, $userid, $h, $bbc;
global $db; 
if($ir['user_level'] < 2)
{
die("");
}   
$q=$db->query("SELECT * FROM forum_topics WHERE ft_id={$_GET['topic']}");
$topic=$db->fetch_row($q);
$q2=$db->query("SELECT * FROM forum_forums WHERE ff_id={$_POST['forum']}");
$forum=$db->fetch_row($q2);
$db->query("UPDATE forum_topics SET ft_forum_id={$_POST['forum']} WHERE ft_id={$_GET['topic']}");
$db->query("UPDATE forum_posts SET fp_forum_id={$_POST['forum']} WHERE fp_topic_id={$_GET['topic']}");
print "Topic moved...<br />";

stafflog_add("Moved Topic {$topic['ft_name']} to {$forum['ff_name']}");

recache_forum($topic['ft_forum_id']);
recache_forum($_POST['forum']);
}
function lock()
{
global $ir, $c, $userid, $h, $bbc, $db;
global $db; 
if($ir['user_level'] < 2)
{
die("");
}
$db->query("UPDATE forum_topics SET ft_locked=-ft_locked+1 WHERE ft_id={$_GET['topic']}");
$q=$db->query("SELECT * FROM forum_topics WHERE ft_id={$_GET['topic']}");
$r=$db->fetch_row($q);
print "<b>{$r['ft_name']}</b>'s lock status changed, if it was unlocked, it will now be locked, otherwise, it will be unlocked.";
if($r['ft_locked']==0) {
stafflog_add("Unlocked Topic {$r['ft_name']}");
}
else
{
stafflog_add("Locked Topic {$r['ft_name']}");
}
}
function pin()
{
global $ir, $c, $userid, $h, $bbc, $db;
global $db; 
if($ir['user_level'] < 2)
{
die("");
}
$db->query("UPDATE forum_topics SET ft_pinned=-ft_pinned+1 WHERE ft_id={$_GET['topic']}");
$q=$db->query("SELECT * FROM forum_topics WHERE ft_id={$_GET['topic']}");
$r=$db->fetch_row($q);
print "<b>{$r['ft_name']}</b>'s pin status changed, if it was unpinned, it will now be pinned, otherwise, it will be unpinned.";
if($r['ft_pinned']==0) {
stafflog_add("Unpinned Topic {$r['ft_name']}");
}
else
{
stafflog_add("Pinned Topic {$r['ft_name']}");
}
}
function delepost()
{
global $ir, $c, $userid, $h, $bbc, $db;
global $db; 
if($ir['user_level'] < 2)
{
die("");
}
$q3=$db->query("SELECT * FROM forum_posts WHERE fp_id={$_GET['post']}");
$post=$db->fetch_row($q3);
$q=$db->query("SELECT * FROM forum_topics WHERE ft_id={$post['fp_topic_id']}");
$topic=$db->fetch_row($q);

$u=mysql_escape($post['fp_poster_name']);
$db->query("DELETE FROM forum_posts WHERE fp_id={$post['fp_id']}");
print "Post deleted...<br />";
recache_topic($post['fp_topic_id']);
recache_forum($post['fp_forum_id']);

stafflog_add("Deleted post ({$post['fp_subject']}) in {$topic['ft_name']}");

}
function deletopic()
{
global $db;
$q=$db->query("SELECT * FROM forum_topics WHERE ft_id={$_GET['topic']}");
$topic=$db->fetch_row($q);
$db->query("DELETE FROM forum_topics WHERE ft_id={$_GET['topic']}");
$db->query("DELETE FROM forum_posts WHERE fp_topic_id={$_GET['topic']}");
print "Deleting topic... Done<br />";
recache_forum($topic['ft_forum_id']);
stafflog_add("Deleted topic {$topic['ft_name']}");

}

$h->endpage();
?>
