@echo off
REM Cron Five Minutes - Executes every 5 minutes
REM Updates: energy, will, health, brave

cd /d C:\laragon\www\Mafia-Game-Script
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe cron_run_five.php
