$mysqlPath = "C:\xampp\mysql\bin\mysql.exe"
& $mysqlPath -u root -e "CREATE DATABASE retail;"
Write-Host "Database 'retail' created successfully."
