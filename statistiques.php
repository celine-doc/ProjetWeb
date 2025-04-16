<?php
$csvFile = 'csv/villes_consultees.csv';
$cityVisits = [];

if (($handle = fopen($csvFile, "r")) !== FALSE) {
    // Ignorer l'en-tête
    fgetcsv($handle, 1000, ",");
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $city = trim($data[0]); // Nettoyer les espaces
        if (!empty($city)) { // Vérifier que la ville n'est pas vide
            if (!isset($cityVisits[$city])) {
                $cityVisits[$city] = 0;
            }
            $cityVisits[$city]++;
        }
    }
    fclose($handle);
}

// Trier par fréquence décroissante
arsort($cityVisits);

// Limiter aux 10 premières villes et regrouper les autres
$topCities = array_slice($cityVisits, 0, 10, true); // Prendre les 10 premières
$otherVisits = array_sum(array_slice($cityVisits, 10)); // Somme des visites des autres villes

if ($otherVisits > 0) {
    $topCities['Autres'] = $otherVisits; // Ajouter une catégorie "Autres"
}
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
    </style>
</head>
<body>
<header>
    <a href="index.php"><img src="images/logosite.png" alt="Logo-Météo France" /></a>
    <nav>
        <ul>
            <li> <p><a href="index.php">Accueil</a></p> </li>
            <li> <p><a href="meteo.php">Meteo</a></p></li>
            <li> <p><a href="statistiques.php">Statistiques</a></p></li>
            <li>  <p><a href="nous.php">à propos de nous</a></p></li>
        </ul>
    </nav>
        <div id="theme-switcher" style="position: absolute; top: 20px; right: 20px;">
      <button onclick="setTheme('light')">☀️ Mode clair</button>
      <button onclick="setTheme('dark')">🌙 Mode sombre</button>
    </div>
</header>
    <h1>Statistiques de visites par ville</h1>
    <canvas id="visitsChart" width="800" height="400"></canvas>

    <script>
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
<script>
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

    // Store choice in cookie (valid for 30 days)
    document.cookie = `theme=${mode}; path=/; max-age=${30 * 24 * 60 * 60}`;
}

// Get cookie value
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    return parts.length === 2 ? parts.pop().split(';').shift() : null;
}

// On page load, set theme from cookie
window.addEventListener('DOMContentLoaded', () => {
    const savedTheme = getCookie('theme');
    if (savedTheme === 'dark') {
        setTheme('dark');
    } else {
        setTheme('light'); // Default to light
    }
});
</script>

</body>
</html>