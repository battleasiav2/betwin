# BET369WIN

BET369WIN (Laravel / sunfyre) for Hostinger.

## Database
- DB_HOST=`localhost`
- DB_DATABASE=`u811189100_betwin`
- DB_USERNAME=`u811189100_betwin`
- DB_PASSWORD= only in server `core/.env` (never commit)

## Auto deploy (GitHub → Hostinger)

**Full guide:** [AUTO-DEPLOY.md](./AUTO-DEPLOY.md)

**Problem found:** repo te GitHub webhook **0** chilo — Hostinger auto-pull impossible.

**Fix (one-time):**
1. Hostinger → Advanced → Git → Auto Deployment → Webhook URL copy  
2. `.\setup-auto-deploy.ps1 -WebhookUrl "PASTE_URL"`  
3. Then every `git push origin main` → Hostinger pulls

Daily: `push-to-hostinger.bat`

### Server first-time
Create `core/.env` from `core/env.hostinger.example` (File Manager). Never commit `.env`.
