$mysqlPath = "C:\xampp\mysql\bin\mysql.exe"
& $mysqlPath -u root -e "use retail; source C:/xampp/htdocs/db/retail_empty.sql;"
Write-Host "Database 'retail' structure loaded successfully."
