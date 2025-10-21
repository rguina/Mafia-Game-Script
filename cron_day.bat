@echo off
REM Cron Daily - Executes once per day
REM Updates: daily game updates, resets, and maintenance

cd /d C:\laragon\www\Mafia-Game-Script
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe cron_run_day.php
