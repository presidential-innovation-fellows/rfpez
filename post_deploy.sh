# to run: "sh post_deploy.sh (laravel environment name)"
#! /bin/sh

composer self-update
composer update
composer install
php artisan migrate --env=$1
php artisan seed:production --env=$1
php artisan increment_deploy_timestamp

if [ "$1" != "ec2" ] ; then
  cp public/.htaccess_ssl public/.htaccess
fi