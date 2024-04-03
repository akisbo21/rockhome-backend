#!/bin/bash

echo "DEV: $DEV"
echo "\n"

if [ $DEV ]
then
    cp /usr/php-dev.ini /usr/local/etc/php/conf.d/zzz-php-dev.ini
fi


service nginx start
php-fpm