# Set-Up

## local
```bash
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
symfony serve -d
```

## docker

le compose.yaml recré automatiquement les fixtures au lancement si vous souhaiter retirer ce comportement il vous suffit de retirer la ligne suivant dans le compose.yaml (recommandé après le premier lancement pour éviter de tout supprimer):
`php bin/console doctrine:fixtures:load --no-interaction &&`

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

il est probable que vous ayez un message d'erreur de type

`dependency failed to start: container sym64gregory-db-1 is unhealthy`

dans ce cas là il suffit d'attendre quelques minutes et de lancer

```bash
docker-compose up -d php phpmyadmin
```

phpmyadmin fonctionneras directement par après en revanche php prendras un peu de temps pour charger les fixtures
