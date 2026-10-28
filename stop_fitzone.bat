@echo off
title FitZone Gym - Server Shutdown
echo ==================================================
echo   Stopping FitZone Gym Servers (Apache + MySQL)
echo ==================================================
echo.

:: 1. Stop Apache Web Server
echo [*] Stopping Apache Web Server (httpd.exe)...
taskkill /F /IM httpd.exe >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] Apache stopped successfully.
) else (
    echo [INFO] Apache was not running.
)

:: 2. Gracefully Stop MySQL Server
echo [*] Stopping MySQL Database Server (mysqld.exe)...
if exist "C:\xampp\mysql\bin\mysqladmin.exe" (
    "C:\xampp\mysql\bin\mysqladmin.exe" -u root shutdown >nul 2>&1
)
timeout /t 1 /nobreak >nul

:: Ensure any remaining mysqld processes are terminated
taskkill /F /IM mysqld.exe >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] MySQL stopped successfully.
) else (
    echo [INFO] MySQL was not running.
)

echo.
echo ==================================================
echo   All servers and background processes stopped!
echo ==================================================
echo.
timeout /t 2 /nobreak >nul
