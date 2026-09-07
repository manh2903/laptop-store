@echo off
title Laravel Backend Server
set "PATH=C:\php;%PATH%"
echo ==============================================
echo   Dang khoi chay Laravel Backend Server...
echo   Dia chi: http://127.0.0.1:8000
echo ==============================================
php artisan serve
pause
