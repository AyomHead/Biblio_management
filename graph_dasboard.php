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
            max-width: 1800px; /* Augmenté pour accommoder 4 graphiques */
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
            grid-template-columns: repeat(4, 1fr); /* 4 colonnes égales */
            gap: 15px; /* Espacement réduit */
            margin-top: 20px;
        }
        
        .chart-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 15px; /* Padding réduit */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .chart-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(0, 0, 0, 0.4);
        }
        
        .chart-card h3 {
            color: var(--primary-color);
            margin-bottom: 12px; /* Marge réduite */
            font-size: 16px; /* Taille de police réduite */
            text-align: center;
            font-weight: 600;
        }
        
        .chart-container {
            position: relative;
            width: 100%;
            height: 250px; /* Hauteur réduite */
        }
        
        /* Responsive pour tablettes */
        @media (max-width: 1200px) {
            .charts-grid {
                grid-template-columns: repeat(2, 1fr); /* 2 colonnes sur tablettes */
            }
        }
        
        /* Responsive pour mobile */
        @media (max-width: 768px) {
            .charts-grid {
                grid-template-columns: 1fr; /* 1 colonne sur mobile */
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
            
            <!-- Graphique 5: Taux d'emprunts -->
            <div class="chart-card">
                <h3>Taux d'emprunts et retours</h3>
                <div class="chart-container">
                    <canvas id="borrowReturnRateChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
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
        
        // Données simulées pour les graphiques
        const simulatedData = {
            userRegistrations: {
                labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30'],
                data: [3, 5, 7, 4, 6, 8, 10, 7, 9, 12, 8, 6, 10, 13, 11, 9, 12, 15, 13, 11, 14, 16, 12, 15, 17, 14, 16, 18, 15, 17]
            },
            borrowEvolution: {
                labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30'],
                data: [12, 15, 18, 14, 16, 20, 22, 19, 23, 25, 21, 18, 22, 26, 24, 20, 25, 28, 26, 23, 27, 30, 25, 28, 32, 27, 30, 35, 30, 33]
            },
            booksByCategory: {
                labels: ['Fiction', 'Histoire', 'Science', 'Art', 'Philosophie', 'Littérature'],
                data: [120, 85, 75, 60, 45, 90]
            },
            monthlyStats: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                newUsers: [45, 52, 48, 60, 55, 65],
                borrowings: [210, 230, 250, 280, 300, 350],
                reservations: [75, 85, 80, 95, 100, 120]
            },
            borrowReturnRate: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                borrows: [210, 230, 250, 280, 300, 350],
                returns: [190, 215, 240, 260, 290, 330]
            },
            popularBooks: {
                labels: ['L\'Étranger', 'Au coeur des ténèbres', 'Une si longue lettre', 'Sous l\'orage', 'Le vieux nègre et la médaille'],
                data: [85, 72, 68, 63, 59]
            }
        };
        
        // Initialisation des graphiques
        function initCharts() {
            // Graphique: Évolution des inscriptions
            const userRegistrationsCtx = document.getElementById('userRegistrationsChart');
            if (userRegistrationsCtx) {
                new Chart(userRegistrationsCtx, {
                    type: 'line',
                    data: {
                        labels: simulatedData.userRegistrations.labels,
                        datasets: [{
                            label: 'Inscriptions',
                            data: simulatedData.userRegistrations.data,
                            borderColor: chartColors.primary,
                            backgroundColor: chartColors.primaryLight,
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: chartColors.primary,
                            pointRadius: 3, /* Point réduit */
                            pointHoverRadius: 5 /* Point réduit */
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
                                        size: 12 /* Taille de police réduite */
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: 'rgba(255, 255, 255, 0.7)',
                                    stepSize: 5,
                                    font: {
                                        size: 10 /* Taille de police réduite */
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
                                        size: 10 /* Taille de police réduite */
                                    },
                                    maxTicksLimit: 10 /* Réduire le nombre de ticks */
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                }
                            }
                        }
                    }
                });
            }
            
            // Graphique: Évolution des emprunts
            const borrowEvolutionCtx = document.getElementById('borrowEvolutionChart');
            if (borrowEvolutionCtx) {
                new Chart(borrowEvolutionCtx, {
                    type: 'bar',
                    data: {
                        labels: simulatedData.borrowEvolution.labels,
                        datasets: [{
                            label: 'Emprunts',
                            data: simulatedData.borrowEvolution.data,
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
                                        size: 12 /* Taille de police réduite */
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: 'rgba(255, 255, 255, 0.7)',
                                    stepSize: 10,
                                    font: {
                                        size: 10 /* Taille de police réduite */
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
                                        size: 10 /* Taille de police réduite */
                                    },
                                    maxTicksLimit: 10 /* Réduire le nombre de ticks */
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                }
                            }
                        }
                    }
                });
            }
            
            // Graphique: Livres par catégorie
            const booksByCategoryCtx = document.getElementById('booksByCategoryChart');
            if (booksByCategoryCtx) {
                new Chart(booksByCategoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: simulatedData.booksByCategory.labels,
                        datasets: [{
                            data: simulatedData.booksByCategory.data,
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
                                        size: 10 /* Taille de police réduite */
                                    }
                                }
                            }
                        }
                    }
                });
            }
            
            
            // Graphique: Taux d'emprunts et retours
            const borrowReturnRateCtx = document.getElementById('borrowReturnRateChart');
            if (borrowReturnRateCtx) {
                new Chart(borrowReturnRateCtx, {
                    type: 'line',
                    data: {
                        labels: simulatedData.borrowReturnRate.labels,
                        datasets: [
                            {
                                label: 'Emprunts',
                                data: simulatedData.borrowReturnRate.borrows,
                                borderColor: chartColors.primary,
                                backgroundColor: chartColors.primaryLight,
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3
                            },
                            {
                                label: 'Retours',
                                data: simulatedData.borrowReturnRate.returns,
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
                                        size: 12 /* Taille de police réduite */
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
                                        size: 10 /* Taille de police réduite */
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
                                        size: 10 /* Taille de police réduite */
                                    }
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                }
                            }
                        }
                    }
                });
            }
        }
        
        // Initialiser les graphiques au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
        });
    </script>
</body>
</html>