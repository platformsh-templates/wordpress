# WordPress for Platform.sh

This template builds WordPress on Platform.sh using the [`johnbolch/wordpress`](https://github.com/johnpbloch/wordpress) "Composer Fork" of WordPress.  Plugins and themes should be managed with Composer exclusively.

WordPress is a blogging and lightweight CMS written in PHP.

## Services

* PHP 7.3
* MariaDB 10.2

## Post-install

1. Run through the WordPress installer as normal.  You will not be asked for database credentials as those are already provided.

2. This example looks for an optional `wp-config-local.php` in the project root that you can use to develop locally. This file is ignored in git.

Example `wp-config-local.php`:

```php
<?php

define('WP_HOME', "http://localhost");
define('WP_SITEURL',"http://localhost");
define('DB_NAME', "my_wordpress");
define('DB_USER', "user");
define('DB_PASSWORD', "a strong password");
define('DB_HOST', "127.0.0.1");
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

// These will be set automatically on Platform.sh to a different value, but that won't cause issues.
define('AUTH_KEY', 'SECURE_AUTH_KEY');
define('LOGGED_IN_KEY', 'LOGGED_IN_SALT');
define('NONCE_KEY', 'NONCE_SALT');
define('AUTH_SALT', 'SECURE_AUTH_SALT');
```

## Customizations

The following changes have been made relative to WordPress as it is downloaded from WordPress.org.  If using this project as a reference for your own existing project, replicate the changes below to your project.

* It uses the [`johnbolch/wordpress`](https://github.com/johnpbloch/wordpress) "Composer Fork" of WordPress, which allow the site to be managed entirely with Composer.
* The `.platform.app.yaml`, `.platform/services.yaml`, and `.platform/routes.yaml` files have been added.  These provide Platform.sh-specific configuration and are present in all projects on Platform.sh.  You may customize them as you see fit.
* The `.platform.template.yaml` file contains information needed by Platform.sh's project setup process for templates.  It may be safely ignored or removed.
* An additional Composer library, [`platformsh/config-reader`](https://github.com/platformsh/config-reader-php), has been added.  It provides convenience wrappers for accessing the Platform.sh environment variables.
* The `web/wp-config.php` file has been modified to use the Config Reader to configure WordPress based on Platform.sh environment variables if present.  If not, your own `wp-config-local.php` file will be loaded to configure the site for local development.

## References

* [WordPress](https://wordpress.org/)
* [WordPress on Platform.sh](https://docs.platform.sh/frameworks/wordpress.html)
* [PHP on Platform.sh](https://docs.platform.sh/languages/php.html)
