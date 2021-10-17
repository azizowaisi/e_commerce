#!/bin/bash

composer dump-autoload --optimize --classmap-authoritative

php bin/console cache:clear --env=dev --no-warmup;
php bin/console cache:clear --env=prod --no-warmup;

chmod -R 777 var/cache


