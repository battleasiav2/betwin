# BET369WIN

Laravel (Xaxino / sunfyre) for Hostinger.

## Database
- DB_HOST=`localhost`
- DB_DATABASE=`u811189100_betwin`
- DB_USERNAME=`u811189100_betwin`
- DB_PASSWORD= only in server `core/.env` (never commit)

## Auto deploy (GitHub → Hostinger)

**Full guide:** [AUTO-DEPLOY.md](./AUTO-DEPLOY.md)

One-time (required — repo previously had **zero** webhooks):

1. Hostinger → Advanced → **Git** → **Auto Deployment** → copy Webhook URL  
2. Run:
   ```powershell
   $env:GH_PUSH_TOKEN="your_pat"
   .\setup-auto-deploy.ps1 -WebhookUrl "PASTE_WEBHOOK_URL"
   ```
   Or add GitHub secret `HOSTINGER_DEPLOY_WEBHOOK` manually.  
3. Push to `main` → GitHub **Actions** runs "Deploy to Hostinger"

Optional FTP secrets: `FTP_HOST`, `FTP_USER`, `FTP_PASSWORD`.

### Daily update
```bat
push-to-hostinger.bat
```
or `git push origin main`

### Server first-time
Create `core/.env` from `core/env.hostinger.example` (File Manager). Never commit `.env`.
