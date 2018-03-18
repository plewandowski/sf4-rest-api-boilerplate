#!/bin/bash

composer install

mkdir -p var/config/jwt
openssl genrsa -out var/config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in var/config/jwt/private.pem -out var/config/jwt/public.pem

php console/bin doctrine:migration:migrate --no-interaction
