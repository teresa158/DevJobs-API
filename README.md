# DevJobs API

DevJobs API est une plateforme backend RESTful conçue pour la mise en relation entre des **Candidats** et des **Entreprises**. L'API permet aux entreprises de publier des offres d'emploi, et aux candidats d'y postuler, tout en sécurisant l'accès grâce à un système d'authentification et de gestion des rôles.

## 🚀 Technologies utilisées
- **Framework** : Laravel 11 (PHP)
- **Base de données** : MySQL
- **Authentification** : Laravel Sanctum (Tokens)
- **Architecture** : MVC étendu (Controllers, Services, FormRequests, API Resources, Enums)

## 📦 Fonctionnalités principales
- **Authentification sécurisée** : Inscription et connexion par token.
- **Gestion des rôles** : Deux rôles distincts (`candidate` et `company`) gérés via des Middlewares et des Requests.
- **Offres d'emploi** : Création, lecture, modification et suppression (Soft Deletes) réservées aux entreprises.
- **Candidatures** : 
  - Les candidats peuvent postuler aux offres ouvertes.
  - Les entreprises peuvent visualiser les candidatures reçues et modifier leur statut (Accepté/Refusé).
- **Relations avancées** : Gestion des compétences (Many-to-Many), profils utilisateurs (One-to-One), etc.

## 🛣️ Liste des Endpoints (API Routes)

Toutes les routes sont préfixées par `/api`.

### 🔐 Authentification (Routes Publiques)
| Méthode | Endpoint | Description | Body (JSON) |
| --- | --- | --- | --- |
| `POST` | `/register` | Inscription d'un nouvel utilisateur | `name`, `email`, `password`, `password_confirmation`, `role` (candidate/company) |
| `POST` | `/login` | Connexion et récupération du Token | `email`, `password` |

### 🏢 Offres d'Emploi (Routes Protégées - Token Requis)
| Méthode | Endpoint | Description | Autorisation | Body (JSON) |
| --- | --- | --- | --- | --- |
| `GET` | `/job-offers` | Liste toutes les offres disponibles | Tous (Candidats et Entreprises) | *Aucun* |
| `GET` | `/job-offers/{id}` | Détails d'une offre spécifique | Tous | *Aucun* |
| `POST` | `/job-offers` | Créer une nouvelle offre d'emploi | **Entreprise uniquement** | `title`, `description`, `contract_type`, `status`, `skills` (array) |
| `PUT` | `/job-offers/{id}` | Modifier une offre existante | **Propriétaire de l'offre** | `title`, `description`, `contract_type`, `status`, `skills` |
| `DELETE` | `/job-offers/{id}` | Supprimer une offre (Soft delete) | **Propriétaire de l'offre** | *Aucun* |

### 📝 Candidatures (Routes Protégées - Token Requis)
| Méthode | Endpoint | Description | Autorisation | Body (JSON) |
| --- | --- | --- | --- | --- |
| `GET` | `/applications` | Historique candidat **OU** Candidatures reçues | Tous | *Aucun* |
| `POST` | `/job-offers/{id}/apply`| Postuler à une offre | **Candidat uniquement** | `cover_letter` (optionnel) |
| `PUT` | `/applications/{id}/status`| Accepter ou refuser une candidature | **Propriétaire de l'offre** | `status` (accepted, rejected, pending) |

### 🚪 Déconnexion
| Méthode | Endpoint | Description | Autorisation |
| --- | --- | --- | --- |
| `POST` | `/logout` | Déconnexion (Révocation du Token) | Tous | *Aucun* |

## 🛠️ Installation et Lancement

1. Cloner le projet.
2. Installer les dépendances : 
   ```bash
   composer install
   ```
3. Configurer la base de données : copier `.env.example` vers `.env` et ajouter les identifiants MySQL.
4. Générer la clé d'application : 
   ```bash
   php artisan key:generate
   ```
5. Lancer les migrations et les seeders : 
   ```bash
   php artisan migrate --seed
   ```
6. Démarrer le serveur local : 
   ```bash
   php artisan serve
   ```
