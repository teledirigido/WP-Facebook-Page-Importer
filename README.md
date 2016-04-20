# WP-Facebook-Page-Importer

Import data from your facebook page to your Wordpress site.

### How To 

1. Create a cron and import your Facebook posts:
```
#!/usr/local/bin/php -q
<?php
include('wp-load.php');
FPI_SDK::init_import();
```

Make sure all your credentials are correct on your plugin configuration file before running your cron.

Under development.
