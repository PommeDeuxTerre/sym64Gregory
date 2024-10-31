# Set-Up

## local
```bash
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony serve -d
```

## docker

modifier le .env

```php
# DB_HOST="localhost"
# DB_USER="root"
# DB_PWD=""
# remplacer les champs au dessus par
DB_HOST="db"
DB_USER="user"
DB_PWD="password"
```

```bash
docker-compose up -d --build
```
il est possible que vous ayez un message d'erreur de type
``
dans ce cas là attendez que le container db soit prêt à recevoir des requêtes
