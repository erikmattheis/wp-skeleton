# WordPress Skeleton

This is simply a skeleton repo for a WordPress site.

## Assumptions

* WordPress as a Git submodule in `/htdocs/`
* Custom content directory in `/content/` (cleaner, and also because it can't be in `/htdocs/`)
* Plug-in and themes are submodules, these are deploy and cannot be live update.
* `uploads` is a folder that is added to git ignore.
* `wp-config-sample.php` define the logic above and is template to overwrite default file.

## Deployment
```
git clone https://github.com/gsn/wp-skeleton.git
cp wp-skeleton /var/www --exclude .git wp-config.php
cd /var/www
cp wp-config-sample.php htdocs/wp-config-sample.php
```

## Update
* Update local repo with remote
* Make sure we don't override wp-config.php by accident
* Zip up current site
* Complete override of folder
```
tar czf $(date +%Y%m%d-%H%M%S).tar.gz /var/www/
cd wp-skeleton
git pull
rm -f htdocs/wp-config.php
yes | cp -rf wp-skeleton /var/www/ --exclude .git wp-config.php
```
