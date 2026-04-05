# Deployment Notes

Last verified: 2026-04-05

## Current state

- Runtime: Laravel 13.1.1, PHP 8.3.6, Filament 5.4.1
- Local domain: `https://kondor`
- Web server: OSPanel + Apache
- Database: MySQL (`DB_HOST=MySQL-8.0`)
- Storage link: `public/storage` is already linked
- Migrations: all migrations are applied
- Health endpoint: `https://kondor/up` returns HTTP 200

## Important deployment facts

- Storefront pages load CSS and JS directly from `public/css` and `public/js`.
- `public/build` is not used by the current Blade templates, so Node/Vite is not required for the current deploy flow.
- The app uses database-backed sessions, cache, notifications, and queue configuration.
- Scheduled pruning commands are registered in `bootstrap/app.php`.
- As of this verification, the codebase does not contain any jobs or notifications implementing `ShouldQueue`, so a dedicated queue worker is not required for current behavior.

## Minimum runtime requirements

- PHP 8.3
- MySQL 8.x
- Writable:
  - `storage`
  - `bootstrap/cache`
- Public symlink:
  - `public/storage -> storage/app/public`

## Local OSPanel deploy

Use the helper script:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\deploy-osp.ps1
```

Optional full dependency refresh:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\deploy-osp.ps1 -InstallDependencies
```

What the script does:

1. Resolves the correct OSPanel `php.exe` from `.osp/project.ini`
2. Optionally runs Composer install
3. Runs `php artisan migrate --force`
4. Ensures `storage:link`
5. Clears and rebuilds Laravel caches
6. Prints `artisan about`

## Manual commands

If you need to run them yourself, current PHP path is:

```powershell
D:\OSPanel\modules\PHP-8.3\PHP\php.exe
```

Core commands:

```powershell
& 'D:\OSPanel\modules\PHP-8.3\PHP\php.exe' artisan migrate --force
& 'D:\OSPanel\modules\PHP-8.3\PHP\php.exe' artisan storage:link
& 'D:\OSPanel\modules\PHP-8.3\PHP\php.exe' artisan optimize:clear
& 'D:\OSPanel\modules\PHP-8.3\PHP\php.exe' artisan optimize
& 'D:\OSPanel\modules\PHP-8.3\PHP\php.exe' artisan about
```

## Scheduler / service

These scheduled commands are registered:

- `shared-carts:prune-expired`
- `shared-build-links:prune-expired`

For a real production host, run Laravel scheduler every minute:

```bash
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```

For Windows Task Scheduler, run the matching `php.exe` against:

```powershell
artisan schedule:run
```

## Notes for future changes

- If Blade templates switch back to `@vite(...)`, add Node.js and `npm run build` to the deploy flow.
- If any job, listener, or notification starts implementing `ShouldQueue`, add a persistent queue worker service.
