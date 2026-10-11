@echo off
git add .
if "%~1"=="" (
    git commit -m "Manual update %date% %time%"
) else (
    git commit -m "%~1"
)
git push origin main
