# 🚀 Guide d'Installation Rapide

## Installation en 3 étapes

### 1️⃣ Télécharger les fichiers
Créez un dossier `solar-calculator-rentabilite` avec cette structure :
```
solar-calculator-rentabilite/
├── solar-calculator-rentabilite.php
├── assets/
│   ├── solar-calculator-style.css
│   └── solar-calculator-script.js
└── README.md
```

### 2️⃣ Installer le plugin
**Option A - Via FTP :**
1. Uploadez le dossier dans `/wp-content/plugins/`
2. Allez dans WordPress Admin → Extensions
3. Activez "Simulateur de Rentabilité Panneaux Solaires"

**Option B - Via l'admin WordPress :**
1. Créez un fichier ZIP du dossier complet
2. WordPress Admin → Extensions → Ajouter → Téléverser
3. Sélectionnez le ZIP et installez

### 3️⃣ Utiliser le simulateur
Ajoutez ce shortcode dans vos pages/articles :
```
[solar_calculator]
```

## ✅ Test rapide

1. Créez une nouvelle page
2. Ajoutez le shortcode `[solar_calculator]`
3. Prévisualisez la page
4. Testez le formulaire avec ces valeurs :
   - Consommation : 5000 kWh
   - Facture : 120€/mois
   - Surface toit : 50m²
   - Orientation : Sud
   - Région : Centre

## 🔧 Résolution de problèmes

**Le simulateur n'apparaît pas :**
- Vérifiez que le plugin est activé
- Consultez les erreurs dans Outils → Santé du site

**Les styles ne s'appliquent pas :**
- Videz le cache si vous utilisez un plugin de cache
- Vérifiez dans la console navigateur (F12)

**Les calculs ne fonctionnent pas :**
- Vérifiez que jQuery est chargé par votre thème
- Testez avec un thème par défaut (Twenty Twenty-Four)

## 📞 Support
En cas de problème, vérifiez dans l'ordre :
1. Version WordPress (minimum 5.0)
2. Version PHP (minimum 7.4)
3. Thème compatible
4. Pas de conflit avec d'autres plugins

## 🎨 Personnalisation rapide
Pour changer les couleurs, ajoutez ce CSS dans votre thème :
```css
.solar-calc-wrapper {
    --solar-calc-primary: #VOTRE_COULEUR;
    --solar-calc-accent: #VOTRE_COULEUR_ACCENT;
}
```

---
✨ **C'est tout !** Votre simulateur est prêt à générer des leads qualifiés. 