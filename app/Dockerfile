FROM php:8.2-apache

ARG USER

RUN docker-php-ext-install mysqli

COPY www /var/www/html

# # -U: create user group with same name
# # -m: create user home
# # -s: set default user shell
RUN useradd -Ums /bin/bash ${USER}

USER ${USER}
