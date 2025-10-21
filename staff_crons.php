<?php

/**************************************************************************************************
| Cron Management System
| Allows administrators to enable/disable cron jobs
**************************************************************************************************/

include "sglobals.php";

// Only allow admin (user_level <= 2)
if($ir['user_level'] > 2)
{
    die("403 - Access Denied");
}

// Initialize $_GET and $_POST to avoid undefined key warnings
$_GET['action'] = isset($_GET['action']) ? $_GET['action'] : 'list';
$_POST['cron_id'] = isset($_POST['cron_id']) ? (int)$_POST['cron_id'] : 0;

$action = isset($_GET['action']) ? $_GET['action'] : '';
switch($action)
{
    case 'list': listCrons(); break;
    case 'toggle': toggleCron(); break;
    case 'runnow': runCronNow(); break;
    default: listCrons(); break;
}

function listCrons()
{
    global $db, $ir, $userid;

    $query = $db->query("SELECT * FROM cron_config ORDER BY cron_interval, cron_name");

    print <<<EOF
<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'>Cron Jobs Management</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div>
</div>
<div class='generalinfo_simple'>
<br><br>
<p style='color: #ffffff;'>Manage scheduled tasks (cron jobs) that run automatically to update the game.</p>
<br>
<table width='100%' border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; color: #ffffff;'>
    <tr style='background-color: #333;'>
        <th>ID</th>
        <th>Name</th>
        <th>File</th>
        <th>Description</th>
        <th>Interval</th>
        <th>Status</th>
        <th>Last Run</th>
        <th>Actions</th>
    </tr>
EOF;

    while($r = $db->fetch_row($query))
    {
        $status_color = $r['cron_active'] ? '#00ff00' : '#ff0000';
        $status_text = $r['cron_active'] ? 'ACTIVE' : 'DISABLED';
        $toggle_text = $r['cron_active'] ? 'Disable' : 'Enable';
        $toggle_color = $r['cron_active'] ? '#ff6666' : '#66ff66';
        $last_run = $r['last_run'] ? $r['last_run'] : 'Never';

        print <<<EOF
    <tr>
        <td>{$r['cron_id']}</td>
        <td><b>{$r['cron_name']}</b></td>
        <td><code style='color: #aaa;'>{$r['cron_file']}</code></td>
        <td>{$r['cron_description']}</td>
        <td>{$r['cron_interval']}</td>
        <td style='color: {$status_color}; font-weight: bold;'>{$status_text}</td>
        <td>{$last_run}</td>
        <td>
            <form method='post' action='staff_crons.php?action=toggle' style='display:inline;'>
                <input type='hidden' name='cron_id' value='{$r['cron_id']}' />
                <input type='submit' value='{$toggle_text}' style='background-color: {$toggle_color}; color: #000; padding: 5px 10px; border: none; cursor: pointer;' />
            </form>
            <form method='post' action='staff_crons.php?action=runnow' style='display:inline;'>
                <input type='hidden' name='cron_id' value='{$r['cron_id']}' />
                <input type='submit' value='Run Now' style='background-color: #6699ff; color: #fff; padding: 5px 10px; border: none; cursor: pointer;' />
            </form>
        </td>
    </tr>
EOF;
    }

    print <<<EOF
</table>
<br>
<p style='color: #ffcc00;'><b>Note:</b> Disabling a cron will prevent it from running automatically. You can still run it manually using "Run Now".</p>
<br>
</div>
<div><img src='images/generalinfo_btm.jpg' alt='' /></div>
EOF;
}

function toggleCron()
{
    global $db, $ir, $userid;

    if($_POST['cron_id'] > 0)
    {
        // Get current status
        $q = $db->query("SELECT cron_active, cron_name FROM cron_config WHERE cron_id={$_POST['cron_id']}");

        if($db->num_rows($q) > 0)
        {
            $r = $db->fetch_row($q);
            $new_status = $r['cron_active'] ? 0 : 1;
            $action = $new_status ? 'enabled' : 'disabled';

            // Toggle status
            $db->query("UPDATE cron_config SET cron_active={$new_status} WHERE cron_id={$_POST['cron_id']}");

            // Log action
            stafflog_add("Cron '{$r['cron_name']}' has been {$action}");

            print <<<EOF
<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'>Success</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div>
</div>
<div class='generalinfo_simple'>
<br><br>
<p style='color: #00ff00;'>Cron job '{$r['cron_name']}' has been <b>{$action}</b> successfully!</p>
<br>
<a href='staff_crons.php?action=list' style='color: #6699ff;'>&gt; Back to Cron Management</a>
<br><br>
</div>
<div><img src='images/generalinfo_btm.jpg' alt='' /></div>
EOF;
        }
        else
        {
            print "<p style='color: #ff0000;'>Error: Cron job not found!</p>";
            print "<a href='staff_crons.php?action=list' style='color: #6699ff;'>&gt; Back</a>";
        }
    }
    else
    {
        print "<p style='color: #ff0000;'>Error: Invalid cron ID!</p>";
        print "<a href='staff_crons.php?action=list' style='color: #6699ff;'>&gt; Back</a>";
    }
}

function runCronNow()
{
    global $db, $ir, $userid;

    if($_POST['cron_id'] > 0)
    {
        $q = $db->query("SELECT * FROM cron_config WHERE cron_id={$_POST['cron_id']}");

        if($db->num_rows($q) > 0)
        {
            $r = $db->fetch_row($q);

            print <<<EOF
<div class='generalinfo_txt'>
<div><img src='images/info_left.jpg' alt='' /></div>
<div class='info_mid'><h2 style='padding-top:10px;'>Run Cron Job</h2></div>
<div><img src='images/info_right.jpg' alt='' /></div>
</div>
<div class='generalinfo_simple'>
<br><br>
<p style='color: #ffffff;'><b>Cron:</b> {$r['cron_name']}</p>
<p style='color: #ffffff;'><b>File:</b> {$r['cron_file']}</p>
<br>
EOF;

            if(file_exists($r['cron_file']))
            {
                ob_start();
                try {
                    include $r['cron_file'];
                    $output = ob_get_clean();

                    // Update last run time
                    $db->query("UPDATE cron_config SET last_run=NOW() WHERE cron_id={$_POST['cron_id']}");

                    // Log action
                    stafflog_add("Manually executed cron '{$r['cron_name']}'");

                    print "<p style='color: #00ff00;'><b>✓ Cron executed successfully!</b></p>";
                    print "<p style='color: #aaa;'>Last run updated to current time.</p>";
                } catch (Exception $e) {
                    ob_end_clean();
                    print "<p style='color: #ff0000;'><b>✗ Error executing cron:</b> {$e->getMessage()}</p>";
                }
            }
            else
            {
                print "<p style='color: #ff0000;'><b>✗ Error:</b> Cron file '{$r['cron_file']}' not found!</p>";
            }

            print <<<EOF
<br>
<a href='staff_crons.php?action=list' style='color: #6699ff;'>&gt; Back to Cron Management</a>
<br><br>
</div>
<div><img src='images/generalinfo_btm.jpg' alt='' /></div>
EOF;
        }
        else
        {
            print "<p style='color: #ff0000;'>Error: Cron job not found!</p>";
            print "<a href='staff_crons.php?action=list' style='color: #6699ff;'>&gt; Back</a>";
        }
    }
    else
    {
        print "<p style='color: #ff0000;'>Error: Invalid cron ID!</p>";
        print "<a href='staff_crons.php?action=list' style='color: #6699ff;'>&gt; Back</a>";
    }
}

$h->endpage();
?>
