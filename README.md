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
