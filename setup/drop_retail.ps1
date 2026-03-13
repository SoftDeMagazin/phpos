$mysqlPath = "C:\xampp\mysql\bin\mysql.exe"
& $mysqlPath -u root -e "DROP DATABASE IF EXISTS retail;"
Write-Host "Database 'retail' dropped successfully."
