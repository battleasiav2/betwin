# BET369WIN

Laravel (Xaxino / sunfyre) for Hostinger.

## Database
- DB_HOST=`localhost`
- DB_DATABASE=`u811189100_betwin`
- DB_USERNAME=`u811189100_betwin`
- DB_PASSWORD= only in server `core/.env` (never commit)

## Auto update: Git → Hostinger

### 1) Hostinger (one time)
1. hPanel → **Advanced** → **Git**
2. Repository: `https://github.com/battleasiav2/betwin.git`
3. Branch: `main`
4. Deploy path: site `public_html` (repo root has `index.php`)
5. Turn **Auto Deployment** ON

### 2) PC (every update)
Double-click `push-to-hostinger.bat` or:

```bash
git add -A
git commit --trailer "Co-authored-by: Cursor <cursoragent@cursor.com>" -m "your message"
git push origin main
```

Hostinger pulls automatically after push.

### Server first-time
Copy `core/.env.example` → `core/.env`, set DB password + APP_URL, then:
`php core/artisan key:generate`
