# BET369WIN — Hostinger Auto Deploy (one-time)

GitHub repo te **kono webhook chilo na** — tai `git push` holeo Hostinger update hotto na.

## One-time setup (2 minute)

### 1) Hostinger webhook URL
1. hPanel → **bet369win.com** → **Advanced** → **Git**
2. Repo: `battleasiav2/betwin`, branch `main`, folder `public_html`
3. **⋯ / Actions** → **Auto Deployment** → **Webhook URL** copy

### 2) PC te run
```powershell
cd C:\Users\sumon\Desktop\client\betwin369\REDJILI9876\web\ck444.rapidverse.site\public_html\red
$env:GH_PUSH_TOKEN = "battleasiav2_PAT_with_repo_scope"
.\setup-auto-deploy.ps1 -WebhookUrl "PASTE_HOSTINGER_WEBHOOK_HERE"
```

Script GitHub e **push webhook** add kore. Tarpor `git push origin main` → Hostinger auto pull.

### 3) Test
```powershell
git commit --allow-empty -m "chore: test auto-deploy"
git push origin main
```
Hostinger Git → Deployments e notun deploy dekhabe. Live e FOUC fix (`critical-theme-color`) thakle pull OK.

---

## Optional: GitHub Actions FTP
PAT e `workflow` scope thakle `deploy/github-actions-deploy-hostinger.yml` file ke `.github/workflows/deploy-hostinger.yml` path e copy kore push korun. Secrets: `FTP_HOST`, `FTP_USER`, `FTP_PASSWORD` ba `HOSTINGER_DEPLOY_WEBHOOK`.

## Note
`core/.env` server e thakbe — deploy overwrite korbe na (gitignored).
