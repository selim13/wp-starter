# Minimal Wordpress starter theme

Based on [Timber 2](https://timber.github.io/docs/v2)

## Requirements:
* working installation of the wordpress
* composer 2
* advanced custom fields pro plugin

## Installation:

* Put the contents of `wordpress` directory inside your wordpress root directory.
* Put advanced custom fields pro plugin inside `/wp-content/mu-plugins/advanced-custom-fields-pro` directory.
* Install required libraries: `cd /wp-content/mu-plugins/local && composer install`
* Activate `timber-theme-bare` theme.
* Install optional plugins:
```
wp plugin install \
	cyr3lat \
    query-monitor \
	disable-emojis \
	duplicate-post \
	simple-custom-post-order \
	wordpress-seo \
	regenerate-thumbnails \
	post-smtp \
	safe-svg --activate
```