# WP-Facebook-Page-Importer

Import data from your Facebook page to your Wordpress site using the [Facebook PHP SDK](https://developers.facebook.com/docs/reference/php).

#### HOW TO

1. Create a cron and import your Facebook posts:
```
#!/usr/local/bin/php -q
<?php
include('wp-load.php');
FPI_SDK::init_import();
```

Make sure all your credentials are correct on your plugin configuration file before running your cron.

#### Notes

This plugin should be fully working but still under development.
