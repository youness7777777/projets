# Use the official PHP image from Docker Hub
FROM php:8.0-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the contents of the local PHP app to the container
COPY . .

# Expose port 80 to access the app in a web browser
EXPOSE 80

# Enable Apache mod_rewrite (if needed)
RUN a2enmod rewrite
