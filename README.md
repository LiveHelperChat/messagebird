# messagebird

MessageBird whatsapp template sending functionality.

# Install

Install database
```
php cron.php -s site_admin -e messagebird -c cron/update_structure
```
or `doc/install.sql`

Setup cronjob to run every minute. It will do mass sending.

```
php cron.php -s site_admin -e messagebird -c cron/masssending
```

# SMS Support

Set

```
Developers -> API Settings -> STATUS REPORTS URL`
```

As incoming webhook. URL itself you will find in `Home -> System configuration -> Incoming webhooks` in `MessageBirdSMS` edit window.
