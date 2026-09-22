@echo off
cd /d "%~dp0"
git add -A
set /p MSG=Commit message: 
if "%MSG%"=="" set MSG=Update site
git commit --trailer "Co-authored-by: Cursor <cursoragent@cursor.com>" -m "%MSG%"
git push origin main
if errorlevel 1 (
  echo Push failed. Check GitHub token / permission.
  pause
  exit /b 1
)
echo.
echo Pushed to main.
echo GitHub Actions "Deploy to Hostinger" should run now.
echo If site does not update: open AUTO-DEPLOY.md and set HOSTINGER_DEPLOY_WEBHOOK.
echo.
pause
