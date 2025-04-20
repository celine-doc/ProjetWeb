<?php
date_default_timezone_set('Europe/Paris');


// Fonction pour lire les régions dans le fichier CSV
/**
 * Loads and processes region data from a CSV file.
 *
 * @param string $fichier The path to the CSV file containing region data.
 * @return array An array of associative arrays, each representing a region.
 *               Each region array contains 'id', 'nom', and 'coords' keys.
 */
function chargerRegions($fichier) {
    $listeRegions = [];
    if (($handle = fopen($fichier, "r")) !== FALSE) {
        fgetcsv($handle, 1000, ",", '"', "\\"); // Skip header row
        while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE) {
            if (count($row) >= 7) {
                $coords = trim($row[6]);
                $coordArray = preg_split('/[\s,]+/', $coords); // Split by spaces or commas
                if (count($coordArray) % 2 === 0) { // Ensure even number of coordinates
                    $listeRegions[] = [
                        "id" => trim($row[0]),
                        "nom" => trim($row[3]),
                        "coords" => implode(',', $coordArray) // Join with commas
                    ];
                }
            }
        }
        fclose($handle);
    }
    return $listeRegions;
}

// Fonction pour lire les départements dans le fichier CSV
/**
 * Loads and processes departments data from a CSV file.
 *
 * @param string $fichier The path to the CSV file containing departments data.
 * @return array An associative array where the keys are region IDs and the values are arrays of department data.
 * Each department data is an associative array with 'code' and 'nom' keys.
 */
function chargerDepartements($fichier) {
    $listeDepartements = [];
    if (($handle = fopen($fichier, "r")) !== FALSE) {
        fgetcsv($handle, 1000, ",", '"', "\\");
        while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE) {
            if (count($row) >= 7) {
                $region_id = trim($row[1]);
                $dep_code = trim($row[0]);
                $dep_nom = trim($row[6]);
                if (!isset($listeDepartements[$region_id])) {
                    $listeDepartements[$region_id] = [];
                }
                $listeDepartements[$region_id][] = [
                    "code" => $dep_code,
                    "nom" => $dep_nom
                ];
            }
        }
        fclose($handle);
    }
    return $listeDepartements;
}

// Fonction pour lire les villes dans le fichier CSV
/**
 * Loads a list of cities from a CSV file.
 *
 * @param string $fichier The path to the CSV file.
 * @return array An associative array of cities, grouped by department code.
 * Each city is represented as an associative array with 'nom' and 'code_postal' keys.
 */
function chargerVilles($fichier) {
    $listeVilles = [];
    if (file_exists($fichier) && ($handle = fopen($fichier, "r")) !== FALSE) {
        fgetcsv($handle, 1000, ",", '"', "\\");
        while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE) {
            if (count($row) >= 7) {
                $dep_code = trim($row[1]);
                $ville_nom = trim($row[4]);
                $postal_code = trim($row[2]);
                if (!isset($listeVilles[$dep_code])) {
                    $listeVilles[$dep_code] = [];
                }
                $unique_key = $ville_nom . "|" . $postal_code;
                if (!isset($listeVilles[$dep_code][$unique_key])) {
                    $listeVilles[$dep_code][$unique_key] = [
                        'nom' => $ville_nom,
                        'code_postal' => $postal_code
                    ];
                }
            }
        }
        fclose($handle);
    }
    foreach ($listeVilles as $dep => $villes) {
        $listeVilles[$dep] = array_values($villes);
    }
    return $listeVilles;
}

// Fonction pour sauvegarder une ville dans le fichier CSV
/**
 * This function saves the given city name along with the current date and time into a CSV file.
 *
 * @param string $ville The name of the city to be saved.
 *
 * @return void
 */
function enregistrerVilleDansCSV($ville) {
    $fichier = "csv/villes_consultees.csv";
    if (!file_exists($fichier)) {
        $handle = fopen($fichier, 'w');
        fputcsv($handle, ['ville', 'date'], ",", '"', "\\");
        fclose($handle);
    }
    if (($handle = fopen($fichier, 'a'))) {
        $date_heure = date('Y-m-d H:i');
        fputcsv($handle, [$ville, $date_heure], ",", '"', "\\");
        fclose($handle);
    }
    
}

// Fonction pour lire les villes consultées
/**
 * Retrieves the list of cities visited from the CSV file.
 *
 * @return array An array of associative arrays, each representing a visited city.
 *               Each array contains 'ville' and 'date' keys.
 */
function getVillesConsultees() {
    $fichier = "csv/villes_consultees.csv";
    $villes = [];
    if (file_exists($fichier) && ($handle = fopen($fichier, 'r')) !== false) {
        fgetcsv($handle, 1000, ",", '"', "\\");
        while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== false) {
            $villes[] = [
                'ville' => $row[0],
                'date' => $row[1]
            ];
        }
        fclose($handle);
    }
    return $villes;
}

// Fonction pour récupérer les données météo depuis OpenWeatherMap
/**
 * Retrieves weather data from OpenWeatherMap for a given city.
 *
 * @param string $ville The name of the city for which to retrieve weather data.
 * @return array|null The weather data as an associative array, or null if the request fails.
 */
function recupererMeteo($ville) {
    $apiKey = "9aade7de501e1aa02c00e13202801f36";
    $url = "https://api.openweathermap.org/data/2.5/forecast?q=" . urlencode($ville) . "&appid=" . $apiKey . "&units=metric&cnt=40&lang=fr";
    $response = @file_get_contents($url);
    return $response ? json_decode($response, true) : null;
}

$listeRegions = chargerRegions("csv/v_region_2024.csv");
$listeDepartements = chargerDepartements("csv/v_departement_2024.csv");
$listeVilles = chargerVilles("csv/cities.csv");

$regionChoisie = $_GET['region'] ?? null;
$departementChoisi = $_GET['departement'] ?? null;
$villeChoisie = $_GET['city'] ?? null;
$afficherDetails = isset($_GET['details']) && $_GET['details'] == '1';

$departementsChoisis = $regionChoisie ? ($listeDepartements[$regionChoisie] ?? []) : [];
$villesChoisies = $departementChoisi ? ($listeVilles[$departementChoisi] ?? []) : [];
$donneesMeteo = $villeChoisie ? recupererMeteo($villeChoisie) : null;
if ($villeChoisie) {
    enregistrerVilleDansCSV($villeChoisie);
    setcookie('ville_consultee', $villeChoisie, time() + 86400 * 30, '/');
}

$urlBase = "?region=" . urlencode($regionChoisie ?? '') . "&departement=" . urlencode($departementChoisi ?? '') . "&city=" . urlencode($villeChoisie ?? '');
$urlDetails = $afficherDetails ? $urlBase : $urlBase . "&details=1";
$texteBouton = $afficherDetails ? "Cacher les détails" : "Voir les détails";

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css" id="light-theme"/>
    <link rel="stylesheet" href="./css/alternative.css" id="dark-theme" disabled/>
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>
    <title>Carte Interactive</title>
</head>
<body>
<header>
    <a href="index.php" class="header-logo">
        <img src="images/logosite.png" alt="Logo-Météo France" style="height: 120px;">
    </a>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="meteo.php">Meteo</a></li>
            <li><a href="statistiques.php">Statistiques</a></li>
        </ul>
    </nav>
    <div id="theme-switcher" style="position: absolute; top: 20px; right: 20px;">
        <button onclick="setTheme('light')">☀️ Mode clair</button>
        <button onclick="setTheme('dark')">🌙 Mode sombre</button>
    </div>
</header>

<main class="main-content">
    <section>
        <h1>Sélectionnez votre région</h1>
        <img src="images/france.png" usemap="#regionsMap" alt="Carte des Régions de France">
        <map name="regionsMap">
    <?php foreach ($listeRegions as $region): ?>
        <?php if (!empty($region['coords'])): ?>
            <?php 
            // Split coords into array and check count
            $coordsArray = explode(',', $region['coords']);
            if (count($coordsArray) % 2 === 0): 
            ?>
                <area shape="poly" 
                      coords="<?= htmlspecialchars($region['coords']) ?>" 
                      href="?region=<?= urlencode($region['id']) ?>" 
                      alt="<?= htmlspecialchars($region['nom']) ?>" 
                      title="<?= htmlspecialchars($region['nom']) ?>">
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</map>
    </section>

    <?php if ($regionChoisie): ?>
        <section>
            <h2>Choisissez un département :</h2>
            <form action="" method="get">
                <input type="hidden" name="region" value="<?= htmlspecialchars($regionChoisie); ?>">
                <label for="departement">Département :</label>
                <select id="departement" name="departement" onchange="this.form.submit()">
                    <option value="">Sélectionnez un département</option>
                    <?php foreach ($departementsChoisis as $departement): ?>
                        <option value="<?= htmlspecialchars($departement['code']); ?>" <?= ($departementChoisi == $departement['code']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($departement['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </section>
    <?php endif; ?>

    <?php if ($departementChoisi): ?>
        <section>
            <h2>Choisissez une ville :</h2>
            <form action="" method="get">
                <input type="hidden" name="region" value="<?= htmlspecialchars($regionChoisie); ?>">
                <input type="hidden" name="departement" value="<?= htmlspecialchars($departementChoisi); ?>">
                <label for="city">Ville :</label>
                <select id="city" name="city" onchange="this.form.submit()">
                    <option value="">Sélectionnez une ville</option>
                    <?php foreach ($villesChoisies as $ville): ?>
                        <?php 
                        $villeNom = htmlspecialchars($ville['nom']);
                        $codePostal = htmlspecialchars($ville['code_postal']);
                        ?>
                        <option value="<?= $villeNom; ?>" <?= ($villeChoisie == $villeNom) ? 'selected' : ''; ?>>
                            <?= $villeNom; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </section>
    <?php endif; ?>

    <?php if ($donneesMeteo && isset($donneesMeteo['list'])): ?>
        <section>
            <h2>Météo à <?= htmlspecialchars($villeChoisie); ?></h2>
            <div class="meteo-actuelle">
                <div class="infos-principales">
                    <img src="https://openweathermap.org/img/wn/<?= $donneesMeteo['list'][0]['weather'][0]['icon']; ?>@2x.png" alt="Icône Météo">
                    <div class="temperature"><?= round($donneesMeteo['list'][0]['main']['temp']); ?>°C</div>
                    <div class="description-meteo"><?= htmlspecialchars($donneesMeteo['list'][0]['weather'][0]['description']); ?></div>
                </div>
                <div class="details-meteo">
                    <p><strong>Ressenti :</strong> <?= round($donneesMeteo['list'][0]['main']['feels_like']); ?>°C</p>
                    <p><strong>Humidité :</strong> <?= $donneesMeteo['list'][0]['main']['humidity']; ?>%</p>
                    <p><strong>Pression :</strong> <?= $donneesMeteo['list'][0]['main']['pressure']; ?>hPa</p>
                    <p><strong>Vitesse du vent :</strong> <?= round($donneesMeteo['list'][0]['wind']['speed']); ?>m/s</p>
                </div>
            </div>
            <a href="<?= htmlspecialchars($urlDetails); ?>" class="bouton-details"><?= $texteBouton; ?></a>

            <?php if ($afficherDetails): ?>
                <div class="section-details">
                    <h3>Prévisions chaque 3 heures</h3>
                    <div class="previsions-horaires">
                        <?php
                        $limiteHeures = 5;
                        for ($i = 0; $i < $limiteHeures && $i < count($donneesMeteo['list']); $i++):
                            $heure = date('H:i', strtotime($donneesMeteo['list'][$i]['dt_txt']));
                            $temp = round($donneesMeteo['list'][$i]['main']['temp']);
                            $icon = $donneesMeteo['list'][$i]['weather'][0]['icon'];
                            $precipitation = isset($donneesMeteo['list'][$i]['rain']['3h']) ? round($donneesMeteo['list'][$i]['rain']['3h'], 1) : 0;
                        ?>
                            <div class="element-horaire">
                                <p><?= $heure; ?></p>
                                <img src="https://openweathermap.org/img/wn/<?= $icon; ?>@2x.png" alt="Icône Météo">
                                <p><?= $temp; ?>°C</p>
                                <p>Pluie : <?= $precipitation; ?>mm</p>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <h3>Prévisions sur plusieurs jours</h3>
                    <div class="previsions-jours">
                        <?php
                        $donneesJours = [];
                        foreach ($donneesMeteo['list'] as $item) {
                            $date = date('Y-m-d', strtotime($item['dt_txt']));
                            if (!isset($donneesJours[$date])) {
                                $donneesJours[$date] = [
                                    'temps' => [],
                                    'descriptions' => [],
                                    'icons' => []
                                ];
                            }
                            $donneesJours[$date]['temps'][] = $item['main']['temp'];
                            $donneesJours[$date]['descriptions'][] = $item['weather'][0]['description'];
                            $donneesJours[$date]['icons'][] = $item['weather'][0]['icon'];
                        }

                        $compteurJours = 0;
                        foreach ($donneesJours as $date => $data):
                            if ($compteurJours >= 8) break;
                            $nomJour = date('D, M d', strtotime($date));
                            $tempMax = round(max($data['temps']));
                            $tempMin = round(min($data['temps']));
                            $description = $data['descriptions'][0];
                            $icon = $data['icons'][0];
                        ?>
                            <div class="element-jour">
                                <p><?= $nomJour; ?></p>
                                <img src="https://openweathermap.org/img/wn/<?= $icon; ?>@2x.png" alt="Icône Météo">
                                <p><?= $tempMax; ?> / <?= $tempMin; ?>°C</p>
                                <p><?= htmlspecialchars($description); ?></p>
                            </div>
                        <?php
                            $compteurJours++;
                        endforeach;
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    <?php else: ?>
        <?php if ($villeChoisie): ?>
            <section>
                <p>Erreur : impossible d'obtenir la météo pour <?= htmlspecialchars($villeChoisie); ?>.</p>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <section>
        <h2>Dernière ville consultée :</h2>
        <ul>
            <?php
            if (isset($_COOKIE['ville_consultee'])) {
                $derniereVille = $_COOKIE['ville_consultee'];
                $villesConsultees = getVillesConsultees();
                $dateConsultation = null;
                foreach (array_reverse($villesConsultees) as $consultation) {
                    if ($consultation['ville'] === $derniereVille) {
                        $dateConsultation = $consultation['date'];
                        break;
                    }
                }
                if ($dateConsultation) {
                    echo "<li>" . htmlspecialchars($derniereVille) . " - Consultée le " . htmlspecialchars($dateConsultation) . "<br>";
                    $meteoDerniereVille = recupererMeteo($derniereVille);
                    if ($meteoDerniereVille && isset($meteoDerniereVille['list'][0])) {
                        ?>
                        <div class="meteo-actuelle">
                            <div class="infos-principales">
                                <img src="https://openweathermap.org/img/wn/<?= $meteoDerniereVille['list'][0]['weather'][0]['icon']; ?>@2x.png" alt="Icône Météo">
                                <div class="temperature"><?= round($meteoDerniereVille['list'][0]['main']['temp']); ?>°C</div>
                                <div class="description-meteo"><?= htmlspecialchars($meteoDerniereVille['list'][0]['weather'][0]['description']); ?></div>
                            </div>
                            <div class="details-meteo">
                                <p><strong>Ressenti :</strong> <?= round($meteoDerniereVille['list'][0]['main']['feels_like']); ?>°C</p>
                                <p><strong>Humidité :</strong> <?= $meteoDerniereVille['list'][0]['main']['humidity']; ?>%</p>
                                <p><strong>Pression :</strong> <?= $meteoDerniereVille['list'][0]['main']['pressure']; ?>hPa</p>
                                <p><strong>Vitesse du vent :</strong> <?= round($meteoDerniereVille['list'][0]['wind']['speed']); ?>m/s</p>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo "Pas de météo disponible pour l'instant.</li>";
                    }
                } else {
                    echo "<li>" . htmlspecialchars($derniereVille) . " - Date de consultation introuvable.<br>";
                    echo "Pas de météo disponible pour l'instant.</li>";
                }
            } else {
                echo "<li>Aucune ville n'a été consultée pour le moment.</li>";
            }
            ?>
        </ul>
    </section>
</main>

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

// Enregistrer le choix dans le cookie (valable 30 jours)
    document.cookie = `theme=${mode}; path=/; max-age=${30 * 24 * 60 * 60}`;
}

// Obtenir la valeur du cookie
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    return parts.length === 2 ? parts.pop().split(';').shift() : null;
}

// Au chargement de la page, définir le thème à partir du cookie
window.addEventListener('DOMContentLoaded', () => {
    const savedTheme = getCookie('theme');
    if (savedTheme === 'dark') {
        setTheme('dark');
    } else {
        setTheme('light');
    }
});
</script>

</body>
</html>