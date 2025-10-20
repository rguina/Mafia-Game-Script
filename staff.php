<?php
/**************************************************************************************************
| Staff Panel - Simplified Version
| Redirects to Cron Management
**************************************************************************************************/

include "sglobals.php";

// Only allow admin (user_level <= 2)
if($ir['user_level'] > 2)
{
    die("403 - Access Denied");
}

// Staff panel main page
print <<<EOF
<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'>Staff Panel</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div>
</div>
<div class='generalinfo_simple'>
<br><br>
<h3 style='color: #ffffff;'>Welcome to the Staff Panel</h3>
<br>
<p style='color: #ffffff;'>Select a management tool:</p>
<br>
<ul style='color: #ffffff; line-height: 2em;'>
    <li><a href='staff_crons.php' style='color: #6699ff;'>Cron Jobs Management</a> - Enable/disable and manage scheduled tasks</li>
    <li><a href='staff_jobs.php?action=managejobs' style='color: #6699ff;'>Jobs Management</a> - Create and edit game jobs</li>
    <li><a href='staff_users.php' style='color: #6699ff;'>Users Management</a> - Manage player accounts</li>
    <li><a href='staff_items.php' style='color: #6699ff;'>Items Management</a> - Create and edit game items</li>
</ul>
<br>
</div>
<div><img src='images/generalinfo_btm.jpg' alt='' /></div>
EOF;

$h->endpage();
?>
