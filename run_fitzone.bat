@echo off
title FitZone Gym - Server Starter & Manager
echo ==================================================
echo   Starting FitZone Gym Server (Apache + MySQL)
echo ==================================================
echo.

cd /d C:\xampp

:: 1. Start MySQL if not running
tasklist /fi "imagename eq mysqld.exe" | find /i "mysqld.exe" >nul
if errorlevel 1 (
    echo [*] Starting MySQL Database Server...
    start "" /b mysql\bin\mysqld.exe --defaults-file=mysql\bin\my.ini --standalone
    timeout /t 2 /nobreak >nul
) else (
    echo [OK] MySQL is already running.
)

:: 2. Start Apache if not running
tasklist /fi "imagename eq httpd.exe" | find /i "httpd.exe" >nul
if errorlevel 1 (
    echo [*] Starting Apache Web Server...
    start "" /b apache\bin\httpd.exe
    timeout /t 2 /nobreak >nul
) else (
    echo [OK] Apache is already running.
)

echo.
echo ==================================================
echo   FitZone is LIVE!
echo   URL: http://localhost/fitzone-gym-website/index.php
echo ==================================================
echo.
echo Opening in your default browser...
start http://localhost/fitzone-gym-website/index.php
echo.
echo --------------------------------------------------
echo   HOW TO STOP SERVERS:
echo   - Press any key below to STOP everything and exit.
echo   - OR run "stop_fitzone.bat" anytime.
echo --------------------------------------------------
echo.
pause

echo.
echo [*] Shutting down Apache...
taskkill /F /IM httpd.exe >nul 2>&1

echo [*] Shutting down MySQL...
if exist "C:\xampp\mysql\bin\mysqladmin.exe" (
    "C:\xampp\mysql\bin\mysqladmin.exe" -u root shutdown >nul 2>&1
)
taskkill /F /IM mysqld.exe >nul 2>&1

echo [OK] All servers have been stopped cleanly!
timeout /t 2 >nul
