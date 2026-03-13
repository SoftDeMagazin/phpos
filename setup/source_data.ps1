$mysqlPath = "C:\xampp\mysql\bin\mysql.exe"
$dataDir = "C:/xampp/htdocs/db/data"

$sqlFiles = Get-ChildItem -Path $dataDir -Filter "*.sql" | Sort-Object Name

foreach ($file in $sqlFiles) {
    Write-Host "Running $($file.Name)..."
    & $mysqlPath -u root -e "use retail; source $dataDir/$($file.Name);"
}

Write-Host "All data loaded successfully."
