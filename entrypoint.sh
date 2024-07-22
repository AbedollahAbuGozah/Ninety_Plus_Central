#!/bin/sh

# Navigate to the project directory
cd /var/www/html

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Keep the container running
tail -f /dev/null
