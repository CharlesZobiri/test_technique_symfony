# README - Test Technique Symfony

## Description du projet

Ce projet est une application Symfony permettant de gérer un CRUD (Create, Read, Update, Delete) pour des produits et des catégories. Les utilisateurs peuvent ajouter, modifier, supprimer et consulter des produits et des catégories via une API RESTful.

Les données sont stockées dans une base de données MySQL, et une validation des entrées utilisateur est effectuée avant toute interaction avec la base de données.

## Choix techniques

### Symfony Framework
L'application utilise **Symfony** comme framework PHP, car il offre une architecture robuste et une flexibilité pour gérer les API REST. Symfony est une solution éprouvée pour le développement d'applications web modernes.

### Doctrine ORM
Pour gérer les données, l'application utilise **Doctrine ORM**, un puissant outil pour interagir avec les bases de données relationnelles en utilisant des objets PHP. Cela permet de simplifier la gestion des entités et de garantir la cohérence des données.

### Validation des données
La validation des données utilisateur est essentielle pour garantir l'intégrité des informations dans la base de données. Voici les principales validations mises en place :
- **Produit** : Validation des champs `nom`, `description`, `prix`, et `categorie` pour vérifier leur présence et leur format.
- **Catégorie** : Validation du champ `nom` pour s'assurer qu'il n'est pas vide et qu'il respecte une longueur maximale.
- Utilisation de **Symfony Validator** avec des annotations `@Assert` pour garantir que les données sont valides avant d'être enregistrées dans la base de données.

### API REST
L'application fonctionne en tant qu'API REST, avec des points de terminaison pour chaque fonctionnalité CRUD. Les données sont échangées au format JSON.

### Base de données
La base de données utilise **MySQL**, configurée via Doctrine, et les informations de connexion sont définies dans le fichier `.env`.

## Instructions d'installation

### Prérequis

- PHP 8.x ou supérieur
- Composer
- Base de données MySQL


### Étapes d'installation

1. **Cloner le projet**

   Pour récupérer le projet sur votre machine locale, exécutez la commande suivante :
   ```bash
   git clone https://github.com/CharlesZobiri/test_technique_symfony.git
   cd test_technique_symfony
   ```

2. **Installer les dépendances avec `composer`**

   Exécutez cette commande pour installer les dépendances du projet :
   ```bash
   composer install 
   ```

3.  **Configurer la base de données**

   Modifiez le fichier .env.local.example pour configurer la connexion à la base de        données.

   Remplacez la variable `DATABASE_URL` dans .env.local par vos informations de connexion MySQL :
   ```dotenv
   DATABASE_URL="mysql://username:password@127.0.0.1:3306/nom_de_votre_base"
   ```

4. **Appliquer les migrations pour configurer la base de données**

   Exécutez la commande suivante pour créer la base de données et appliquer les migrations :
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

5. **Démarrer le serveur Symfony**

   Lancez le serveur Symfony avec la commande :
   ```bash
   symfony server:start
   ```

6. **Accéder à l'application**

   L'application sera accessible à l'adresse suivante dans votre navigateur :
   ```bash 
   http://localhost:8000
   ```

   Vous pouvez maintenant tester l'API via Postman ou tout autre outil d'API.
### Points d'API disponibles

#### Produits
- **Récupérer tous les produits** :  
  `GET /api/produit`
  
- **Créer un nouveau produit** :  
  `POST /api/produit/create`

- **Mettre à jour un produit existant** :  
  `PUT /api/produit/update/{id}`

   Remplacer '{id}' par l'id du produit souhaité, par exemple `/api/produit/update/2`

- **Supprimer un produit** :  
  `DELETE /api/produit/delete/{id}`

#### Catégories
- **Récupérer toutes les catégories** :  
  `GET /api/categorie`

- **Créer une nouvelle catégorie** :  
  `POST /api/categorie/create`

- **Mettre à jour une catégorie existante** :  
  `PUT /api/categorie/update/{id}`

  Remplacer '{id}' par l'id de la catégorie souhaitée, par exemple `/api/categorie/update/2`

- **Supprimer une catégorie** :  
  `DELETE /api/categorie/delete/{id}`

## Test de l'application

Une fois que l'application est en cours d'exécution, vous pouvez tester les différentes fonctionnalités avec Postman. Voici quelques exemples de tests, pensez à créer d'abord des catégories et seulement ensuite des produits :

### Exemple de création d'une catégorie

Pour ce faire, allez dans l'application `Postman`, ouvrez un nouvel onglet, allez dans `Body` et sélectionnez `raw` en type `JSON`. 
À présent pour créer une catégorie veuillez inscire dans le champs d'url `http://localhost:8000{le_Endpoint_ciblé_pour_le_test}`.

Choisissez l'option `POST`, vous n'avez plus qu'à écrire votre requête comme sur les exemples fournis et à appuyez  sur `SEND`.

#### Endpoint : 
**`POST  /api/categorie/create`**

Voici un exemple de l'url que vous devriez avoir pour créer une catégorie : **POST** `http://localhost:8000/api/categorie/create`.

#### Exemple de requête JSON valide : 
```json
{
  "nom": "Informatique"
}
```

#### Réponse attendue (succès) :
Code HTTP : `201 Created`
```json 
{
  "message": "Catégorie créée avec succès",
  "catégorie": {
    "id": 1,
    "nom": "Informatique"
  }
}
```

#### Exemple de requête JSON non valide :
```json
{
  "nom": ""
}
```

#### Réponse attendue (erreur) :
Code HTTP : `400 Bad Request`
```json 
{
  "error": "Le nom de la catégorie ne peut pas être vide."
}
```


### Exemple d'édition d'une catégorie

Cette fois-ci sélectionnez l'option `PUT`, pensez à mettre l'`id` de la catégorie souhaitée et appuyez sur `SEND` quand votre requête est prête.

#### Endpoint : 
**`PUT  /api/categorie/update/{id}`**

#### Exemple de requête JSON valide : 
```json
{
  "nom": "Foyer"
}
```

#### Réponse attendue (succès) :
Code HTTP : `200 OK`
```json 
{
  "message": "Catégorie mise à jour avec succès",
  "catégorie": {
    "id": 1,
    "nom": "Foyer"
  }
}
```


### Exemple de création d'un produits

Choisissez l'option `POST`et vous n'avez plus qu'à écrire votre requête comme sur les exemples fournis et à appuyez sur `SEND`.
Faites attention à bien renseigner une catégorie que vous avez créée en amont.

#### Endpoint : 
**`POST  /api/produit/create`**

#### Exemple de requête JSON valide : 
```json
{
  "nom": "Ordinateur Portable HP",
  "description": "Un ordinateur portable performant avec processeur Intel i7 et 16 Go de RAM.",
  "prix": 799.99,
  "categorie": "Informatique"
}
```

#### Réponse attendue (succès) :
Code HTTP : `201 Created`
```json 
{
  "message": "Produit créé avec succès",
  "produit": {
    "id": 1,
    "nom": "Ordinateur Portable HP",
    "description": "Un ordinateur portable performant avec processeur Intel i7 et 16 Go de RAM.",
    "prix": 799.99,
    "dateCreation": "2024-12-04 12:00:00",
    "categorie": "Informatique"
  }
}
```

#### Exemple de requête JSON non valide :
```json
{
  "nom": "Ordinateur Portable HP",
  "description": "Un ordinateur portable performant avec processeur Intel i7 et 16 Go de RAM.",
  "prix": -799.99,
  "categorie": "Informatique"
}
```

#### Réponse attendue (erreur) :
Code HTTP : `400 Bad Request`
```json 
{
    "errors": [
        "Le prix doit être supérieur à 0."
    ]
}
```
