<?php
/**
 * Plugin Name: Simulateur de Rentabilité Panneaux Solaires
 * Plugin URI: https://votre-site.com
 * Description: Plugin pour simuler la rentabilité et les économies avec l'installation de panneaux solaires
 * Version: 1.0.0
 * Author: Votre Nom
 * License: GPL v2 or later
 * Text Domain: solar-calc-rentabilite
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Définir les constantes du plugin
define('SOLAR_CALC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SOLAR_CALC_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SOLAR_CALC_VERSION', '1.0.0');

class SolarCalculatorRentabilite {
    
    public function __construct() {
        add_action('init', array($this, 'solar_calc_init'));
        add_action('wp_enqueue_scripts', array($this, 'solar_calc_enqueue_scripts'));
        add_shortcode('solar_calculator', array($this, 'solar_calc_shortcode'));
        add_action('wp_ajax_solar_calc_calculate', array($this, 'solar_calc_ajax_calculate'));
        add_action('wp_ajax_nopriv_solar_calc_calculate', array($this, 'solar_calc_ajax_calculate'));
    }
    
    public function solar_calc_init() {
        // Initialisation du plugin
    }
    
    public function solar_calc_enqueue_scripts() {
        wp_enqueue_style(
            'solar-calc-style',
            SOLAR_CALC_PLUGIN_URL . 'assets/solar-calculator-style.css',
            array(),
            SOLAR_CALC_VERSION
        );
        
        wp_enqueue_script(
            'solar-calc-script',
            SOLAR_CALC_PLUGIN_URL . 'assets/solar-calculator-script.js',
            array('jquery'),
            SOLAR_CALC_VERSION,
            true
        );
        
        // Localiser le script pour AJAX
        wp_localize_script('solar-calc-script', 'solar_calc_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('solar_calc_nonce')
        ));
    }
    
    public function solar_calc_shortcode($atts) {
        $atts = shortcode_atts(array(
            'style' => 'default'
        ), $atts);
        
        ob_start();
        $this->solar_calc_render_form();
        return ob_get_clean();
    }
    
    private function solar_calc_render_form() {
        ?>
        <div id="solar-calc-container" class="solar-calc-wrapper">
            <div class="solar-calc-header">
                <h2 class="solar-calc-title">Simulateur de Rentabilité Panneaux Solaires</h2>
                <p class="solar-calc-subtitle">Découvrez vos économies potentielles</p>
            </div>
            
            <form id="solar-calc-form" class="solar-calc-form">
                <?php wp_nonce_field('solar_calc_nonce', 'solar_calc_nonce_field'); ?>
                
                <div class="solar-calc-section">
                    <h3 class="solar-calc-section-title">Votre consommation électrique</h3>
                    
                    <div class="solar-calc-field-group">
                        <label for="solar-calc-consumption" class="solar-calc-label">
                            Consommation annuelle (kWh)
                        </label>
                        <div class="solar-calc-slider-container">
                            <input type="range" 
                                   id="solar-calc-consumption" 
                                   name="consumption" 
                                   min="1000" 
                                   max="20000" 
                                   value="5000" 
                                   class="solar-calc-slider">
                            <span class="solar-calc-value" id="solar-calc-consumption-value">5000 kWh</span>
                        </div>
                        <small class="solar-calc-help">Vous pouvez trouver cette info sur votre facture EDF</small>
                    </div>
                    
                    <div class="solar-calc-field-group">
                        <label for="solar-calc-bill" class="solar-calc-label">
                            Facture électrique mensuelle (€)
                        </label>
                        <div class="solar-calc-slider-container">
                            <input type="range" 
                                   id="solar-calc-bill" 
                                   name="bill" 
                                   min="30" 
                                   max="500" 
                                   value="120" 
                                   class="solar-calc-slider">
                            <span class="solar-calc-value" id="solar-calc-bill-value">120 €/mois</span>
                        </div>
                    </div>
                </div>
                
                <div class="solar-calc-section">
                    <h3 class="solar-calc-section-title">Votre installation</h3>
                    
                    <div class="solar-calc-field-group">
                        <label class="solar-calc-label">Connaissez-vous la surface de votre toit ?</label>
                        <div class="solar-calc-radio-group">
                            <label class="solar-calc-radio-label">
                                <input type="radio" name="roof_known" value="yes" checked class="solar-calc-radio">
                                Oui, je connais la surface
                            </label>
                            <label class="solar-calc-radio-label">
                                <input type="radio" name="roof_known" value="no" class="solar-calc-radio">
                                Non, j'ai besoin d'aide
                            </label>
                        </div>
                    </div>
                    
                    <div id="solar-calc-roof-direct" class="solar-calc-field-group">
                        <label for="solar-calc-roof-area" class="solar-calc-label">
                            Surface de toit disponible (m²)
                        </label>
                        <div class="solar-calc-slider-container">
                            <input type="range" 
                                   id="solar-calc-roof-area" 
                                   name="roof_area" 
                                   min="10" 
                                   max="200" 
                                   value="50" 
                                   class="solar-calc-slider">
                            <span class="solar-calc-value" id="solar-calc-roof-area-value">50 m²</span>
                        </div>
                    </div>
                    
                    <div id="solar-calc-roof-helper" class="solar-calc-field-group" style="display: none;">
                        <div class="solar-calc-helper-box">
                            <h4 class="solar-calc-helper-title">Aide au calcul de surface</h4>
                            
                            <div class="solar-calc-field-group">
                                <label for="solar-calc-ground-area" class="solar-calc-label">
                                    Surface au sol de votre maison (m²)
                                </label>
                                <input type="number" 
                                       id="solar-calc-ground-area" 
                                       name="ground_area" 
                                       min="50" 
                                       max="500" 
                                       value="100" 
                                       class="solar-calc-input">
                            </div>
                            
                            <div class="solar-calc-field-group">
                                <label for="solar-calc-roof-type" class="solar-calc-label">Type de toit</label>
                                <select id="solar-calc-roof-type" name="roof_type" class="solar-calc-select">
                                    <option value="flat">Toit plat (coefficient 1.0)</option>
                                    <option value="low" selected>Pente faible (coefficient 1.1)</option>
                                    <option value="medium">Pente moyenne (coefficient 1.2)</option>
                                    <option value="steep">Pente forte (coefficient 1.4)</option>
                                </select>
                            </div>
                            
                            <div class="solar-calc-field-group">
                                <label for="solar-calc-roof-percentage" class="solar-calc-label">
                                    Pourcentage utilisable (%)
                                </label>
                                <div class="solar-calc-slider-container">
                                    <input type="range" 
                                           id="solar-calc-roof-percentage" 
                                           name="roof_percentage" 
                                           min="30" 
                                           max="80" 
                                           value="60" 
                                           class="solar-calc-slider">
                                    <span class="solar-calc-value" id="solar-calc-roof-percentage-value">60%</span>
                                </div>
                                <small class="solar-calc-help">En tenant compte des obstacles (cheminées, velux, etc.)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="solar-calc-field-group">
                        <label for="solar-calc-orientation" class="solar-calc-label">Orientation du toit</label>
                        <select id="solar-calc-orientation" name="orientation" class="solar-calc-select">
                            <option value="south">Sud (optimal)</option>
                            <option value="southeast">Sud-Est (bon)</option>
                            <option value="southwest">Sud-Ouest (bon)</option>
                            <option value="east">Est (correct)</option>
                            <option value="west">Ouest (correct)</option>
                            <option value="north">Nord (non recommandé)</option>
                        </select>
                    </div>
                </div>
                
                <div class="solar-calc-section">
                    <h3 class="solar-calc-section-title">Localisation</h3>
                    
                    <div class="solar-calc-field-group">
                        <label for="solar-calc-region" class="solar-calc-label">Région</label>
                        <select id="solar-calc-region" name="region" class="solar-calc-select">
                            <option value="north">Nord</option>
                            <option value="center" selected>Centre</option>
                            <option value="south">Sud</option>
                            <option value="overseas">Outre-mer</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="solar-calc-button">
                    Calculer mes économies
                </button>
            </form>
            
            <div id="solar-calc-results" class="solar-calc-results" style="display: none;">
                <div class="solar-calc-loading" id="solar-calc-loading">
                    <div class="solar-calc-spinner"></div>
                    <p>Calcul en cours...</p>
                </div>
                
                <div id="solar-calc-results-content" class="solar-calc-results-content" style="display: none;">
                    <h3 class="solar-calc-results-title">Vos économies potentielles</h3>
                    
                    <div class="solar-calc-highlight-box">
                        <div class="solar-calc-big-number">
                            <span id="solar-calc-yearly-savings">0</span>€/an
                        </div>
                        <p class="solar-calc-savings-text">d'économies estimées</p>
                    </div>
                    
                    <div class="solar-calc-details">
                        <div class="solar-calc-detail-item">
                            <span class="solar-calc-detail-label">Production annuelle estimée :</span>
                            <span class="solar-calc-detail-value" id="solar-calc-production">0 kWh</span>
                        </div>
                        
                        <div class="solar-calc-detail-item">
                            <span class="solar-calc-detail-label">Puissance installée :</span>
                            <span class="solar-calc-detail-value" id="solar-calc-power">0 kWc</span>
                        </div>
                        
                        <div class="solar-calc-detail-item">
                            <span class="solar-calc-detail-label">Nombre de panneaux :</span>
                            <span class="solar-calc-detail-value" id="solar-calc-panels">0</span>
                        </div>
                        
                        <div class="solar-calc-detail-item">
                            <span class="solar-calc-detail-label">Investissement estimé :</span>
                            <span class="solar-calc-detail-value" id="solar-calc-investment">0€</span>
                        </div>
                        
                        <div class="solar-calc-detail-item">
                            <span class="solar-calc-detail-label">Retour sur investissement :</span>
                            <span class="solar-calc-detail-value" id="solar-calc-payback">0 ans</span>
                        </div>
                    </div>
                    
                    <div class="solar-calc-chart-container">
                        <canvas id="solar-calc-chart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function solar_calc_ajax_calculate() {
        // Vérifier le nonce de sécurité
        if (!wp_verify_nonce($_POST['nonce'], 'solar_calc_nonce')) {
            wp_die('Erreur de sécurité');
        }
        
        // Récupérer les données du formulaire
        $consumption = intval($_POST['consumption']);
        $bill = intval($_POST['bill']);
        $roof_area = floatval($_POST['roof_area']);
        $orientation = sanitize_text_field($_POST['orientation']);
        $region = sanitize_text_field($_POST['region']);
        
        // Effectuer les calculs
        $results = $this->solar_calc_perform_calculations($consumption, $bill, $roof_area, $orientation, $region);
        
        wp_send_json_success($results);
    }
    
    private function solar_calc_perform_calculations($consumption, $bill, $roof_area, $orientation, $region) {
        // Coefficients de calcul
        $orientation_coeffs = array(
            'south' => 1.0,
            'southeast' => 0.95,
            'southwest' => 0.95,
            'east' => 0.85,
            'west' => 0.85,
            'north' => 0.6
        );
        
        $region_coeffs = array(
            'north' => 900,
            'center' => 1100,
            'south' => 1300,
            'overseas' => 1500
        );
        
        // Calculs
        $orientation_coeff = $orientation_coeffs[$orientation] ?? 1.0;
        $irradiation = $region_coeffs[$region] ?? 1100;
        
        // Puissance installable (kWc) - environ 6m² par kWc
        $power_kwc = ($roof_area / 6);
        
        // Production annuelle (kWh)
        $annual_production = $power_kwc * $irradiation * $orientation_coeff;
        
        // Prix de l'électricité (€/kWh) - calculé à partir de la facture
        $electricity_price = ($bill * 12) / $consumption;
        
        // Économies annuelles
        $autoconsumption_rate = 0.7; // 70% d'autoconsommation moyenne
        $feed_in_tariff = 0.10; // Tarif de rachat EDF
        
        $self_consumed = min($annual_production * $autoconsumption_rate, $consumption);
        $excess_production = max(0, $annual_production - $self_consumed);
        
        $savings_self_consumption = $self_consumed * $electricity_price;
        $savings_feed_in = $excess_production * $feed_in_tariff;
        $yearly_savings = $savings_self_consumption + $savings_feed_in;
        
        // Investissement et retour
        $cost_per_kwc = 2500; // Coût moyen par kWc installé
        $total_investment = $power_kwc * $cost_per_kwc;
        $payback_years = $total_investment / $yearly_savings;
        
        // Nombre de panneaux (environ 400W par panneau)
        $panels_count = ceil($power_kwc / 0.4);
        
        return array(
            'yearly_savings' => round($yearly_savings),
            'annual_production' => round($annual_production),
            'power_kwc' => round($power_kwc, 1),
            'panels_count' => $panels_count,
            'total_investment' => round($total_investment),
            'payback_years' => round($payback_years, 1),
            'chart_data' => $this->solar_calc_generate_chart_data($yearly_savings, $total_investment, $payback_years)
        );
    }
    
    private function solar_calc_generate_chart_data($yearly_savings, $total_investment, $payback_years) {
        $years = array();
        $cumulative_savings = array();
        $investment_line = array();
        
        for ($i = 0; $i <= 20; $i++) {
            $years[] = $i;
            $cumulative_savings[] = $i * $yearly_savings;
            $investment_line[] = $total_investment;
        }
        
        return array(
            'years' => $years,
            'cumulative_savings' => $cumulative_savings,
            'investment_line' => $investment_line,
            'payback_point' => $payback_years
        );
    }
}

// Initialiser le plugin
new SolarCalculatorRentabilite();

// Hook d'activation
register_activation_hook(__FILE__, 'solar_calc_activate');
function solar_calc_activate() {
    // Actions à effectuer lors de l'activation
}

// Hook de désactivation
register_deactivation_hook(__FILE__, 'solar_calc_deactivate');
function solar_calc_deactivate() {
    // Actions à effectuer lors de la désactivation
} 