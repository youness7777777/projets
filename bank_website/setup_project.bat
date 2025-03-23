@echo off
echo Copying project files to XAMPP htdocs...
if exist "C:\xampp\htdocs" (
    xcopy /E /I /Y "%~dp0" "C:\xampp\htdocs\bank_website\"
    echo Files copied successfully!
    echo You can now access your website at: http://localhost/bank_website/
) else (
    echo XAMPP not found! Please install XAMPP first.
    echo Download XAMPP from: https://www.apachefriends.org/download.html
)
pause
