@echo off
REM Cron Minute - Executes every minute
REM Updates: hospital, jail, travel time, bodyguard

cd /d C:\laragon\www\Mafia-Game-Script
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe cron_run_minute.php
