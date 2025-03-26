# Use the official PHP Apache image
FROM php:8.1-apache

# Set the working directory
WORKDIR /var/www/html

# Change Apache port to 8080 (common practice, matches your original)
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/' /etc/apache2/sites-available/000-default.conf

# --- OpenShift Permissions Fix ---
# Create a directory where PHP will attempt to write.
# Change its group ownership to root (GID 0).
# Make it group-writable (g+w) and readable/executable (g+rx).
# The setgid bit (g+s) ensures files created inherit the group GID 0.
RUN mkdir -p /var/www/html/writable_test && \
    chgrp -R 0 /var/www/html/writable_test && \
    chmod -R g+rwx /var/www/html/writable_test && \
    chmod g+s /var/www/html/writable_test
# --- End OpenShift Permissions Fix ---

# Simple PHP script to test file writing and show info
COPY index.php /var/www/html/index.php

# Expose the new port
EXPOSE 8080

# The base image already has a CMD/ENTRYPOINT to start Apache
