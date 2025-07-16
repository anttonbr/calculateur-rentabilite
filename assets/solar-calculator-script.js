/**
 * JavaScript pour le Simulateur de Rentabilité Panneaux Solaires
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Variables globales
    let chart = null;
    let isCalculating = false;
    
    // Initialisation
    init();
    
    function init() {
        // Charger Chart.js si pas déjà présent
        if (typeof Chart === 'undefined') {
            loadChartJS();
        }
        
        // Initialiser les sliders
        initSliders();
        
        // Initialiser les radio buttons pour l'aide au toit
        initRoofHelper();
        
        // Initialiser le formulaire
        initForm();
        
        // Initialiser le système de steps
        initSteps();
    }
    
    function loadChartJS() {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js';
        script.onload = function() {
            console.log('Chart.js chargé avec succès');
        };
        document.head.appendChild(script);
    }
    
    function initSliders() {
        // Sliders avec mise à jour en temps réel (sans calcul automatique)
        $('.solar-calc-slider').on('input', function() {
            updateSliderValue(this);
        });
        
        // Initialiser les valeurs affichées
        $('.solar-calc-slider').each(function() {
            updateSliderValue(this);
        });
    }
    
    function updateSliderValue(slider) {
        const $slider = $(slider);
        const value = parseInt($slider.val());
        const $valueDisplay = $('#' + $slider.attr('id') + '-value');
        
        let displayText = '';
        
        switch($slider.attr('id')) {
            case 'solar-calc-consumption':
                displayText = value.toLocaleString() + ' kWh';
                break;
            case 'solar-calc-bill':
                displayText = value + ' €/mois';
                break;
            case 'solar-calc-roof-area':
                displayText = value + ' m²';
                break;
            default:
                displayText = value;
        }
        
        $valueDisplay.text(displayText);
        
        // Mettre à jour la surface de toit calculée si on est en mode aide
        if ($slider.attr('id') === 'solar-calc-ground-area') {
            updateCalculatedRoofArea();
        }
    }
    
    function initRoofHelper() {
        $('input[name="roof_known"]').on('change', function() {
            const isKnown = $(this).val() === 'yes';
            
            if (isKnown) {
                $('#solar-calc-roof-direct').show();
                $('#solar-calc-roof-helper').hide();
            } else {
                $('#solar-calc-roof-direct').hide();
                $('#solar-calc-roof-helper').show();
                updateCalculatedRoofArea();
            }
        });
        
        // Événements pour les champs de l'aide au toit
        $('#solar-calc-ground-area, #solar-calc-roof-type').on('input change', function() {
            updateCalculatedRoofArea();
        });
    }
    
    function updateCalculatedRoofArea() {
        const groundArea = parseFloat($('#solar-calc-ground-area').val()) || 100;
        const roofType = $('#solar-calc-roof-type').val();
        const percentage = 60; // Pourcentage fixe utilisable
        
        const roofCoefficients = {
            'flat': 1.0,
            'low': 1.1,
            'medium': 1.2,
            'steep': 1.4
        };
        
        const coefficient = roofCoefficients[roofType] || 1.1;
        const calculatedArea = Math.round(groundArea * coefficient * (percentage / 100));
        
        // Mettre à jour la valeur du slider principal
        $('#solar-calc-roof-area').val(calculatedArea);
        updateSliderValue(document.getElementById('solar-calc-roof-area'));
    }
    
    function initSteps() {
        // Gérer les clics sur les titres de steps
        $('.solar-calc-step-title').on('click', function() {
            const $step = $(this).closest('.solar-calc-step');
            const $content = $step.find('.solar-calc-step-content');
            const $toggle = $step.find('.solar-calc-step-toggle');
            
            if ($content.is(':visible')) {
                $content.slideUp(300);
                $step.removeClass('active');
            } else {
                $content.slideDown(300);
                $step.addClass('active');
                
                // Auto-ouvrir le step suivant après completion
                autoOpenNextStep($step);
            }
        });
        
        // Ouvrir le premier step par défaut
        $('.solar-calc-step-1').addClass('active');
        $('.solar-calc-step-1 .solar-calc-step-content').show();
    }
    
    function autoOpenNextStep($currentStep) {
        // Attendre un peu puis ouvrir le step suivant
        setTimeout(function() {
            const currentStepNum = parseInt($currentStep.data('step'));
            const $nextStep = $(`.solar-calc-step-${currentStepNum + 1}`);
            
            if ($nextStep.length && !$nextStep.hasClass('active')) {
                $nextStep.find('.solar-calc-step-title').click();
            }
        }, 500);
    }
    
    function initForm() {
        $('#solar-calc-form').on('submit', function(e) {
            e.preventDefault();
            
            if (!isCalculating) {
                performCalculation();
            }
        });
        
        // Pas de calcul automatique - seulement sur submit
    }
    
    // Pas de calcul automatique - calcul seulement sur submit
    
    function performCalculation() {
        if (isCalculating) return;
        
        isCalculating = true;
        
        // Récupérer les données du formulaire
        const formData = getFormData();
        
        // Afficher le loading
        showLoading();
        
        // Envoyer la requête AJAX
        $.ajax({
            url: solar_calc_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'solar_calc_calculate',
                nonce: solar_calc_ajax.nonce,
                ...formData
            },
            success: function(response) {
                if (response.success) {
                    displayResults(response.data);
                } else {
                    showError('Erreur lors du calcul. Veuillez réessayer.');
                }
            },
            error: function() {
                showError('Erreur de connexion. Veuillez vérifier votre connexion internet.');
            },
            complete: function() {
                isCalculating = false;
                hideLoading();
            }
        });
    }
    
    function getFormData() {
        // Déterminer la surface de toit à utiliser
        let roofArea;
        if ($('input[name="roof_known"]:checked').val() === 'yes') {
            roofArea = parseFloat($('#solar-calc-roof-area').val());
        } else {
            // Utiliser la surface calculée
            const groundArea = parseFloat($('#solar-calc-ground-area').val()) || 100;
            const roofType = $('#solar-calc-roof-type').val();
            const percentage = 60; // Pourcentage fixe
            
            const roofCoefficients = {
                'flat': 1.0,
                'low': 1.1,
                'medium': 1.2,
                'steep': 1.4
            };
            
            const coefficient = roofCoefficients[roofType] || 1.1;
            roofArea = groundArea * coefficient * (percentage / 100);
        }
        
        return {
            consumption: parseInt($('#solar-calc-consumption').val()),
            bill: parseInt($('#solar-calc-bill').val()),
            roof_area: roofArea,
            orientation: $('#solar-calc-orientation').val()
        };
    }
    
    function showLoading() {
        $('#solar-calc-results').show();
        $('#solar-calc-loading').show();
        $('#solar-calc-results-content').hide();
        
        // Scroll vers les résultats
        $('html, body').animate({
            scrollTop: $('#solar-calc-results').offset().top - 20
        }, 500);
    }
    
    function hideLoading() {
        $('#solar-calc-loading').hide();
    }
    
    function displayResults(data) {
        // Mettre à jour les valeurs
        $('#solar-calc-yearly-savings').text(data.yearly_savings.toLocaleString());
        $('#solar-calc-production').text(data.annual_production.toLocaleString() + ' kWh');
        $('#solar-calc-power').text(data.power_kwc + ' kWc');
        $('#solar-calc-panels').text(data.panels_count);
        $('#solar-calc-investment').text(data.total_investment.toLocaleString() + '€');
        $('#solar-calc-payback').text(data.payback_years + ' ans');
        
        // Afficher le contenu des résultats
        $('#solar-calc-results-content').show();
        
        // Créer le graphique
        createChart(data.chart_data);
        
        // Animation des nombres
        animateNumbers();
    }
    
    function createChart(chartData) {
        const ctx = document.getElementById('solar-calc-chart');
        
        if (!ctx) return;
        
        // Détruire le graphique existant
        if (chart) {
            chart.destroy();
        }
        
        // Attendre que Chart.js soit chargé
        if (typeof Chart === 'undefined') {
            setTimeout(() => createChart(chartData), 100);
            return;
        }
        
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.years,
                datasets: [{
                    label: 'Économies cumulées',
                    data: chartData.cumulative_savings,
                    borderColor: '#D45F2C',
                    backgroundColor: 'rgba(212, 95, 44, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.2
                }, {
                    label: 'Investissement initial',
                    data: chartData.investment_line,
                    borderColor: '#303030',
                    backgroundColor: 'rgba(48, 48, 48, 0.1)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Évolution des économies dans le temps',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        color: '#303030'
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += new Intl.NumberFormat('fr-FR', {
                                    style: 'currency',
                                    currency: 'EUR',
                                    minimumFractionDigits: 0
                                }).format(context.parsed.y);
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Années',
                            color: '#303030',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(48, 48, 48, 0.1)'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Montant (€)',
                            color: '#303030',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(48, 48, 48, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR', {
                                    style: 'currency',
                                    currency: 'EUR',
                                    minimumFractionDigits: 0
                                }).format(value);
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 8
                    }
                }
            }
        });
        
        // Marquer le point de retour sur investissement
        if (chartData.payback_point && chartData.payback_point <= 20) {
            const paybackIndex = Math.round(chartData.payback_point);
            if (paybackIndex < chartData.years.length) {
                chart.data.datasets.push({
                    label: 'Retour sur investissement',
                    data: Array(chartData.years.length).fill(null).map((_, i) => 
                        i === paybackIndex ? chartData.cumulative_savings[i] : null
                    ),
                    borderColor: '#28a745',
                    backgroundColor: '#28a745',
                    pointRadius: 8,
                    pointHoverRadius: 12,
                    showLine: false,
                    pointStyle: 'circle'
                });
                chart.update();
            }
        }
    }
    
    function animateNumbers() {
        // Animation du gros nombre
        const $bigNumber = $('#solar-calc-yearly-savings');
        const targetValue = parseInt($bigNumber.text().replace(/[^\d]/g, ''));
        
        animateValue($bigNumber[0], 0, targetValue, 1500, (value) => {
            return value.toLocaleString();
        });
        
        // Animation des autres valeurs
        $('.solar-calc-detail-value').each(function() {
            const $element = $(this);
            const text = $element.text();
            const number = parseInt(text.replace(/[^\d]/g, ''));
            
            if (!isNaN(number) && number > 0) {
                const suffix = text.replace(/[\d\s,]/g, '');
                
                animateValue(this, 0, number, 1200, (value) => {
                    return Math.round(value).toLocaleString() + suffix;
                });
            }
        });
    }
    
    function animateValue(element, start, end, duration, formatFunction) {
        const startTime = performance.now();
        
        function updateValue(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Utiliser une fonction d'easing pour une animation plus fluide
            const easedProgress = easeOutQuart(progress);
            const currentValue = start + (end - start) * easedProgress;
            
            element.textContent = formatFunction ? formatFunction(currentValue) : Math.round(currentValue);
            
            if (progress < 1) {
                requestAnimationFrame(updateValue);
            }
        }
        
        requestAnimationFrame(updateValue);
    }
    
    function easeOutQuart(t) {
        return 1 - Math.pow(1 - t, 4);
    }
    
    function showError(message) {
        // Créer ou mettre à jour le message d'erreur
        let $errorDiv = $('#solar-calc-error');
        
        if ($errorDiv.length === 0) {
            $errorDiv = $('<div id="solar-calc-error" class="solar-calc-error"></div>');
            $('#solar-calc-results').prepend($errorDiv);
        }
        
        $errorDiv.html(`
            <div style="background: #fee; border: 1px solid #fcc; color: #c66; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong>Erreur :</strong> ${message}
            </div>
        `).show();
        
        // Masquer après 5 secondes
        setTimeout(() => {
            $errorDiv.fadeOut();
        }, 5000);
    }
    
    // Fonctions utilitaires
    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'EUR',
            minimumFractionDigits: 0
        }).format(amount);
    }
    
    function formatNumber(number) {
        return new Intl.NumberFormat('fr-FR').format(number);
    }
    
    // Gestion du resize pour le graphique
    $(window).on('resize', function() {
        if (chart) {
            chart.resize();
        }
    });
    
    // Export des fonctions pour debug
    window.solarCalcDebug = {
        chart: () => chart,
        calculate: performCalculation,
        formData: getFormData
    };
}); 