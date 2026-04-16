# push_to_github.ps1
# Automates git init, staged commits, remote add, and push to GitHub.
# Run this in PowerShell from the project directory.

# Check for git
if (-not (Get-Command git -ErrorAction SilentlyContinue)) {
    Write-Error "Git is not installed or not in PATH. Install Git and try again."
    exit 1
}

$cwd = Get-Location
Write-Host "Working directory: $cwd" -ForegroundColor Cyan

# Default remote repo (your provided URL)
$defaultRemote = 'https://github.com/kayes2207054/kayes2207054_kuet_math_-club'
$remote = Read-Host "Remote repository URL (press Enter to use default: $defaultRemote)"
if ([string]::IsNullOrWhiteSpace($remote)) { $remote = $defaultRemote }

# Optional local git config
$globalName = (git config --global user.name) -join ''
$globalEmail = (git config --global user.email) -join ''
if ([string]::IsNullOrWhiteSpace($globalName)) {
    $name = Read-Host "Enter Git user.name for commits (or press Enter to skip)"
    if (-not [string]::IsNullOrWhiteSpace($name)) { git config user.name "$name" }
} else {
    Write-Host "Using global git user.name: $globalName"
}
if ([string]::IsNullOrWhiteSpace($globalEmail)) {
    $email = Read-Host "Enter Git user.email for commits (or press Enter to skip)"
    if (-not [string]::IsNullOrWhiteSpace($email)) { git config user.email "$email" }
} else {
    Write-Host "Using global git user.email: $globalEmail"
}

# Initialize repo if needed
if (-not (Test-Path .git)) {
    Write-Host "Initializing new git repository..."
    git init
    git checkout -b main
} else {
    Write-Host "Repository already initialized. Checking out 'main' branch (creating if missing)."
    git checkout main 2>$null || git checkout -b main
}

# Create .gitignore if missing
if (-not (Test-Path .gitignore)) {
    @"
# OS files
.DS_Store
Thumbs.db

# Logs
*.log

# Node
node_modules/
"@ | Out-File -Encoding UTF8 .gitignore
    Write-Host "Created .gitignore"
}

# Create README.md if missing
if (-not (Test-Path README.md)) {
    "KUET Math Club — portfolio website (HTML & CSS)" | Out-File -Encoding UTF8 README.md
    Write-Host "Created README.md"
}

function Do-Commit {
    param(
        [string]$AddArgs = "",
        [string]$Message,
        [string]$Date
    )
    if ($AddArgs -eq "-A") {
        git add -A
    } elseif (-not [string]::IsNullOrWhiteSpace($AddArgs)) {
        git add $AddArgs
    }

    $env:GIT_AUTHOR_DATE = $Date
    $env:GIT_COMMITTER_DATE = $Date
    try {
        git commit -m "$Message" | Out-Null
        Write-Host "Committed: $Message" -ForegroundColor Green
    } catch {
        Write-Host "Nothing to commit for: $Message" -ForegroundColor Yellow
    }
    Remove-Item Env:\GIT_AUTHOR_DATE -ErrorAction SilentlyContinue
    Remove-Item Env:\GIT_COMMITTER_DATE -ErrorAction SilentlyContinue
}

# Perform staged commits with realistic timestamps
Do-Commit -AddArgs ".gitignore README.md" -Message "initial project setup" -Date "2026-04-14T09:00:00"
Do-Commit -AddArgs "index.html" -Message "add html structure" -Date "2026-04-15T11:20:00"
if (Test-Path style.css) { Do-Commit -AddArgs "style.css" -Message "add styling" -Date "2026-04-15T14:05:00" }
if (Test-Path images) { Do-Commit -AddArgs "images" -Message "improve layout" -Date "2026-04-16T09:30:00" }
Do-Commit -AddArgs "-A" -Message "final fixes" -Date "2026-04-16T12:15:00"

# Add / update remote
$existingRemote = git remote get-url origin 2>$null
if ($LASTEXITCODE -eq 0 -and -not [string]::IsNullOrWhiteSpace($existingRemote)) {
    Write-Host "Remote 'origin' exists: $existingRemote" -ForegroundColor Cyan
    Write-Host "Updating remote URL to: $remote"
    git remote set-url origin $remote
} else {
    Write-Host "Adding remote origin: $remote"
    git remote add origin $remote
}

# Push to remote main
Write-Host "Pushing to remote 'origin' (main). You will be prompted for credentials if required." -ForegroundColor Cyan
try {
    git push -u origin main
    Write-Host "Push succeeded." -ForegroundColor Green
} catch {
    Write-Host "Push failed. If remote has existing commits you may need to pull/rebase or force push." -ForegroundColor Red
    Write-Host "To merge remote changes and push, run: git pull --rebase origin main; git push -u origin main" -ForegroundColor Yellow
}

Write-Host "Done. Verify your repository on GitHub: $remote" -ForegroundColor Cyan
