# ERP Finance Starter for Laravel

Starter modulaire pour un ERP de gestion financière inspiré de votre logique Excel/VBA : fournisseurs, dépenses, ventilations, paiements, budgets, trésorerie et reporting.

## Positionnement

Ce starter **n'est pas un projet Laravel complet avec vendor/**. Il est pensé pour être intégré dans une **application Laravel neuve** afin d'accélérer la mise en place du socle métier.

Il reprend les briques clés déjà stabilisées côté Excel/VBA :
- Référentiel fournisseurs
- Dépenses / factures fournisseurs
- Ventilation des dépenses
- Paiements partiels ou globaux
- Budget par catégorie / type de tiers
- Simulation de trésorerie
- Dashboard de pilotage
- Journalisation basique

## Ce qui est inclus

- Structure MVC Laravel claire
- Migrations SQL
- Modèles Eloquent
- Contrôleurs HTTP
- Form Requests de validation
- Services métier (budget / trésorerie)
- Layout Blade premium Tailwind-like
- Dashboard direction
- Écrans CRUD : Fournisseurs, Dépenses, Budgets
- Vue Trésorerie simplifiée
- Seeders de démarrage
- Routes web

## Pré-requis

- PHP 8.2+
- Composer
- Node.js 20+
- Base MySQL ou PostgreSQL
- Une application Laravel récente créée au préalable

## Installation recommandée

### 1. Créer une application Laravel neuve

```bash
laravel new erp-finance
```

Ou avec Composer :

```bash
composer create-project laravel/laravel erp-finance
```

### 2. Installer les dépendances front

```bash
cd erp-finance
npm install
```

### 3. Copier le contenu de ce starter dans le projet

Copier les dossiers suivants dans votre projet Laravel :
- `app/`
- `database/`
- `resources/`
- `routes/`
- `config/erp.php`

### 4. Compléter le layout principal

Ce starter suppose que votre projet dispose déjà de Vite + Tailwind. Si vous partez d'un Laravel neuf, laissez la configuration front par défaut et ajoutez simplement les vues fournies.

### 5. Configurer la base

Dans `.env` :

```env
APP_NAME="ERP Finance"
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_finance
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Migrer + seeder

```bash
php artisan migrate --seed
```

### 7. Lancer

```bash
php artisan serve
npm run dev
```

## Comptes de départ

Le seeder crée trois comptes :
- admin@erp.local / password
- finance@erp.local / password
- lecture@erp.local / password

## Rôles inclus

- `admin`
- `finance`
- `reader`

## Modules inclus

### 1. Dashboard
- KPI globaux
- Répartition par statut
- Budget vs réalisé
- Tensions de trésorerie

### 2. Fournisseurs
- Fiche fournisseur
- Catégorie
- Type de tiers
- Catégorie budgétaire
- Délai de paiement
- Taux TVA par défaut

### 3. Dépenses
- Dépense parent
- Statut opérationnel
- Statut validation
- Montant TTC, payé, solde
- Lien fournisseur
- Génération de ventilation

### 4. Ventilation
- Une dépense parent peut avoir plusieurs lignes ventilées
- Montant ventilé par période
- Paiement partiel possible
- Date prévue de paiement

### 5. Budgets
- Ligne budgétaire mensuelle
- Type tiers / catégorie budgétaire
- Version budget
- Réalisé calculé
- Écart

### 6. Trésorerie
- Calcul simplifié des sorties à venir
- Projection à date
- Mise en évidence des tensions

## Ce qu'il faut encore construire après le starter

- Gestion complète des pièces jointes
- Workflows de validation par acteur
- Notifications mail
- Exports PDF / Excel avancés
- Dashboards dynamiques plus riches
- Historisation fine
- Permissions détaillées
- API REST

## Conseils d'évolution

Ordre conseillé :
1. Finaliser fournisseurs
2. Stabiliser dépenses + ventilations
3. Consolider budget
4. Étendre trésorerie
5. Ajouter reporting avancé
6. Ajouter ventes / social

## Structure clé

- `app/Models` : entités métier
- `app/Services` : logique métier transverse
- `app/Support` : constantes / helpers
- `app/Http/Controllers` : contrôleurs UI
- `resources/views` : interface Blade
- `database/migrations` : schéma SQL
- `database/seeders` : données de démarrage

## Important

Ce starter est un **socle de projet**, pas une version production finalisée. Il est conçu pour accélérer la construction d'une V1 solide à partir de votre logique métier existante.
