# Admin Panel - Quick Access Guide

## Cron Jobs Management

### Access URL
```
https://mafia-game-script.test/staff_crons.php
```

### Requirements
- Admin account with **user_level <= 2**
- Currently logged in

### Default Admin Credentials
```
Username: admin
Password: admin123
Level: 2 (Admin)
```

## What You Can Do

### 1. View All Crons
The main page shows a table with all 9 cron jobs including:
- ID and Name
- PHP filename
- Description of what it does
- Execution interval (minute, 5 minutes, hourly, daily)
- Status (ACTIVE in green or DISABLED in red)
- Last run timestamp
- Action buttons

### 2. Enable/Disable Crons
- **Green "Enable" button**: Click to activate a disabled cron
- **Red "Disable" button**: Click to deactivate an active cron
- Changes take effect immediately
- Action is logged in staff logs

### 3. Run Crons Manually
- **Blue "Run Now" button**: Executes the cron immediately
- Useful for testing
- Updates "Last Run" timestamp
- Shows success/error message
- Action is logged in staff logs

## Available Cron Jobs

| ID | Name | File | Interval | Purpose |
|----|------|------|----------|---------|
| 1 | Minute Updates | cron_run_minute.php | Every minute | Hospital, jail, travel, bodyguard timers |
| 2 | Five Minute Updates | cron_run_five.php | Every 5 minutes | Energy, will, health, brave regeneration |
| 3 | Hourly Updates | cron_run_hour.php | Every hour | Hourly maintenance tasks |
| 4 | Daily Updates | cron_run_day.php | Once per day | Daily resets and cleanup |
| 5 | Battle Cron | battle_cron.php | Variable | Combat event processing |
| 6 | Secure Minute | cron_srun_minute.php | Every minute | Secure minute updates |
| 7 | Secure Five Minute | cron_srun_five.php | Every 5 minutes | Secure 5-minute updates |
| 8 | Secure Hourly | cron_srun_hour.php | Every hour | Secure hourly updates |
| 9 | Secure Daily | cron_srun_day.php | Once per day | Secure daily updates |

## Common Use Cases

### Temporarily Disable a Problematic Cron
1. Go to `staff_crons.php`
2. Find the problematic cron
3. Click the red "Disable" button
4. Cron will stop running automatically
5. Fix the issue in the code
6. Click green "Enable" button to reactivate

### Test a Cron Before Scheduling
1. Go to `staff_crons.php`
2. Find the cron you want to test
3. Click "Run Now"
4. Check the output and verify it works
5. If successful, it will run automatically on schedule

### Debug Why Stats Aren't Regenerating
1. Check if "Five Minute Updates" is **ACTIVE**
2. Check "Last Run" timestamp - should be recent
3. Try "Run Now" to test manually
4. Check if database updates are working

## Database Table

The cron management uses the `cron_config` table:

```sql
-- View all crons
SELECT * FROM cron_config;

-- Check active crons
SELECT cron_name, cron_active, last_run
FROM cron_config
WHERE cron_active = 1;

-- Manually enable a cron
UPDATE cron_config SET cron_active = 1 WHERE cron_id = 2;

-- Manually disable a cron
UPDATE cron_config SET cron_active = 0 WHERE cron_id = 2;
```

## Security Notes

- Only users with `user_level <= 2` can access
- All actions are logged via `stafflog_add()`
- Session authentication required
- No direct file system modification
- Safe to use in production

## Troubleshooting

### Can't access staff_crons.php
- **Error 403**: Your account is not admin level
- **Solution**: Update your user_level to 2 or lower
  ```sql
  UPDATE users SET user_level = 2 WHERE username = 'yourusername';
  ```

### Cron says "File not found"
- The PHP file doesn't exist in the root directory
- Check if the file was deleted or moved
- Restore from backup or create the file

### "Run Now" doesn't work
- Check if file exists
- Check PHP error logs for syntax errors
- Check database connection in the cron file

## Integration with Task Scheduler

### Windows (Laragon)
You can create a Windows Task Scheduler task to run crons:

**For minute cron:**
```
Program: C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe
Arguments: C:\laragon\www\Mafia-Game-Script\cron_run_minute.php
Trigger: Every 1 minute
```

**Note**: The cron will check `cron_config.cron_active` and exit if disabled!

### Linux (crontab)
```bash
# Edit crontab
crontab -e

# Add these lines:
* * * * * php /path/to/cron_run_minute.php
*/5 * * * * php /path/to/cron_run_five.php
0 * * * * php /path/to/cron_run_hour.php
0 0 * * * php /path/to/cron_run_day.php
```

## Future Enhancements

Potential features for v2:
- Execution history log
- Email notifications on failures
- Performance metrics (execution time)
- Cron dependency management
- Custom cron intervals
- Automatic error recovery

---

**Created**: 2025-10-20
**Version**: 1.0
**Tested**: PHP 8.3.16, MySQL 8.4.3
