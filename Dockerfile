FROM php:8.0-apache

# Enable Apache's mod_rewrite module
RUN a2enmod rewrite

# Install mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN apt-get update &&\
    apt-get install --no-install-recommends --assume-yes --quiet ca-certificates curl git &&\
    rm -rf /var/lib/apt/lists/*
RUN curl -Lsf 'https://storage.googleapis.com/golang/go1.8.3.linux-amd64.tar.gz' | tar -C '/usr/local' -xvzf -
ENV PATH /usr/local/go/bin:$PATH
RUN go get github.com/mailhog/mhsendmail
RUN cp /root/go/bin/mhsendmail /usr/bin/mhsendmail
RUN echo 'sendmail_path = /usr/bin/mhsendmail --smtp-addr mailhog:1025' > /usr/local/etc/php/php.ini

COPY ./index.php /var/www/html/
COPY ./.htaccess /var/www/html/

# Update the default Apache virtual host configuration
# to allow .htaccess files to override settings
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Restart Apache to apply the changes
RUN service apache2 restart
