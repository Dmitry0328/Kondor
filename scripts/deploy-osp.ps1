param(
    [switch]$InstallDependencies
)

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

function Invoke-External {
    param(
        [Parameter(Mandatory = $true)]
        [string]$FilePath,

        [Parameter()]
        [string[]]$ArgumentList = @(),

        [Parameter(Mandatory = $true)]
        [string]$Label
    )

    Write-Host "==> $Label" -ForegroundColor Cyan
    & $FilePath @ArgumentList

    if ($LASTEXITCODE -ne 0) {
        throw "Command failed with exit code ${LASTEXITCODE}: $FilePath $($ArgumentList -join ' ')"
    }
}

function Resolve-OspPhpPath {
    param(
        [Parameter(Mandatory = $true)]
        [string]$ProjectRoot
    )

    $projectIni = Join-Path $ProjectRoot '.osp\project.ini'

    if (-not (Test-Path -LiteralPath $projectIni)) {
        throw "Missing OSPanel project file: $projectIni"
    }

    $engineLine = Select-String -Path $projectIni -Pattern '^\s*php_engine\s*=\s*(.+?)\s*$' | Select-Object -First 1

    if (-not $engineLine) {
        throw "Could not resolve php_engine from $projectIni"
    }

    $engineName = $engineLine.Matches[0].Groups[1].Value.Trim()
    $phpPath = Join-Path 'D:\OSPanel\modules' "$engineName\PHP\php.exe"

    if (-not (Test-Path -LiteralPath $phpPath)) {
        throw "Resolved PHP executable does not exist: $phpPath"
    }

    return $phpPath
}

$projectRoot = (Resolve-Path (Join-Path $PSScriptRoot '..')).Path
$phpPath = Resolve-OspPhpPath -ProjectRoot $projectRoot
$phpDir = Split-Path -Parent $phpPath
$composerBat = Join-Path $phpDir 'composer.bat'
$publicStoragePath = Join-Path $projectRoot 'public\storage'

Write-Host "Project root: $projectRoot"
Write-Host "PHP: $phpPath"

Push-Location $projectRoot

try {
    if ($InstallDependencies) {
        if (-not (Test-Path -LiteralPath $composerBat)) {
            throw "Composer wrapper not found: $composerBat"
        }

        Invoke-External -FilePath $composerBat -ArgumentList @(
            'install',
            '--no-interaction',
            '--prefer-dist',
            '--optimize-autoloader'
        ) -Label 'Installing Composer dependencies'
    }

    Invoke-External -FilePath $phpPath -ArgumentList @('artisan', 'migrate', '--force') -Label 'Running database migrations'

    if (Test-Path -LiteralPath $publicStoragePath) {
        Write-Host "==> Storage symlink already exists" -ForegroundColor Cyan
    }
    else {
        Invoke-External -FilePath $phpPath -ArgumentList @('artisan', 'storage:link') -Label 'Creating public storage symlink'
    }

    Invoke-External -FilePath $phpPath -ArgumentList @('artisan', 'optimize:clear') -Label 'Clearing Laravel caches'
    Invoke-External -FilePath $phpPath -ArgumentList @('artisan', 'optimize') -Label 'Rebuilding Laravel caches'
    Invoke-External -FilePath $phpPath -ArgumentList @('artisan', 'about') -Label 'Printing application summary'
}
finally {
    Pop-Location
}
