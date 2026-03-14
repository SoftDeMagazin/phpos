# PhpOS Installation Script
# Run as Administrator: powershell -ExecutionPolicy Bypass -File install.ps1

$ErrorActionPreference = "Stop"

function Write-Step { param([string]$msg) Write-Host "`n=== $msg ===" -ForegroundColor Cyan }
function Write-Ok   { param([string]$msg) Write-Host "  [OK] $msg" -ForegroundColor Green }
function Write-Fail { param([string]$msg) Write-Host "  [FAIL] $msg" -ForegroundColor Red }

# --- 1. Check prerequisites ---

Write-Step "Checking prerequisites"

# Check XAMPP
if (Test-Path "C:\xampp\xampp-control.exe") {
    Write-Ok "XAMPP is installed at C:\xampp"
} else {
    Write-Fail "XAMPP not found at C:\xampp"
    exit 1
}

# Check Apache binary
if (Test-Path "C:\xampp\apache\bin\httpd.exe") {
    Write-Ok "Apache found"
} else {
    Write-Fail "Apache not found in C:\xampp\apache"
    exit 1
}

# Check MySQL binary
if (Test-Path "C:\xampp\mysql\bin\mysql.exe") {
    Write-Ok "MySQL found"
} else {
    Write-Fail "MySQL not found in C:\xampp\mysql"
    exit 1
}

# Check Git
try {
    $gitVersion = & git --version 2>&1
    Write-Ok "Git is available: $gitVersion"
} catch {
    Write-Fail "Git is not installed or not in PATH"
    exit 1
}

# Check Node.js
try {
    $nodeVersion = & node --version 2>&1
    Write-Ok "Node.js is available: $nodeVersion"
} catch {
    Write-Fail "Node.js is not installed or not in PATH"
    exit 1
}

# Check npm
try {
    $npmVersion = & npm --version 2>&1
    Write-Ok "npm is available: $npmVersion"
} catch {
    Write-Fail "npm is not installed or not in PATH"
    exit 1
}

# --- 2. Clone repository ---

Write-Step "Preparing C:\xampp\htdocs"

# Remove existing htdocs directory entirely
if (Test-Path "C:\xampp\htdocs") {
    Write-Host "  Removing existing htdocs directory..."
    Remove-Item -Path "C:\xampp\htdocs" -Recurse -Force
    Write-Ok "htdocs removed"
}

Write-Host "  Cloning repository..."
& git clone https://github.com/SoftDeMagazin/phpos "C:\xampp\htdocs"
if ($LASTEXITCODE -ne 0) {
    Write-Fail "Git clone failed"
    exit 1
}
Write-Ok "Repository cloned successfully"

# --- 3. Check MySQL is running ---

Write-Step "Checking MySQL service"

$mysqlPath = "C:\xampp\mysql\bin\mysql.exe"
try {
    & $mysqlPath -u root -e "SELECT 1;" 2>&1 | Out-Null
    if ($LASTEXITCODE -ne 0) { throw "MySQL not responding" }
    Write-Ok "MySQL is running"
} catch {
    Write-Fail "MySQL is not running. Please start MySQL from XAMPP Control Panel and try again."
    exit 1
}

# --- 4. Copy database config ---

Write-Step "Setting up database config"

$configSrc = "C:\xampp\htdocs\config\config.db.sample.php"
$configDst = "C:\xampp\htdocs\config\config.db.php"

if (Test-Path $configSrc) {
    Copy-Item -Path $configSrc -Destination $configDst -Force
    Write-Ok "Copied config.db.sample.php to config.db.php"
} else {
    Write-Fail "config.db.sample.php not found at $configSrc"
    exit 1
}

# --- 5. Run database setup scripts ---

Write-Step "Setting up database"

$setupDir = "C:\xampp\htdocs\setup"
$scripts = @(
    "drop_retail.ps1",
    "create_database.ps1",
    "source_structure.ps1",
    "source_changes.ps1",
    "source_data.ps1"
)

foreach ($script in $scripts) {
    $scriptPath = Join-Path $setupDir $script
    if (Test-Path $scriptPath) {
        Write-Host "  Running $script..."
        & $scriptPath
        if ($LASTEXITCODE -ne 0) {
            Write-Fail "$script failed"
            exit 1
        }
        Write-Ok "$script completed"
    } else {
        Write-Fail "$script not found at $scriptPath"
        exit 1
    }
}

# --- 5. Build Electron app ---

Write-Step "Setting up Electron kiosk app"

$electronDir = "C:\xampp\htdocs\electron-kiosk"

if (-not (Test-Path "$electronDir\package.json")) {
    Write-Fail "Electron app not found at $electronDir"
    exit 1
}

Write-Host "  Installing npm dependencies..."
Push-Location $electronDir
& npm install
if ($LASTEXITCODE -ne 0) {
    Pop-Location
    Write-Fail "npm install failed"
    exit 1
}
Write-Ok "npm dependencies installed"

Write-Host "  Building Electron app..."
& npm run build
if ($LASTEXITCODE -ne 0) {
    Pop-Location
    Write-Fail "Electron build failed"
    exit 1
}
Write-Ok "Electron app built successfully"
Pop-Location

# --- Done ---

Write-Host "`n"
Write-Host "========================================" -ForegroundColor Green
Write-Host "  Installation completed successfully!  " -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host "`nYou can now start the application from XAMPP Control Panel."
