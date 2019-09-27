# Vote4Lunch

## Expression des besoins 

L’application initital a été développé en react côté front et en node.js côté back. 
L’objectif est de faire la refonte du site côté back afin de migrer sur le framework symfony propulsé via api platform dans un premier temps sans ajout de nouvelles fonctionnalités. 

Ceci est la parti back de l'appli.

## Installation du projet

Prérequis :
  - Docker & docker-compose
  - openSSL
  
Etapes Mise en place de l'Api :
  - Récuperer le projet
  - docker-compose build
  - docker-compose up -d ( ne pas avoir apache de démarer et les ports dans le docker-compose.yml d'utilisé)
  - docker exec -it sf4_php bash
  - cd sf4/server
  - composer install
  - copier le .env.dist
  - coller en .env et modifier le DATABASE_URL par : mysql://sf4:sf4@mysql:3306/sf4
  - Modifier le JWT_PASSPHRASE par une clé de pass de votre choix
  - ./bin/console doctrine:migration:migrate
  - ./bin/console doctrine:fixture:load
  - ./bin/console app:import-data ( 10-15 seconde d'attente )
  - mkdir -p config/jwt
  - openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
  - rentrer 2 fois votre clé de pass configurer dans le .env
  - openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
  - rentrer votre clé de pass encore une fois

## MAJ A VENIR 

- 28/09 : Il manque un fichier de migration, vous pouvez faire un make:migration avant le doctrine:migration:migrate

## Développer par Elnéris Dang pour Omnilog SAS

