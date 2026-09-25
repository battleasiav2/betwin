# Hostinger Git Pull Setup (BET369WIN)

GitHub already ready: **https://github.com/battleasiav2/betwin** · branch **`main`**

Repo **public** — Hostinger without token-o pull korte parbe.

---

## hPanel e setup (ekbar)

1. **hPanel** → apnar website (`ck444.rapidverse.site` / bet369win) open korun  
2. **Advanced** → **Git**  
3. **Create new repository** / **Add repository**:

| Field | Value |
|--------|--------|
| Repository URL | `https://github.com/battleasiav2/betwin.git` |
| Branch | `main` |
| Install path / Directory | `public_html/red` |

> Site jodi seedha `public_html` e thake (ar `red` folder na), path din: `public_html`  
> Apnar current structure: `public_html/red` — tai **`public_html/red`** use korun.

4. **Deploy** / **Pull** click korun (first time full clone/pull hobe)

5. Optional — **Auto Deployment**:
   - Git page e **⋯** → **Auto Deployment** → Webhook URL copy
   - PC te:
     ```powershell
     cd "C:\Users\sumon\Desktop\client\betwin369\New folder\REDJILI9876\web\ck444.rapidverse.site\public_html\red"
     $env:GH_PUSH_TOKEN = "YOUR_GITHUB_PAT"
     .\setup-auto-deploy.ps1 -WebhookUrl "PASTE_HOSTINGER_WEBHOOK_HERE"
     ```
   - Tarpor `git push origin main` → Hostinger auto pull

---

## Pull er por server e check

SSH / File Manager theke:

```bash
cd ~/domains/YOUR_DOMAIN/public_html/red   # path adjust korun
# .env thakte hobe (git e nai — overwrite hobe na)
ls core/.env
php core/artisan config:clear
php core/artisan view:clear
php core/artisan cache:clear
```

---

## Important

| Item | Note |
|------|------|
| `core/.env` | Gitignored — server e alada thakbe, pull overwrite korbe na |
| `*.sql` | Gitignored — dump `public_html/sql/` e rakhun, `red/` e na |
| Branch | Always **`main`** |
| Latest commit | `04c64ba` — Bet369win Clean Dark import |

---

## Local theke update → Hostinger

```powershell
cd "C:\Users\sumon\Desktop\client\betwin369\New folder\REDJILI9876\web\ck444.rapidverse.site\public_html\red"
git add -A
git commit -m "your message"
git push origin main
```

Auto-deploy on thakle Hostinger nije pull korbe.  
Na thakle hPanel → **Git** → **Pull** / **Deploy**.
