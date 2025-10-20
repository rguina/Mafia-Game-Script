# Cron Management System

## Overview
This system allows administrators to manage scheduled tasks (cron jobs) from the game's admin panel.

## Features
- **Enable/Disable Crons**: Turn cron jobs on or off without modifying code
- **View Status**: See which crons are active and when they last ran
- **Manual Execution**: Run any cron job immediately for testing
- **Detailed Information**: View descriptions and intervals for each cron

## Accessing the Cron Management Panel

**URL**: `https://your-domain.com/staff_crons.php`

**Requirements**:
- Admin account (user_level <= 2)
- Logged in to the game

## Available Cron Jobs

### 1. Minute Updates (`cron_run_minute.php`)
- **Interval**: Every minute
- **Functions**:
  - Decrements hospital time
  - Decrements jail time
  - Decrements travel time
  - Decrements bodyguard time
  - Updates hospital and jail counts

### 2. Five Minute Updates (`cron_run_five.php`)
- **Interval**: Every 5 minutes
- **Functions**:
  - Regenerates energy (faster for donators)
  - Regenerates will power
  - Regenerates health/HP
  - Regenerates brave stats
  - Handles validation resets

### 3. Hourly Updates (`cron_run_hour.php`)
- **Interval**: Every hour
- **Functions**: Game-wide hourly maintenance tasks

### 4. Daily Updates (`cron_run_day.php`)
- **Interval**: Once per day
- **Functions**: Daily resets and maintenance

### 5. Battle Cron (`battle_cron.php`)
- **Interval**: Variable
- **Functions**: Processes battle tent and combat events

### 6-9. Secure Crons (`cron_srun_*.php`)
- Secure versions with authentication
- Same intervals as regular crons

## Database Structure

### `cron_config` Table
```sql
CREATE TABLE cron_config (
  cron_id INT AUTO_INCREMENT PRIMARY KEY,
  cron_name VARCHAR(100) NOT NULL,
  cron_file VARCHAR(255) NOT NULL,
  cron_description TEXT,
  cron_interval VARCHAR(50) NOT NULL,
  cron_active TINYINT(1) NOT NULL DEFAULT 1,
  last_run TIMESTAMP NULL DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Usage

### Enable/Disable a Cron
1. Go to `staff_crons.php`
2. Find the cron you want to manage
3. Click "Enable" or "Disable" button
4. The status will update immediately

### Run a Cron Manually
1. Go to `staff_crons.php`
2. Find the cron you want to run
3. Click "Run Now" button
4. The cron will execute immediately
5. Last run time will be updated

## Technical Implementation

### Integration with Existing Crons
To integrate the enable/disable feature with your cron scheduler:

**Option 1: Check in cron files**
Add this at the beginning of each cron file:
```php
$cron_file = basename(__FILE__);
$check = $db->query("SELECT cron_active FROM cron_config WHERE cron_file='$cron_file'");
if($db->num_rows($check) > 0) {
    $cron = $db->fetch_row($check);
    if(!$cron['cron_active']) {
        exit; // Cron is disabled
    }
}
```

**Option 2: Check in cron scheduler**
Before executing a cron, check if it's active in the database.

## Benefits
- ✅ No need to edit crontab or Windows Task Scheduler
- ✅ Can temporarily disable problematic crons
- ✅ Test crons manually without waiting for schedule
- ✅ Track when crons last ran
- ✅ All management from web interface

## Security
- Only accessible to admin users (user_level <= 2)
- All actions are logged via `stafflog_add()`
- Protected by session authentication
- No direct file system access required

## Future Enhancements
- Add cron execution logs
- Add email notifications on cron failures
- Add cron performance metrics
- Add ability to change cron intervals
- Add cron dependency management
