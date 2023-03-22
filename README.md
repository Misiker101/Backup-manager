<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Backup-panel
a laravel project used to backup database and files.

## How to backup
for now by using the terminal write
```laravel
php artisan backup:run
```
open using the browser
```
php artisan serve
```


the backup_panel is found on the route `/home`

## How to customize
first make application by going to `https://console.developers.google.com/` 
setup `Google API` and from OAuth credentials get a `json` file copy it into `.env` as following

update your `.env` file

```
GOOGLE_DRIVE_CLIENT_ID=xxx.apps.googleusercontent.com
GOOGLE_DRIVE_CLIENT_SECRET=xxx
GOOGLE_DRIVE_REFRESH_TOKEN=xxx
GOOGLE_DRIVE_FOLDER_ID=null
```

configure the database, create a database called `backup_user`

```
DB_DATABASE=backup_user
DB_USERNAME=root
```
## To determine who can access Laravel Backup Panel in non-local environments.

go to `app/providers/LaravelBackupPanelServiceProviders` and add user email in `gate()` function

## For daily backup
in `app/console/kernel.php` add the following or uncomment
```
$schedule->command('backup:clean')->dailyAt('01:30');
$schedule->command('backup:run --only-db')->dailyAt('01:35');
```
