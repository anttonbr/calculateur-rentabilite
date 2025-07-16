<?php
/**
 * Exemples d'utilisation du plugin Simulateur de Rentabilité Panneaux Solaires
 * 
 * Ce fichier montre différentes façons d'intégrer le simulateur dans votre site WordPress.
 * Copiez les exemples qui vous intéressent dans vos templates ou contenus.
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

?>

<!-- EXEMPLE 1: Utilisation simple dans une page ou un article -->
<!-- Copiez ce code dans l'éditeur de contenu WordPress -->
<div class="container-simulateur">
    <h2>Découvrez vos économies potentielles</h2>
    <p>Utilisez notre simulateur pour estimer les économies que vous pourriez réaliser avec des panneaux solaires.</p>
    
    [solar_calculator]
    
    <p><em>Simulation basée sur les données du marché français actuel.</em></p>
</div>

<!-- EXEMPLE 2: Intégration dans un template PHP -->
<?php
/*
Ajoutez ce code dans vos fichiers de template (page.php, single.php, etc.)
*/

// Dans un template, vous pouvez utiliser :
echo '<div class="wp-block-group">';
echo '<h2>Simulateur de Rentabilité Solaire</h2>';
echo do_shortcode('[solar_calculator]');
echo '</div>';
?>

<!-- EXEMPLE 3: Page dédiée avec contenu marketing -->
<!-- Créez une nouvelle page et utilisez ce contenu -->
<div class="page-simulateur-solaire">
    <header class="hero-section">
        <h1>Calculez vos économies avec le solaire</h1>
        <p class="lead">Découvrez en quelques clics combien vous pourriez économiser avec une installation de panneaux solaires adaptée à votre situation.</p>
    </header>
    
    <section class="avantages">
        <h2>Pourquoi choisir le solaire ?</h2>
        <div class="row">
            <div class="col-md-4">
                <h3>🌱 Écologique</h3>
                <p>Réduisez votre empreinte carbone et participez à la transition énergétique.</p>
            </div>
            <div class="col-md-4">
                <h3>💰 Économique</h3>
                <p>Diminuez vos factures d'électricité et bénéficiez d'aides de l'État.</p>
            </div>
            <div class="col-md-4">
                <h3>🏠 Valorisation</h3>
                <p>Augmentez la valeur de votre bien immobilier.</p>
            </div>
        </div>
    </section>
    
    <section class="simulateur-section">
        <h2>Simulez vos économies</h2>
        <p>Remplissez les informations ci-dessous pour obtenir une estimation personnalisée :</p>
        
        [solar_calculator]
    </section>
    
    <section class="informations-complementaires">
        <h2>Informations importantes</h2>
        <div class="accordion">
            <h3>💡 Comment fonctionne le calcul ?</h3>
            <p>Notre simulateur utilise des données réelles du marché français, incluant l'irradiation solaire par région, les tarifs EDF actuels, et les coûts d'installation moyens.</p>
            
            <h3>📊 Que signifient les résultats ?</h3>
            <p>Les économies affichées représentent une estimation basée sur votre consommation et la production potentielle de votre installation. Les résultats peuvent varier selon les conditions réelles.</p>
            
            <h3>🔧 Prochaines étapes</h3>
            <p>Pour une étude personnalisée et un devis précis, contactez nos experts qui analyseront votre situation spécifique.</p>
        </div>
    </section>
</div>

<!-- EXEMPLE 4: Widget dans une sidebar -->
<?php
/*
Pour créer un widget personnalisé avec le simulateur,
ajoutez ce code dans functions.php de votre thème :
*/

function solar_calc_widget_shortcode() {
    return '
    <div class="widget widget-solar-calc">
        <h3 class="widget-title">Simulateur Solaire</h3>
        <p>Calculez vos économies potentielles :</p>
        ' . do_shortcode('[solar_calculator]') . '
    </div>';
}
add_shortcode('solar_widget', 'solar_calc_widget_shortcode');

// Puis utilisez [solar_widget] dans vos widgets texte
?>

<!-- EXEMPLE 5: Landing page complète -->
<!-- Structure HTML pour une landing page dédiée -->
<div class="landing-page-solaire">
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>Panneaux Solaires : Économisez jusqu'à 70% sur vos factures</h1>
                    <p class="lead">Découvrez combien vous pourriez économiser avec une installation solaire personnalisée.</p>
                    <ul class="checklist">
                        <li>✅ Simulation gratuite et instantanée</li>
                        <li>✅ Basée sur votre consommation réelle</li>
                        <li>✅ Données du marché français</li>
                        <li>✅ Aide au calcul de surface</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="simulateur-hero">
                        [solar_calculator]
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="resultats-types">
        <div class="container">
            <h2>Exemples de résultats</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="exemple-carte">
                        <h3>Maison 100m²</h3>
                        <p>Consommation : 4 500 kWh/an</p>
                        <p class="economie">Économies : <strong>800€/an</strong></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="exemple-carte">
                        <h3>Maison 150m²</h3>
                        <p>Consommation : 8 000 kWh/an</p>
                        <p class="economie">Économies : <strong>1 400€/an</strong></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="exemple-carte">
                        <h3>Grande maison</h3>
                        <p>Consommation : 12 000 kWh/an</p>
                        <p class="economie">Économies : <strong>2 100€/an</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
/*
EXEMPLE 6: CSS personnalisé pour intégrer le simulateur
Ajoutez ce CSS dans votre thème pour personnaliser l'apparence :
*/
?>

<style>
/* Personnalisation du simulateur pour votre thème */
.container-simulateur {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 40px;
    border-radius: 15px;
    margin: 30px auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* Adapter les couleurs à votre charte graphique */
.solar-calc-wrapper {
    --solar-calc-primary: #2c3e50; /* Remplacez par votre couleur */
    --solar-calc-accent: #e74c3c;  /* Remplacez par votre couleur d'accent */
}

/* Style pour une sidebar */
.widget-solar-calc .solar-calc-wrapper {
    padding: 15px;
    font-size: 0.9em;
}

.widget-solar-calc .solar-calc-section {
    margin-bottom: 20px;
    padding: 15px;
}

/* Responsive pour mobile */
@media (max-width: 768px) {
    .container-simulateur {
        padding: 20px;
        margin: 15px;
    }
}
</style>

<?php
/*
EXEMPLE 7: Hook WordPress pour afficher automatiquement le simulateur
Ajoutez dans functions.php pour afficher automatiquement sur certaines pages :
*/

function auto_display_solar_calculator($content) {
    // Afficher automatiquement sur les pages contenant "solaire" ou "panneaux"
    if (is_page() && (strpos(get_the_title(), 'solaire') !== false || 
                      strpos(get_the_title(), 'panneaux') !== false)) {
        $content .= '<div class="auto-solar-calc">';
        $content .= '<h3>Simulez vos économies :</h3>';
        $content .= do_shortcode('[solar_calculator]');
        $content .= '</div>';
    }
    return $content;
}
add_filter('the_content', 'auto_display_solar_calculator');
?>

<!-- EXEMPLE 8: Formulaire de contact intégré -->
<!-- Ajoutez ce code après le simulateur pour capter les leads -->
<div class="contact-post-simulation" style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
    <h3>Intéressé par une étude personnalisée ?</h3>
    <p>Nos experts peuvent vous proposer une étude détaillée et un devis sur mesure.</p>
    
    <!-- Utilisez Contact Form 7 ou votre plugin de formulaire préféré -->
    [contact-form-7 id="123" title="Contact Solaire"]
    
    <!-- Ou un formulaire HTML simple -->
    <form class="contact-solaire" method="post">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="nom" placeholder="Votre nom" required>
            </div>
            <div class="col-md-6">
                <input type="email" name="email" placeholder="Votre email" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <input type="tel" name="telephone" placeholder="Votre téléphone">
            </div>
            <div class="col-md-6">
                <input type="text" name="ville" placeholder="Votre ville">
            </div>
        </div>
        <textarea name="message" placeholder="Votre message (optionnel)" rows="3"></textarea>
        <button type="submit" class="btn btn-primary">Demander une étude gratuite</button>
    </form>
</div>

<?php
/*
NOTES D'UTILISATION :

1. Le shortcode [solar_calculator] peut être utilisé partout dans WordPress
2. Tous les styles utilisent le préfixe "solar-calc-" pour éviter les conflits
3. Le plugin est responsive et s'adapte automatiquement
4. Chart.js est chargé automatiquement depuis un CDN
5. Les calculs se font en AJAX sans rechargement de page
6. La sécurité est assurée par les nonces WordPress

CONSEILS D'INTÉGRATION :

- Testez sur différents thèmes pour vérifier la compatibilité
- Personnalisez les couleurs selon votre charte graphique
- Ajoutez du contenu marketing autour du simulateur
- Intégrez un formulaire de contact pour capter les leads
- Utilisez Google Analytics pour tracker les conversions
- Testez la performance sur mobile

*/
?> 