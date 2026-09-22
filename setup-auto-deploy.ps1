param(
    [Parameter(Mandatory = $true)]
    [string]$WebhookUrl,
    [string]$Repo = "battleasiav2/betwin",
    [string]$Token = $env:GH_PUSH_TOKEN
)

$ErrorActionPreference = "Stop"

if (-not $Token) {
    Write-Host "Set GH_PUSH_TOKEN env to a classic PAT with repo + admin:repo_hook scope, or pass -Token"
    Write-Host "Example: `$env:GH_PUSH_TOKEN='ghp_xxx'; .\setup-auto-deploy.ps1 -WebhookUrl 'https://...'"
    exit 1
}

$headers = @{
    Authorization = "Bearer $Token"
    Accept        = "application/vnd.github+json"
    "X-GitHub-Api-Version" = "2022-11-28"
}

# 1) Create / update repository webhook (Hostinger legacy auto-deploy)
$existing = Invoke-RestMethod -Uri "https://api.github.com/repos/$Repo/hooks" -Headers $headers
$hook = $existing | Where-Object { $_.config.url -eq $WebhookUrl } | Select-Object -First 1

if ($hook) {
    Write-Host "Webhook already exists id=$($hook.id)"
} else {
    $body = @{
        name   = "web"
        active = $true
        events = @("push")
        config = @{
            url          = $WebhookUrl
            content_type = "form"
            insecure_ssl = "0"
        }
    } | ConvertTo-Json -Depth 5

    $created = Invoke-RestMethod -Method Post -Uri "https://api.github.com/repos/$Repo/hooks" -Headers $headers -Body $body -ContentType "application/json"
    Write-Host "Created webhook id=$($created.id)"
}

# 2) Store as Actions secret (so workflow can also POST on push)
# GitHub requires libsodium for secrets API — use gh if available
$gh = Get-Command gh -ErrorAction SilentlyContinue
if ($gh) {
    $env:GH_TOKEN = $Token
    $WebhookUrl | & gh secret set HOSTINGER_DEPLOY_WEBHOOK --repo $Repo
    Write-Host "Set Actions secret HOSTINGER_DEPLOY_WEBHOOK"
} else {
    Write-Host "Install GitHub CLI (gh) to set Actions secret, or add manually in repo Settings → Secrets"
}

Write-Host ""
Write-Host "Done. Push to main should trigger Hostinger deploy."
Write-Host "Test: git commit --allow-empty -m `"chore: test auto-deploy`" ; git push origin main"
