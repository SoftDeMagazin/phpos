$mysqlPath = "C:\xampp\mysql\bin\mysql.exe"
$changesDir = "C:/xampp/htdocs/db/changes"

$sqlFiles = Get-ChildItem -Path $changesDir -Filter "*.sql" | Sort-Object Name

foreach ($file in $sqlFiles) {
    Write-Host "Running $($file.Name)..."
    & $mysqlPath -u root -e "use retail; source $changesDir/$($file.Name);"
}

Write-Host "All changes applied successfully."
