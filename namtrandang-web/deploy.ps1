<#
    Deploy child theme len namtrandang.com qua FTPS.

    Dung session da luu trong WinSCP (mat khau nam trong kho cua WinSCP,
    KHONG co trong file nay). Duong dan tu suy ra tu vi tri script nen
    copy thu muc di dau cung chay duoc.

    Cach dung:
        .\deploy.ps1              # sao luu ban tren server roi day file moi len
        .\deploy.ps1 -NoBackup    # bo qua buoc sao luu (nhanh hon)
        .\deploy.ps1 -Delete      # xoa luon file thua tren server cho khop may local
#>

param(
    [switch]$NoBackup,
    [switch]$Delete
)

$ErrorActionPreference = 'Stop'

# --- Cau hinh ---
$Session   = "deloy@namtrandang.com"   # ten site da luu trong WinSCP
$RemoteDir = "/flatsome-child"         # thu muc goc cua tai khoan FTP la wp-content/themes/

# --- Duong dan (tu dong theo vi tri script) ---
$Root       = $PSScriptRoot
$LocalTheme = Join-Path $Root "flatsome-child"
$BackupRoot = Join-Path $Root "backup-remote"

# --- Tim WinSCP ---
$WinScpCandidates = @(
    "$env:ProgramFiles\WinSCP\WinSCP.com",
    "${env:ProgramFiles(x86)}\WinSCP\WinSCP.com",
    "$env:LOCALAPPDATA\Programs\WinSCP\WinSCP.com"
)
$WinScp = $WinScpCandidates | Where-Object { Test-Path $_ } | Select-Object -First 1

if (-not $WinScp) {
    $cmd = Get-Command winscp.com -ErrorAction SilentlyContinue
    if ($cmd) { $WinScp = $cmd.Source }
}

if (-not $WinScp)            { throw "Khong tim thay WinSCP.com. Cai WinSCP truoc." }
if (-not (Test-Path $LocalTheme)) { throw "Khong tim thay thu muc theme: $LocalTheme" }

Write-Host "WinSCP : $WinScp" -ForegroundColor DarkGray
Write-Host "Theme  : $LocalTheme" -ForegroundColor DarkGray

# --- Sao luu ban dang chay tren server ---
if (-not $NoBackup) {
    $stamp     = Get-Date -Format "yyyyMMdd-HHmm"
    $backupDir = Join-Path $BackupRoot $stamp
    New-Item -ItemType Directory -Force $backupDir | Out-Null

    Write-Host "Sao luu ban tren server -> $backupDir" -ForegroundColor Cyan

    & $WinScp /command `
        "option batch abort" `
        "option confirm off" `
        "open ""$Session""" `
        "lcd ""$backupDir""" `
        "get ""$RemoteDir/*""" `
        "exit"

    if ($LASTEXITCODE -ne 0) { throw "Sao luu that bai - dung lai, chua deploy gi ca." }
}

# --- Day file len ---
$syncArgs = if ($Delete) { "-delete" } else { "" }

Write-Host "Deploy $LocalTheme -> $RemoteDir" -ForegroundColor Cyan

& $WinScp /command `
    "option batch abort" `
    "option confirm off" `
    "open ""$Session""" `
    "synchronize remote $syncArgs ""$LocalTheme"" ""$RemoteDir""" `
    "exit"

if ($LASTEXITCODE -ne 0) { throw "Deploy that bai (exit code $LASTEXITCODE)." }

Write-Host "Xong. CSS/JS tu doi phien ban theo thoi diem sua file - khong can tang NLW_VERSION." -ForegroundColor Green
Write-Host "Neu dung LiteSpeed Cache: vao wp-admin purge cache sau khi deploy." -ForegroundColor Green
Write-Host "Mo https://namtrandang.com kiem tra site con chay binh thuong." -ForegroundColor Green
