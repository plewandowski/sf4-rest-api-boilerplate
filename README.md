# Introduction

Rest api based on popular symfony bundles:
- FOSUserBundle (provides users repository and tools)
- FOSRestBundle (REST output and parameter listeners, rest routing, etc...)
- JMSSerializer (Serializes doctrine entities to json)
- LexikJwtAuthenticationBundle (Provides JWT Authenticator)
- NelmioCorsBundle (CORS)
- NelmioApiDocBundle (Docuemntation with swagger)


# Installation

Composer packages  
`composer install`

## Symfony config
### Development
Copy `.env.dist` to `.env` and update configuration variables

Update database credentials (do not use the same database for test env)
````
DATABASE_URL=mysql://root:test@127.0.0.1:3306/sf4_rest
DATABASE_TEST_URL=mysql://root:test@127.0.0.1:3306/sf4_rest_test
```` 

Update JWT configuration variables
````
JWT_PRIVATE_KEY_PATH=config/jwt/private.pem
JWT_PUBLIC_KEY_PATH=config/jwt/public.pem
JWT_PASSPHRASE=657f71ad85bd9638746f070edfde13ed
JWT_TOKEN_TTL=3600
```` 

### Production
For production use system environment variables instead of .env  

## JWT config
Create keys folder `mkdir -p var/config/jwt`

Create private key  
`openssl genrsa -out var/config/jwt/private.pem -aes256 4096`

Create public key  
`openssl rsa -pubout -in var/config/jwt/private.pem -out var/config/jwt/public.pem`

## Database schema setup

Create database schema
````
php bin/console doctrine:schema:create
````

## Test API users
There is not a default test user for security reasons. Test user(s) can be easily created with FOSUser bundle command:  
`php bin/console fos:user:create` and then add role `ROLE_REST_USER` to the user with `php bin/console fos:user:promote` command 


# Tests

## Behat
1. Copy `behat.yml.dist` to `behat.yml` 
2. Run behat: `vendor/bin/behat`

# API doc 
Api doc is not fully completed. However, it can be accessed under: `/api/doc`.
