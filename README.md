# Simulateur de Rentabilité Panneaux Solaires - Plugin WordPress

Un plugin WordPress complet pour simuler la rentabilité et les économies avec l'installation de panneaux solaires.

## 🌟 Fonctionnalités

- **Formulaire interactif** avec sliders et champs dynamiques
- **Aide au calcul de surface** de toit pour les utilisateurs qui ne connaissent pas leur surface
- **Calculs en temps réel** basés sur des données réalistes du marché français
- **Graphique interactif** montrant l'évolution des économies dans le temps
- **Interface responsive** s'adaptant à tous les écrans
- **Design moderne** avec les couleurs personnalisées (#303030 et #D45F2C)
- **Classes CSS spécifiques** pour éviter les conflits avec les thèmes

## 📋 Paramètres de simulation

### Consommation électrique
- Consommation annuelle (kWh)
- Facture électrique mensuelle (€)

### Installation
- Surface de toit disponible (avec aide au calcul)
- Orientation du toit (Sud, Sud-Est, Sud-Ouest, etc.)
- Type de toit et pourcentage utilisable

### Localisation
- Région (Nord, Centre, Sud, Outre-mer)

## 🎯 Résultats affichés

- **Économies annuelles** estimées
- Production électrique annuelle
- Puissance installée (kWc)
- Nombre de panneaux nécessaires
- Investissement estimé
- Retour sur investissement
- **Graphique interactif** des économies cumulées

## 🚀 Installation

### 1. Téléchargement
Téléchargez tous les fichiers du plugin dans un dossier `solar-calculator-rentabilite/`

### 2. Installation via FTP
1. Uploadez le dossier `solar-calculator-rentabilite/` dans `/wp-content/plugins/`
2. Connectez-vous à votre administration WordPress
3. Allez dans `Extensions > Extensions installées`
4. Activez le plugin "Simulateur de Rentabilité Panneaux Solaires"

### 3. Installation via l'administration WordPress
1. Compressez le dossier en fichier ZIP
2. Allez dans `Extensions > Ajouter une extension`
3. Cliquez sur "Téléverser une extension"
4. Sélectionnez le fichier ZIP et installez
5. Activez le plugin

## 📝 Utilisation

### Shortcode principal
```php
[solar_calculator]
```

### Exemples d'utilisation

#### Dans un article ou une page
```
Découvrez vos économies potentielles avec notre simulateur :

[solar_calculator]
```

#### Dans un template PHP
```php
<?php echo do_shortcode('[solar_calculator]'); ?>
```

#### Avec des paramètres (optionnel)
```php
[solar_calculator style="default"]
```

## 🎨 Personnalisation

### Couleurs principales
- **Couleur principale** : `#303030`
- **Couleur d'accent** : `#D45F2C`

### Classes CSS utilisées
Toutes les classes CSS commencent par `solar-calc-` pour éviter les conflits :

- `.solar-calc-wrapper` : Container principal
- `.solar-calc-section` : Sections du formulaire
- `.solar-calc-slider` : Sliders interactifs
- `.solar-calc-button` : Boutons
- `.solar-calc-results` : Zone de résultats
- `.solar-calc-highlight-box` : Mise en avant des économies

### Personnaliser les styles
Pour personnaliser l'apparence, ajoutez du CSS dans votre thème :

```css
/* Modifier la couleur principale */
.solar-calc-wrapper {
    --solar-calc-primary: #votre-couleur;
    --solar-calc-accent: #votre-couleur-accent;
}

/* Modifier la largeur maximale */
.solar-calc-wrapper {
    max-width: 1000px;
}
```

## ⚙️ Configuration technique

### Calculs utilisés
- **Irradiation solaire** : Basée sur les régions françaises (900-1500 kWh/m²/an)
- **Coefficients d'orientation** : Sud (100%), Sud-Est/Ouest (95%), Est/Ouest (85%)
- **Puissance installable** : ~6m² par kWc
- **Coût d'installation** : 2 500€ par kWc installé
- **Taux d'autoconsommation** : 70% (configurable)
- **Tarif de rachat EDF** : 0,10€/kWh

### Aide au calcul de surface
Le plugin propose une aide pour calculer la surface de toit :
- Surface au sol × Coefficient de pente × Pourcentage utilisable
- Coefficients : Plat (1.0), Pente faible (1.1), Moyenne (1.2), Forte (1.4)

## 🔧 Fichiers du plugin

```
solar-calculator-rentabilite/
├── solar-calculator-rentabilite.php  # Fichier principal
├── assets/
│   ├── solar-calculator-style.css    # Styles CSS
│   └── solar-calculator-script.js    # JavaScript
└── README.md                         # Documentation
```

## 🌐 Compatibilité

- **WordPress** : 5.0 ou supérieur
- **PHP** : 7.4 ou supérieur
- **jQuery** : Inclus avec WordPress
- **Chart.js** : Chargé automatiquement depuis CDN

## 📱 Responsive Design

Le plugin s'adapte automatiquement à tous les écrans :
- **Desktop** : Interface complète avec graphiques
- **Tablette** : Adaptation des colonnes
- **Mobile** : Interface optimisée avec sliders verticaux

## 🔒 Sécurité

- **Nonces WordPress** : Protection CSRF
- **Sanitisation** : Toutes les entrées utilisateur sont nettoyées
- **Validation** : Vérification des données côté serveur
- **Pas d'accès direct** : Protection contre l'accès direct aux fichiers

## 🐛 Débogage

Pour activer le mode débogage, utilisez la console navigateur :
```javascript
// Voir le graphique actuel
solarCalcDebug.chart()

// Forcer un nouveau calcul
solarCalcDebug.calculate()

// Voir les données du formulaire
solarCalcDebug.formData()
```

## 📈 Performance

- **Calculs AJAX** : Pas de rechargement de page
- **Debouncing** : Limite les requêtes lors de la saisie
- **Chart.js lazy loading** : Chargement à la demande
- **CSS optimisé** : Utilisation de CSS Grid et Flexbox

## 🤝 Support

Pour toute question ou problème :
1. Vérifiez que WordPress et PHP sont à jour
2. Testez avec un thème par défaut (Twenty Twenty-Four)
3. Désactivez les autres plugins temporairement
4. Consultez la console navigateur pour les erreurs JavaScript

## 📄 Licence

Ce plugin est distribué sous licence GPL v2 ou ultérieure.

## 🔄 Changelog

### Version 1.0.0
- Première version du plugin
- Formulaire complet avec aide au calcul de surface
- Graphiques interactifs avec Chart.js
- Interface responsive
- Calculs basés sur le marché français
- Classes CSS spécifiques pour éviter les conflits 