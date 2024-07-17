# Tenis Tournament

## Creation of the project

### Installed dependencies

```bash
composer create-project symfony/skeleton:"7.1.*" tenis_tournament
composer require symfony/form
composer require symfony/orm-pack
composer require symfony/validator
composer require symfony/serializer
composer require symfony/security-csrf
composer require symfony/serializer-pack
composer require symfony/maker-bundle --dev
composer require nelmio/api-doc-bundle
composer require symfony/twig-bundle  # Necessary for Nelmio
composer require symfony/asset  # Necessary for Nelmio
```

## Command for doctrine assistance
```bash
php bin/console list doctrine #(list doctrine commands)
```

## Using the dev.docker-compose.yml file to build and deploy the project

### Build image
```bash
docker-compose -f dev.docker-compose.yml build
```

### Get Up Image
```bash
docker-compose -f dev.docker-compose.yml up
```

### Get in DB
```bash
docker-compose -f dev.docker-compose.yml exec mysql mysql -u root -p
```

### Create first migration
```bash
docker-compose -f dev.docker-compose.yml exec app php bin/console doctrine:migrations:diff
```

### Aplly Migrations
```bash
docker-compose -f dev.docker-compose.yml exec app php bin/console doctrine:migrations:migrate
```

### Get dump schema
```bash
docker-compose -f dev.docker-compose.yml exec app php bin/console doctrine:migrations:dump-schema
```

## An evenlistener was created
### create src/EventListener/ExceptionListener.php | to handle throw Errors like below:
```php
throw new HttpException(Response::HTTP_BAD_REQUEST, json_encode(['errors' => $errorsArray]));
```
### Config this above in config/services.yaml like below next
```yml
services:
    App\EventListener\ExceptionListener:
        tags:
            - { name: 'kernel.event_listener', event: 'kernel.exception' }
```