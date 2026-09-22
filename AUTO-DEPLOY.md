# BET369WIN — Hostinger Auto Deploy (one-time)

GitHub repo e **kono webhook chilo na** — tai push holeo Hostinger pull hotto na.

## Option A (recommended): Hostinger webhook + GitHub Actions

### 1) Hostinger
1. hPanel → website **bet369win.com** → **Advanced** → **Git**
2. Repo already connected thakle: **Actions** (⋯) → **Auto Deployment**
3. **Webhook URL** copy korun
4. Branch = `main`, Deploy directory = `public_html` (repo root / index.php)

### 2) GitHub secret
Repo `battleasiav2/betwin` → **Settings** → **Secrets and variables** → **Actions** → **New repository secret**:

| Name | Value |
|------|--------|
| `HOSTINGER_DEPLOY_WEBHOOK` | Hostinger theke copy kora webhook URL |

### 3) Or run on PC (same effect)
```powershell
cd path\to\red
.\setup-auto-deploy.ps1 -WebhookUrl "PASTE_HOSTINGER_WEBHOOK_HERE"
```

Eta GitHub e push webhook + Actions secret set kore.

### 4) Test
```bash
git push origin main
```
GitHub → **Actions** → "Deploy to Hostinger" green hole deploy trigger hoyeche.
Hostinger Git page e Deployments log check korun.

---

## Option B: FTP auto-deploy (webhook na thakle)

hPanel → **Files** → **FTP Accounts** theke host/user/pass nin. GitHub Actions secrets:

| Name | Example |
|------|---------|
| `FTP_HOST` | `ftp.bet369win.com` or Hostinger FTP hostname |
| `FTP_USER` | your FTP username |
| `FTP_PASSWORD` | FTP password |
| `FTP_SERVER_DIR` | `./` or `/public_html/` (optional) |

Push to `main` → Actions FTP sync (`.env` exclude thake).

---

## Important
- `core/.env` **kokhono** git/FTP te overwrite hobe na (exclude kora)
- Auto deploy = code update; DB password alada File Manager e thakbe
