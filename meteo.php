<?php
date_default_timezone_set('Europe/Paris'); // On met le fuseau horaire à Paris

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Fonction pour lire les régions dans le fichier CSV
function chargerRegions($fichier) {
    $listeRegions = [];

    if (($handle = fopen($fichier, "r")) !== FALSE) {
        fgetcsv($handle, 1000, ",", '"', "\\");

        while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE) {
            if (count($row) >= 7) {
                $listeRegions[] = [
                    "id" => trim($row[0]),
                    "nom" => trim($row[3]),
                    "coords" => trim($row[6])
                ];
            }
        }
        fclose($handle);
    }

    return $listeRegions;
}

// Fonction pour lire les départements dans le fichier CSV
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
function chargerVilles($fichier) {
    $listeVilles = [];

    if (file_exists($fichier) && ($handle = fopen($fichier, "r")) !== FALSE) {
        fgetcsv($handle, 1000, ",", '"', "\\");

        while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE) {
            if (count($row) >= 7) {
                $dep_code = trim($row[1]);
                $ville_nom = trim($row[4]);

                if (!isset($listeVilles[$dep_code])) {
                    $listeVilles[$dep_code] = [];
                }
                $listeVilles[$dep_code][] = $ville_nom;
            }
        }
        fclose($handle);
    }

    return $listeVilles;
}

// Fonction pour sauvegarder une ville dans le fichier CSV
function enregistrerVilleDansCSV($ville) {
    $fichier = "csv/villes_consultees.csv";

    if (!file_exists($fichier)) {
        $handle = fopen($fichier, 'w');
        fputcsv($handle, ['ville', 'date'], ",", '"', "\\");
        fclose($handle);
    }

    // Correction de la ligne : ajout de parenthèses pour équilibrer
    if (($handle = fopen($fichier, 'a'))) {
        $date_heure = date('Y-m-d H:i'); // On prend la date et l'heure actuelles
        fputcsv($handle, [$ville, $date_heure], ",", '"', "\\");
        fclose($handle);
    }
}

if (isset($_GET['city'])) {
    $villeChoisie = $_GET['city'];
    // On enregistre la ville dans le fichier CSV
    enregistrerVilleDansCSV($villeChoisie);

    // On crée un cookie pour la ville avec une durée de 30 jours
    setcookie('ville_consultee', $villeChoisie, time() + (30 * 24 * 60 * 60), "/"); 
}

// Fonction pour lire les villes consultées dans le fichier CSV
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

// Fonction pour récupérer les données météo depuis l'API
function recupererMeteo($ville) {
    $apiKey = "9aade7de501e1aa02c00e13202801f36";
    $url = "https://api.openweathermap.org/data/2.5/forecast?q=" . urlencode($ville) . "&appid=" . $apiKey . "&units=metric&cnt=40&lang=fr";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

$listeRegions = chargerRegions("csv/v_region_2024.csv");
$listeDepartements = chargerDepartements("csv/v_departement_2024.csv");
$listeVilles = chargerVilles("csv/cities.csv");

$regionChoisie = $_GET['region'] ?? null;
$departementChoisi = $_GET['departement'] ?? null;
$villeChoisie = $_GET['city'] ?? null;
$afficherDetails = isset($_GET['details']) && $_GET['details'] == '1'; // On vérifie si on doit montrer les détails

$departementsChoisis = $regionChoisie ? ($listeDepartements[$regionChoisie] ?? []) : [];
$villesChoisies = $departementChoisi ? ($listeVilles[$departementChoisi] ?? []) : [];
$donneesMeteo = $villeChoisie ? recupererMeteo($villeChoisie) : null;

// On construit l'URL pour le bouton qui affiche/masque les détails
$urlBase = "?region=" . urlencode($regionChoisie) . "&departement=" . urlencode($departementChoisi) . "&city=" . urlencode($villeChoisie);
$urlDetails = $afficherDetails ? $urlBase : $urlBase . "&details=1";
$texteBouton = $afficherDetails ? "Cacher les détails" : "Voir les détails";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" />
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>
    <title>Carte Interactive</title>
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

        div.container { 
            max-width: 800px; 
            margin: auto; 
            padding: 20px; 
            background: white; 
            border-radius: 10px; 
        }

        /* Styles pour la météo actuelle */
        .meteo-actuelle {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .meteo-actuelle .infos-principales {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .meteo-actuelle .infos-principales img {
            width: 50px;
            height: 50px;
        }

        .meteo-actuelle .temperature {
            font-size: 2em;
            margin: 0 10px;
        }

        .meteo-actuelle .description-meteo {
            font-size: 1.1em;
            color: #555;
        }

        .meteo-actuelle .details-meteo p {
            margin: 5px 0;
            font-size: 0.9em;
        }

        .previsions-horaires {
            display: flex;
            justify-content: space-between;
            background: #e8f0fe;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .element-horaire {
            text-align: center;
        }

        .element-horaire img {
            width: 30px;
            height: 30px;
        }

        .previsions-jours {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .element-jour {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }

        .element-jour img {
            width: 30px;
            height: 30px;
        }

        /* Style pour le bouton qui montre ou cache les détails */
        .bouton-details {
            display: inline-block;
            background-color: #1e90ff;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .bouton-details:hover {
            background-color: #00b7eb;
        }

        .section-details {
            margin-top: 15px;
        }
    </style>
</head>
<body>
<header>
    <a href="index.php"><img src="images/logosite.png" alt="Logo-Météo France" /></a>
    <nav>
        <ul>
            <li><p><a href="index.php">Accueil</a></p></li>
            <li><p><a href="tech.php">Page tech</a></p></li>
            <li><p><a href="meteo.php">Météo</a></p></li>
            <li><p><a href="statistiques.php">Statistiques</a></p></li>
            <li><p><a href="nous.php">À propos de nous</a></p></li>
        </ul>
    </nav>
</header>

<div class="container">
    <h1>Sélectionnez votre région</h1>
    <img src="images/france.png" usemap="#regionsMap" alt="Carte des Régions de France">
    <map name="regionsMap">
        <?php foreach ($listeRegions as $region): ?>
            <?php if (!empty($region['coords'])): ?>
                <area shape="poly" coords="<?= htmlspecialchars($region['coords']); ?>" 
                      href="?region=<?= urlencode($region['id']); ?>" 
                      alt="<?= htmlspecialchars($region['nom']); ?>" 
                      title="<?= htmlspecialchars($region['nom']); ?>">
            <?php endif; ?>
        <?php endforeach; ?>
    </map>

    <?php if ($regionChoisie): ?>
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
    <?php endif; ?>

    <?php if ($departementChoisi): ?>
        <h2>Choisissez une ville :</h2>
        <form action="" method="get">
            <input type="hidden" name="region" value="<?= htmlspecialchars($regionChoisie); ?>">
            <input type="hidden" name="departement" value="<?= htmlspecialchars($departementChoisi); ?>">
            <label for="city">Ville :</label>
            <select id="city" name="city" onchange="this.form.submit()">
                <option value="">Sélectionnez une ville</option>
                <?php foreach ($villesChoisies as $ville): ?>
                    <option value="<?= htmlspecialchars($ville); ?>" <?= (isset($_GET['city']) && $_GET['city'] == $ville) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($ville); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    <?php endif; ?>

    <?php if ($donneesMeteo && isset($donneesMeteo['list'])): ?>
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
                    $limiteHeures = 5; // On montre les 5 prochaines heures
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
                        if ($compteurJours >= 8) break; // On limite à 8 jours
                        $nomJour = date('D, M d', strtotime($date));
                        $tempMax = round(max($data['temps']));
                        $tempMin = round(min($data['temps']));
                        $description = $data['descriptions'][0]; // On prend la première description
                        $icon = $data['icons'][0]; // On prend la première icône
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
    <?php else: ?>
        <?php if ($villeChoisie): ?>
            <p>Erreur : impossible d'avoir la météo pour <?= htmlspecialchars($villeChoisie); ?>.</p>
        <?php endif; ?>
    <?php endif; ?>

    <h2>Dernière ville consultée :</h2>
    <ul>
        <?php
        // On vérifie si le cookie existe
        if (isset($_COOKIE['ville_consultee'])) {
            $derniereVille = $_COOKIE['ville_consultee'];
            // On récupère la date de la dernière consultation
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
                // On récupère la météo actuelle de la dernière ville
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
</div>
</body>
</html>
