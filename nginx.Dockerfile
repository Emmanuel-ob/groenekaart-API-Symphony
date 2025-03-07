# Based on the offical image with latest nginx version
FROM nginx:alpine

# Expose the port nginx is reachable on
EXPOSE 80

# Application root dir
WORKDIR /var/www/html

# Remove nginx default server (website) definition
RUN rm /etc/nginx/conf.d/default.conf

# Configuration for Symfony
COPY docker/nginx/symfony.conf /etc/nginx/conf.d/symfony.conf
