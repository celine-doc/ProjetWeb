<?php
$csvFile = 'csv/villes_consultees.csv';
$cityVisits = [];
$totalVisits = 0; // Nombre total de visiteurs
$dailyVisits = array_fill(0, 5, 0); // Tableau pour les 5 derniers jours
$dates = []; // Labels pour les 5 derniers jours

// Générer les dates des 5 derniers jours (inclus aujourd'hui)
$today = new DateTime('2025-04-17'); // Date actuelle
for ($i = 4; $i >= 0; $i--) {
    $date = (clone $today)->modify("-$i days");
    $dates[] = $date->format('Y-m-d');
}

if (($handle = fopen($csvFile, "r")) !== FALSE) {
    // Ignorer l'en-tête
    fgetcsv($handle, 1000, ",");
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $city = trim($data[0]); // Ville
        $visitDateTime = isset($data[1]) ? trim($data[1], '"') : ''; // Date et heure (enlever les guillemets)
        
        // Compter les visites par ville
        if (!empty($city)) {
            if (!isset($cityVisits[$city])) {
                $cityVisits[$city] = 0;
            }
            $cityVisits[$city]++;
            $totalVisits++; // Compter le total des visites
            
            // Extraire la date (sans l'heure) pour les visites quotidiennes
            if (!empty($visitDateTime)) {
                $visitDate = explode(' ', $visitDateTime)[0]; // Prendre uniquement la partie YYYY-MM-DD
                $index = array_search($visitDate, $dates);
                if ($index !== false) {
                    $dailyVisits[$index]++;
                }
            }
        }
    }
    fclose($handle);
}

// Trier par fréquence décroissante pour les villes
arsort($cityVisits);

// Limiter aux 10 premières villes et regrouper les autres
$topCities = array_slice($cityVisits, 0, 10, true);
$otherVisits = array_sum(array_slice($cityVisits, 10));
if ($otherVisits > 0) {
    $topCities['Autres'] = $otherVisits;
}

// Formater les dates pour l'affichage (par exemple, "17 Avr")
$formattedDates = array_map(function($date) {
    $dt = new DateTime($date);
    return $dt->format('d M');
}, $dates);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css" id="light-theme"/>
    <link rel="stylesheet" href="./css/alternative.css" id="dark-theme" disabled/>
    <title>Statistiques des visites</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        canvas {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 50px;
            margin-bottom: 100px;
        }
        h2 {
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>
<body>
<header>
    <a href="index.php"><img src="images/logosite.png" alt="Logo-Météo France" /></a>
    <nav>
        <ul>
            <li><p><a href="index.php">Accueil</a></p></li>
            <li><p><a href="meteo.php">Meteo</a></p></li>
            <li><p><a href="statistiques.php">Statistiques</a></p></li>
            <li><p><a href="nous.php">à propos de nous</a></p></li>
        </ul>
    </nav>
    <div id="theme-switcher" style="position: absolute; top: 20px; right: 20px;">
        <button onclick="setTheme('light')">☀️ Mode clair</button>
        <button onclick="setTheme('dark')">🌙 Mode sombre</button>
    </div>
</header>

<h1>Statistiques de visites</h1>

<h2>Villes les plus consultées</h2>
<canvas id="visitsChart" width="800" height="400"></canvas>

<h2>Nombre de visites du site des 5 derniers jours et total</h2>
<canvas id="dailyVisitsChart" width="800" height="400"></canvas>

<script>
    // Graphique 1 : Villes les plus consultées
    const ctx = document.getElementById('visitsChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($topCities)) ?>,
            datasets: [{
                label: 'Nombre de visites',
                data: <?= json_encode(array_values($topCities)) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return Number.isInteger(value) ? value : null;
                        }
                    },
                    title: {
                        display: true,
                        text: 'Nombre de visites'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Villes'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });

    // Graphique 2 : Visiteurs quotidiens (5 derniers jours) et total
    const dailyCtx = document.getElementById('dailyVisitsChart').getContext('2d');
    const dailyChart = new Chart(dailyCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_merge($formattedDates, ['Total'])) ?>,
            datasets: [{
                label: 'Nombre de visiteurs',
                data: <?= json_encode(array_merge($dailyVisits, [$totalVisits])) ?>,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)', // Jour 1 (même bleu que le premier graphique)
                    'rgba(54, 162, 235, 0.6)', // Jour 2
                    'rgba(54, 162, 235, 0.6)', // Jour 3
                    'rgba(54, 162, 235, 0.6)', // Jour 4
                    'rgba(54, 162, 235, 0.6)', // Jour 5
                    'rgba(30, 144, 255, 0.6)'  // Total (bleu plus foncé pour distinction)
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(30, 144, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return Number.isInteger(value) ? value : null;
                        }
                    },
                    title: {
                        display: true,
                        text: 'Nombre de visiteurs'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Jours et Total'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });

    function setTheme(mode) {
        const light = document.getElementById('light-theme');
        const dark = document.getElementById('dark-theme');
        if (mode === 'dark') {
            light.disabled = true;
            dark.disabled = false;
        } else {
            light.disabled = false;
            dark.disabled = true;
        }
        document.cookie = `theme=${mode}; path=/; max-age=${30 * 24 * 60 * 60}`;
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        return parts.length === 2 ? parts.pop().split(';').shift() : null;
    }

    window.addEventListener('DOMContentLoaded', () => {
        const savedTheme = getCookie('theme');
        if (savedTheme === 'dark') {
            setTheme('dark');
        } else {
            setTheme('light');
        }
    });
</script>

<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h4>Aide ?</h4>
            <p>Réalisé par Céline ARKAM - Waseel AYASH ®</p>
            <a href="nous.php">à propos de nous</a>
        </div>
        <div class="footer-section">
            <h4>Informations</h4>
            <a href="tech.php">Page tech</a>
            <a href="plan.php">Plan du site</a>
        </div>
        <div class="footer-section">
            <h4>Organisme</h4>
            <p>CY Cergy Paris Université © 2025</p>
            <p>Mis à jour le : <strong>20/04/2025</strong></p>
        </div>
    </div>
</footer>

</body>
</html>