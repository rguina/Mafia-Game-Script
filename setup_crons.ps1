# Setup Cron Jobs for Mafia Game
# Run this script as Administrator

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Mafia Game - Cron Jobs Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if running as administrator
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
if (-not $isAdmin) {
    Write-Host "ERROR: This script must be run as Administrator!" -ForegroundColor Red
    Write-Host "Right-click PowerShell and select 'Run as Administrator'" -ForegroundColor Yellow
    pause
    exit
}

Write-Host "Creating scheduled tasks..." -ForegroundColor Green
Write-Host ""

# Task 1: Cron Minute
Write-Host "[1/4] Creating 'Mafia Game - Cron Minute' (every minute)..." -ForegroundColor Yellow
$action = New-ScheduledTaskAction -Execute "C:\laragon\www\Mafia-Game-Script\cron_minute.bat"
$trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Minutes 1) -RepetitionDuration ([TimeSpan]::MaxValue)
try {
    Unregister-ScheduledTask -TaskName "Mafia Game - Cron Minute" -Confirm:$false -ErrorAction SilentlyContinue
    Register-ScheduledTask -TaskName "Mafia Game - Cron Minute" -Action $action -Trigger $trigger -Description "Updates jail, hospital, travel, bodyguard every minute" | Out-Null
    Write-Host "  ✓ Created successfully" -ForegroundColor Green
} catch {
    Write-Host "  ✗ Error: $_" -ForegroundColor Red
}

# Task 2: Cron Five Minutes
Write-Host "[2/4] Creating 'Mafia Game - Cron Five' (every 5 minutes)..." -ForegroundColor Yellow
$action = New-ScheduledTaskAction -Execute "C:\laragon\www\Mafia-Game-Script\cron_five.bat"
$trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Minutes 5) -RepetitionDuration ([TimeSpan]::MaxValue)
try {
    Unregister-ScheduledTask -TaskName "Mafia Game - Cron Five" -Confirm:$false -ErrorAction SilentlyContinue
    Register-ScheduledTask -TaskName "Mafia Game - Cron Five" -Action $action -Trigger $trigger -Description "Updates energy, will, health, brave every 5 minutes" | Out-Null
    Write-Host "  ✓ Created successfully" -ForegroundColor Green
} catch {
    Write-Host "  ✗ Error: $_" -ForegroundColor Red
}

# Task 3: Cron Hourly
Write-Host "[3/4] Creating 'Mafia Game - Cron Hour' (every hour)..." -ForegroundColor Yellow
$action = New-ScheduledTaskAction -Execute "C:\laragon\www\Mafia-Game-Script\cron_hour.bat"
$trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Hours 1) -RepetitionDuration ([TimeSpan]::MaxValue)
try {
    Unregister-ScheduledTask -TaskName "Mafia Game - Cron Hour" -Confirm:$false -ErrorAction SilentlyContinue
    Register-ScheduledTask -TaskName "Mafia Game - Cron Hour" -Action $action -Trigger $trigger -Description "Hourly game maintenance" | Out-Null
    Write-Host "  ✓ Created successfully" -ForegroundColor Green
} catch {
    Write-Host "  ✗ Error: $_" -ForegroundColor Red
}

# Task 4: Cron Daily
Write-Host "[4/4] Creating 'Mafia Game - Cron Day' (daily at midnight)..." -ForegroundColor Yellow
$action = New-ScheduledTaskAction -Execute "C:\laragon\www\Mafia-Game-Script\cron_day.bat"
$trigger = New-ScheduledTaskTrigger -Daily -At "00:00"
try {
    Unregister-ScheduledTask -TaskName "Mafia Game - Cron Day" -Confirm:$false -ErrorAction SilentlyContinue
    Register-ScheduledTask -TaskName "Mafia Game - Cron Day" -Action $action -Trigger $trigger -Description "Daily game resets and maintenance" | Out-Null
    Write-Host "  ✓ Created successfully" -ForegroundColor Green
} catch {
    Write-Host "  ✗ Error: $_" -ForegroundColor Red
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Setup Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Scheduled tasks created:" -ForegroundColor White
Get-ScheduledTask | Where-Object {$_.TaskName -like "*Mafia Game*"} | Format-Table -Property TaskName, State

Write-Host ""
Write-Host "To verify a task is working, run:" -ForegroundColor Yellow
Write-Host "  C:\laragon\www\Mafia-Game-Script\cron_minute.bat" -ForegroundColor Cyan
Write-Host ""
Write-Host "To view tasks in Task Scheduler, press Win+R and type:" -ForegroundColor Yellow
Write-Host "  taskschd.msc" -ForegroundColor Cyan
Write-Host ""

pause
