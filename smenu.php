<?php
/**
 * Staff Menu (smenu.php)
 * Simplified version - original was encoded with codelock
 */

global $db, $c, $ir, $set;

print "
<div class='navipart'>
<div class='navitop'><p>
<img src='images/staff_links.gif' alt='' />
</p></div>

<div class='navi_mid'><ul>
<li><a class='link1' href='staff.php'>Staff Panel Home</a></li>
<li><a class='link1' href='staff_crons.php'>Cron Jobs</a></li>
<li><a class='link1' href='staff_jobs.php'>Jobs Management</a></li>
<li><a class='link1' href='staff_users.php'>Users Management</a></li>
<li><a class='link1' href='staff_items.php'>Items Management</a></li>
<li><a class='link1' href='staff_gangs.php'>Gangs Management</a></li>
<li><a class='link1' href='staff_settings.php'>Game Settings</a></li>
<li><a class='link1' href='staff_logs.php'>Staff Logs</a></li>
<li><a class='link1' href='index.php'>Back to Game</a></li>
</div>

<div><img src='images/navi_btm.gif' alt='' /></div>
</div>

<div class='navipart'>
<div class='navitop'><p>
<img src='images/staff_online.gif' alt='' />
</p></div>
<div class='navi_mid'><ul>
";

// Show online staff members
$q = $db->query("SELECT * FROM users WHERE laston>(unix_timestamp()-15*60) AND user_level>1 ORDER BY userid ASC");
while($r = $db->fetch_row($q)) {
    $la = time() - $r['laston'];
    $unit = "secs";
    if($la >= 60) {
        $la = (int)($la / 60);
        $unit = "mins";
    }
    if($la >= 60) {
        $la = (int)($la / 60);
        $unit = "hours";
        if($la >= 24) {
            $la = (int)($la / 24);
            $unit = "days";
        }
    }
    print "<li><a href='viewuser.php?u={$r['userid']}'>{$r['username']}</a> ($la $unit)</li>\n";
}

print "
</ul>
</div>
<div><img src='images/navi_btm.gif' alt='' /></div>
</div>
";
?>
