@echo off
cd /d "%~dp0"
git add -A
set /p MSG=Commit message: 
if "%MSG%"=="" set MSG=Update site
git commit --trailer "Co-authored-by: Cursor <cursoragent@cursor.com>" -m "%MSG%"
git push origin main
echo.
echo Pushed. Hostinger auto-deploy should update now.
pause
