# BET369WIN — Hostinger Git Pull + Auto Deploy

**Full guide:** [HOSTINGER-GIT.md](HOSTINGER-GIT.md)

## Hostinger Git (pull) — paste these

| Field | Value |
|--------|--------|
| Repository | `https://github.com/battleasiav2/betwin.git` |
| Branch | `main` |
| Directory | `public_html/red` |

hPanel → **Advanced** → **Git** → create → **Deploy/Pull**.

## Auto-deploy webhook (optional, one-time)

1. Hostinger Git → **⋯** → **Auto Deployment** → Webhook URL copy  
2. PC:
```powershell
cd "C:\Users\sumon\Desktop\client\betwin369\New folder\REDJILI9876\web\ck444.rapidverse.site\public_html\red"
$env:GH_PUSH_TOKEN = "YOUR_GITHUB_PAT_repo_admin_repo_hook"
.\setup-auto-deploy.ps1 -WebhookUrl "PASTE_HOSTINGER_WEBHOOK_HERE"
```
3. Test: `git commit --allow-empty -m "chore: test auto-deploy"; git push origin main`

## Note
- `core/.env` gitignored — server e thakbe, pull overwrite korbe na  
- `*.sql` gitignored — dump `public_html/sql/` e rakhun
