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
    <link rel="stylesheet" href="style.css" />
    <title>Statistiques des visites</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
        }

        header img {
            height: 100px; 
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 15px;
        }

        nav ul li {
            display: inline;
        }

        div { max-width: 600px; margin: auto; padding: 20px; background: white; border-radius: 10px; }
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        canvas {
            max-width: 800px;
            margin: 0 auto;
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
            <li> <p><a href="nous.php">à propos de nous</a></p></li>
        </ul>
    </nav>
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
      <a href="#">Plan du Site</a>
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