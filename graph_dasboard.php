<?php
// Connexion à la base de données
require_once 'includes/config.php';

// 1. Évolution des inscriptions (30 derniers jours)
$queryInscriptions = "
    SELECT DATE(created_date) as date, COUNT(*) as count 
    FROM users 
    WHERE created_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY DATE(created_date)
    ORDER BY date
";
$stmtInscriptions = $pdo->query($queryInscriptions);
$inscriptionsData = $stmtInscriptions->fetchAll(PDO::FETCH_ASSOC);

// Préparer les données pour le graphique
$inscriptionsLabels = [];
$inscriptionsCounts = [];

foreach ($inscriptionsData as $data) {
    $inscriptionsLabels[] = date('d/m', strtotime($data['date']));
    $inscriptionsCounts[] = $data['count'];
}

// 2. Évolution des emprunts (30 derniers jours)
$queryEmprunts = "
    SELECT DATE(borrow_date) as date, COUNT(*) as count 
    FROM borrowings 
    WHERE borrow_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY DATE(borrow_date)
    ORDER BY date
";
$stmtEmprunts = $pdo->query($queryEmprunts);
$empruntsData = $stmtEmprunts->fetchAll(PDO::FETCH_ASSOC);

// Préparer les données pour le graphique
$empruntsLabels = [];
$empruntsCounts = [];

foreach ($empruntsData as $data) {
    $empruntsLabels[] = date('d/m', strtotime($data['date']));
    $empruntsCounts[] = $data['count'];
}

// 3. Répartition par catégorie
$queryCategories = "
    SELECT category, COUNT(*) as count 
    FROM books 
    WHERE category IS NOT NULL 
    GROUP BY category 
    ORDER BY count DESC
";
$stmtCategories = $pdo->query($queryCategories);
$categoriesData = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);

// Préparer les données pour le graphique
$categoriesLabels = [];
$categoriesCounts = [];

foreach ($categoriesData as $data) {
    $categoriesLabels[] = $data['category'];
    $categoriesCounts[] = $data['count'];
}

// 4. Taux d'emprunts et retours (6 derniers mois)
$queryTauxEmprunts = "
    SELECT 
        DATE_FORMAT(borrow_date, '%Y-%m') as month,
        COUNT(*) as borrow_count,
        SUM(CASE WHEN return_date IS NOT NULL THEN 1 ELSE 0 END) as return_count
    FROM borrowings 
    WHERE borrow_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(borrow_date, '%Y-%m')
    ORDER BY month
";
$stmtTauxEmprunts = $pdo->query($queryTauxEmprunts);
$tauxEmpruntsData = $stmtTauxEmprunts->fetchAll(PDO::FETCH_ASSOC);

// Préparer les données pour le graphique
$tauxLabels = [];
$borrowCounts = [];
$returnCounts = [];

foreach ($tauxEmpruntsData as $data) {
    $tauxLabels[] = date('M Y', strtotime($data['month'] . '-01'));
    $borrowCounts[] = $data['borrow_count'];
    $returnCounts[] = $data['return_count'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphiques d'administration - Bibliothèque Nationale du Bénin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Le style CSS reste inchangé */
        :root {
            --primary-color: #03d476;
            --secondary-color: #1a4657;
            --dark-bg: #013244;
            --card-bg: rgba(26, 70, 87, 0.5);
            --text-light: rgba(255, 255, 255, 0.85);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: var(--dark-bg);
            color: var(--text-light);
            min-height: 100vh;
            padding: 2rem;
        }
        
        .container {
            max-width: 1800px;
            margin: 0 auto;
        }
        
        h1 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 2rem;
            font-weight: 600;
        }
        
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        
        .chart-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .chart-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(0, 0, 0, 0.4);
        }
        
        .chart-card h3 {
            color: var(--primary-color);
            margin-bottom: 12px;
            font-size: 16px;
            text-align: center;
            font-weight: 600;
        }
        
        .chart-container {
            position: relative;
            width: 100%;
            height: 250px;
        }
        
        @media (max-width: 1200px) {
            .charts-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
            
            body {
                padding: 1rem;
            }
            
            .chart-container {
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <div class="container mb-20">
        <div class="charts-grid">
            <!-- Graphique 1: Évolution des inscriptions -->
            <div class="chart-card">
                <h3>Évolution des inscriptions (30 jours)</h3>
                <div class="chart-container">
                    <canvas id="userRegistrationsChart"></canvas>
                </div>
            </div>
            
            <!-- Graphique 2: Évolution des emprunts -->
            <div class="chart-card">
                <h3>Évolution des emprunts (30 jours)</h3>
                <div class="chart-container">
                    <canvas id="borrowEvolutionChart"></canvas>
                </div>
            </div>
            
            <!-- Graphique 3: Livres par catégorie -->
            <div class="chart-card">
                <h3>Répartition par catégorie</h3>
                <div class="chart-container">
                    <canvas id="booksByCategoryChart"></canvas>
                </div>
            </div>
            
            <!-- Graphique 4: Taux d'emprunts -->
            <div class="chart-card">
                <h3>Taux d'emprunts et retours</h3>
                <div class="chart-container">
                    <canvas id="borrowReturnRateChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Conversion des données PHP en JavaScript
        const inscriptionsLabels = <?php echo json_encode($inscriptionsLabels); ?>;
        const inscriptionsData = <?php echo json_encode($inscriptionsCounts); ?>;
        
        const empruntsLabels = <?php echo json_encode($empruntsLabels); ?>;
        const empruntsData = <?php echo json_encode($empruntsCounts); ?>;
        
        const categoriesLabels = <?php echo json_encode($categoriesLabels); ?>;
        const categoriesData = <?php echo json_encode($categoriesCounts); ?>;
        
        const tauxLabels = <?php echo json_encode($tauxLabels); ?>;
        const borrowData = <?php echo json_encode($borrowCounts); ?>;
        const returnData = <?php echo json_encode($returnCounts); ?>;
        
        // Couleurs harmonieuses avec le thème
        const chartColors = {
            primary: '#03d476',
            primaryLight: 'rgba(3, 212, 118, 0.2)',
            secondary: '#3498db',
            secondaryLight: 'rgba(52, 152, 219, 0.2)',
            accent: '#f39c12',
            accentLight: 'rgba(243, 156, 18, 0.2)',
            danger: '#e74c3c',
            dangerLight: 'rgba(231, 76, 60, 0.2)',
            purple: '#9b59b6',
            purpleLight: 'rgba(155, 89, 182, 0.2)',
            teal: '#1abc9c',
            tealLight: 'rgba(26, 188, 156, 0.2)'
        };
        
        // Initialisation des graphiques
        function initCharts() {
            // Graphique: Évolution des inscriptions
            const userRegistrationsCtx = document.getElementById('userRegistrationsChart');
            if (userRegistrationsCtx && inscriptionsData.length > 0) {
                new Chart(userRegistrationsCtx, {
                    type: 'line',
                    data: {
                        labels: inscriptionsLabels,
                        datasets: [{
                            label: 'Inscriptions',
                            data: inscriptionsData,
                            borderColor: chartColors.primary,
                            backgroundColor: chartColors.primaryLight,
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: chartColors.primary,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#fff',
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: 'rgba(255, 255, 255, 0.7)',
                                    stepSize: 1,
                                    font: {
                                        size: 10
                                    }
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                }
                            },
                            x: {
                                ticks: {
                                    color: 'rgba(255, 255, 255, 0.7)',
                                    font: {
                                        size: 10
                                    },
                                    maxTicksLimit: 10
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                }
                            }
                        }
                    }
                });
            } else if (inscriptionsData.length === 0) {
                userRegistrationsCtx.parentElement.innerHTML = '<p class="text-center">Aucune donnée disponible</p>';
            }
            
            // Graphique: Évolution des emprunts
            const borrowEvolutionCtx = document.getElementById('borrowEvolutionChart');
            if (borrowEvolutionCtx && empruntsData.length > 0) {
                new Chart(borrowEvolutionCtx, {
                    type: 'bar',
                    data: {
                        labels: empruntsLabels,
                        datasets: [{
                            label: 'Emprunts',
                            data: empruntsData,
                            backgroundColor: chartColors.secondary,
                            borderColor: chartColors.secondary,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#fff',
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: 'rgba(255, 255, 255, 0.7)',
                                    stepSize: 1,
                                    font: {
                                        size: 10
                                    }
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                }
                            },
                            x: {
                                ticks: {
                                    color: 'rgba(255, 255, 255, 0.7)',
                                    font: {
                                        size: 10
                                    },
                                    maxTicksLimit: 10
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                }
                            }
                        }
                    }
                });
            } else if (empruntsData.length === 0) {
                borrowEvolutionCtx.parentElement.innerHTML = '<p class="text-center">Aucune donnée disponible</p>';
            }
            
            // Graphique: Livres par catégorie
            const booksByCategoryCtx = document.getElementById('booksByCategoryChart');
            if (booksByCategoryCtx && categoriesData.length > 0) {
                new Chart(booksByCategoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: categoriesLabels,
                        datasets: [{
                            data: categoriesData,
                            backgroundColor: [
                                chartColors.primary,
                                chartColors.secondary,
                                chartColors.accent,
                                chartColors.purple,
                                chartColors.teal,
                                chartColors.danger
                            ],
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    color: '#fff',
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });
            } else if (categoriesData.length === 0) {
                booksByCategoryCtx.parentElement.innerHTML = '<p class="text-center">Aucune donnée disponible</p>';
            }
            
            // Graphique: Taux d'emprunts et retours
            const borrowReturnRateCtx = document.getElementById('borrowReturnRateChart');
            if (borrowReturnRateCtx && borrowData.length > 0) {
                new Chart(borrowReturnRateCtx, {
                    type: 'line',
                    data: {
                        labels: tauxLabels,
                        datasets: [
                            {
                                label: 'Emprunts',
                                data: borrowData,
                                borderColor: chartColors.primary,
                                backgroundColor: chartColors.primaryLight,
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3
                            },
                            {
                                label: 'Retours',
                                data: returnData,
                                borderColor: chartColors.secondary,
                                backgroundColor: chartColors.secondaryLight,
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#fff',
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: 'rgba(255, 255, 255, 0.7)',
                                    font: {
                                        size: 10
                                    }
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                }
                            },
                            x: {
                                ticks: {
                                    color: 'rgba(255, 255, 255, 0.7)',
                                    font: {
                                        size: 10
                                    }
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                }
                            }
                        }
                    }
                });
            } else if (borrowData.length === 0) {
                borrowReturnRateCtx.parentElement.innerHTML = '<p class="text-center">Aucune donnée disponible</p>';
            }
        }
        
        // Initialiser les graphiques au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
        });
    </script>
</body>
</html>