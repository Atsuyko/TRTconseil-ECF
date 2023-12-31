# TRTconseil-ECF

## Projet 

TRT Conseil est une agence de recrutement spécialisée dans l’hôtellerie et la restauration.
4 types d’utilisateur devront pouvoir se connecter :
- Les recruteurs : Une entreprise qui recherche un employé.
- Les candidats : Un serveur, responsable de la restauration, chef cuisinier etc.
- Les consultants : Missionnés par TRT Conseil pour gérer les liaisons sur le back-office entre recruteurs et candidats.
- L’administrateur : La personne en charge de la maintenance de l’application.

## Deploiement en local

Afin d'utiliser le site en local vous devez suivre les étapes suivantes :
  1. Cloner le repository présent sur GitHub : https://github.com/Atsuyko/TRTconseil-ECF.git
  2. Ouvrir le dossier dans un IDE, ouvrir le terminal de commande, se placer dans le dossier du projet et taper "composer install"
  3. Modifier les paramètres de votre base de donnée le dossier ".env"
  4. Dans le terminal tapez :
    - php bin/console doctrine:database:create
    - php bin/console make:migration
    - php bin/console doctrine:migration:migrate
  5. Toujours le terminal tapez "symfony serve"

Votre application est déployé en local, à ouvrir en cliquant sur le lien présent dans le terminal.

## Créer un admin

  1. Sur le site créer un compte en cliquant sur "Inscription"
  2. Ouvrir votre base de donnée et dans la colonne roles modifier ["ROLE_USER"] en ["ROLE_ADMIN"]
  3. Dans la colonne role remplacer le contenu par "admin"
  4. Dans la colonne IsValidate changer "false" à "true"

Votre administrateur est créer, vous pouvez vous connecter et accéder au panel administrateur.