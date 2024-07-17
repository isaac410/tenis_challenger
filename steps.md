composer create-project symfony/skeleton:"7.1.*" tenis_tournament
composer require symfony/form
composer require symfony/orm-pack
composer require symfony/validator
composer require symfony/serializer
composer require symfony/security-csrf
composer require symfony/serializer-pack
composer require symfony/maker-bundle --dev
composer require nelmio/api-doc-bundle
agregar "Nelmio\ApiDocBundle\NelmioApiDocBundle::class => ['all' => true]," in config/bundles.php
composer require symfony/twig-bundle (Must to nelmio works)
composer require symfony/asset (Must to nelmio works)
(config nelmio in: config/packages/nelmio_api_doc.yaml & config/routes.yaml)

php bin/console make (see all available commands)

how create crud (example):
first step, create an entity, if it must have some enum property create it before
php bin/console make:entity Tournament
php bin/console make:crud --> that create controller, formType and templates twigs, you can delete those last
php bin/console make:controller PlayerController

php bin/console list doctrine (list doctrine commands)

Get in DB by docker-compose:
docker-compose -f dev.docker-compose.yml exec mysql mysql -u root -p

Get dump schema:
docker-compose -f dev.docker-compose.yml exec app php bin/console doctrine:migrations:dump-schema

create first migration:
docker-compose -f dev.docker-compose.yml exec app php bin/console doctrine:migrations:diff

Migrate:
docker-compose -f dev.docker-compose.yml exec app php bin/console doctrine:migrations:migrate

create src/EventListener/ExceptionListener.php | to handle throw Errors like below:
throw new HttpException(Response::HTTP_BAD_REQUEST, json_encode(['errors' => $errorsArray]));
config this above in config/services.yaml like below next

services:
    App\EventListener\ExceptionListener:
        tags:
            - { name: 'kernel.event_listener', event: 'kernel.exception' }