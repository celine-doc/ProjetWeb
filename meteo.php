<?php
date_default_timezone_set('Europe/Paris'); // Correction du fuseau horaire

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Fonction pour charger les régions depuis un fichier CSV
function chargerRegions($fichier) {
    $regions = [];

    if (($handle = fopen($fichier, "r")) !== FALSE) {
        fgetcsv($handle, 1000, ",", '"', "\\");

        while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE) {
            if (count($row) >= 7) {
                $regions[] = [
                    "id" => trim($row[0]),
                    "nom" => trim($row[3]),
                    "coords" => trim($row[6])
                ];
            }
        }
        fclose($handle);
    }

    return $regions;
}

// Fonction pour charger les départements depuis un fichier CSV
function chargerDepartements($fichier) {
    $departements = [];

    if (($handle = fopen($fichier, "r")) !== FALSE) {
        fgetcsv($handle, 1000, ",", '"', "\\");

        while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE) {
            if (count($row) >= 7) {
                $region_id = trim($row[1]);
                $dep_code = trim($row[0]);
                $dep_nom = trim($row[6]);

                if (!isset($departements[$region_id])) {
                    $departements[$region_id] = [];
                }
                $departements[$region_id][] = [
                    "code" => $dep_code,
                    "nom" => $dep_nom
                ];
            }
        }
        fclose($handle);
    }

    return $departements;
}

// Fonction pour charger les villes depuis un fichier CSV
function chargerVilles($fichier) {
    $villes = [];

    if (file_exists($fichier) && ($handle = fopen($fichier, "r")) !== FALSE) {
        fgetcsv($handle, 1000, ",", '"', "\\");

        while (($row = fgetcsv($handle, 1000, ",", '"', "\\")) !== FALSE) {
            if (count($row) >= 7) {
                $dep_code = trim($row[1]);
                $ville_nom = trim($row[4]);

                if (!isset($villes[$dep_code])) {
                    $villes[$dep_code] = [];
                }
                $villes[$dep_code][] = $ville_nom;
            }
        }
        fclose($handle);
    }

    return $villes;
}

// Fonction pour enregistrer une ville dans le fichier CSV
function enregistrerVilleDansCSV($ville) {
    $fichier = "csv/villes_consultees.csv";

    if (!file_exists($fichier)) {
        $handle = fopen($fichier, 'w');
        fputcsv($handle, ['ville', 'date'], ",", '"', "\\");
        fclose($handle);
    }

    if (($handle = fopen($fichier, 'a')) !== false) {
        $date_heure = date('Y-m-d H:i'); // Utilise l'heure locale 
        fputcsv($handle, [$ville, $date_heure], ",", '"', "\\");
        fclose($handle);
    }
}

if (isset($_GET['city'])) {
    $villeConsultee = $_GET['city'];
    // Enregistrer la ville dans le fichier CSV
    enregistrerVilleDansCSV($villeConsultee);

    // Créer un cookie pour la ville consultée
    setcookie('ville_consultee', $villeConsultee, time() + (30 * 24 * 60 * 60), "/"); // 30 jours de validité
}


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

function fetchWeatherData($city) {
    $apiKey = "9aade7de501e1aa02c00e13202801f36";
    $url = "https://api.openweathermap.org/data/2.5/forecast?q=" . urlencode($city) . "&appid=" . $apiKey . "&units=metric&cnt=40&lang=fr";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

$regions = chargerRegions("csv/v_region_2024.csv");
$departements = chargerDepartements("csv/v_departement_2024.csv");
$villes = chargerVilles("csv/cities.csv");

$regionSelectionnee = $_GET['region'] ?? null;
$departementSelectionne = $_GET['departement'] ?? null;
$city = $_GET['city'] ?? null;

$departementsSelectionnes = $regionSelectionnee ? ($departements[$regionSelectionnee] ?? []) : [];
$villesSelectionnees = $departementSelectionne ? ($villes[$departementSelectionne] ?? []) : [];
$weatherData = $city ? fetchWeatherData($city) : null;
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

        div { max-width: 600px; margin: auto; padding: 20px; background: white; border-radius: 10px; }
    </style>
</head>
<body>
<header>
    <a href="index.php"><img src="images/logosite.png" alt="Logo-Météo France" /></a>
    <nav>
        <ul>
            <li> <p><a href="index.php">Accueil</a></p> </li>
            <li> <p><a href="tech.php">Page tech</a></p></li>
            <li> <p><a href="meteo.php">Meteo</a></p></li>
            <li> <p><a href="statistiques.php">Statistiques</a></p></li>
            <li>  <p><a href="nous.php">à propos de nous</a></p></li>
        </ul>
    </nav>
</header>

<h1>Sélectionnez votre région</h1>
<img src="images/france.png" usemap="#regionsMap" alt="Carte des Régions de France">
<map name="regionsMap">
    <?php foreach ($regions as $region): ?>
        <?php if (!empty($region['coords'])): ?>
            <area shape="poly" coords="<?= htmlspecialchars($region['coords']); ?>" 
                  href="?region=<?= urlencode($region['id']); ?>" 
                  alt="<?= htmlspecialchars($region['nom']); ?>" 
                  title="<?= htmlspecialchars($region['nom']); ?>">
        <?php endif; ?>
    <?php endforeach; ?>
</map>

<?php if ($regionSelectionnee): ?>
    <h2>Choisissez un département :</h2>
    <form action="" method="get">
        <input type="hidden" name="region" value="<?= htmlspecialchars($regionSelectionnee); ?>">
        <label for="departement">Département :</label>
        <select id="departement" name="departement" onchange="this.form.submit()">
            <option value="">Sélectionnez un département</option>
            <?php foreach ($departementsSelectionnes as $departement): ?>
                <option value="<?= htmlspecialchars($departement['code']); ?>" <?= ($departementSelectionne == $departement['code']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($departement['nom']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
<?php endif; ?>

<?php if ($departementSelectionne): ?>
    <h2>Choisissez une ville :</h2>
    <form action="" method="get">
        <input type="hidden" name="region" value="<?= htmlspecialchars($regionSelectionnee); ?>">
        <input type="hidden" name="departement" value="<?= htmlspecialchars($departementSelectionne); ?>">
        <label for="city">Ville :</label>
        <select id="city" name="city" onchange="this.form.submit()">
            <option value="">Sélectionnez une ville</option>
            <?php foreach ($villesSelectionnees as $ville): ?>
                <option value="<?= htmlspecialchars($ville); ?>" <?= (isset($_GET['city']) && $_GET['city'] == $ville) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($ville); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
<?php endif; ?>

<?php if ($weatherData): ?>
    <h2>Météo à <?= htmlspecialchars($city); ?></h2>
    <p>Température actuelle : <?= round($weatherData['list'][0]['main']['temp']); ?>°C</p>
    <p>Description : <?= htmlspecialchars($weatherData['list'][0]['weather'][0]['description']); ?></p>
    <h3>Prévisions à 5 jours :</h3>
    <ul>
        <?php foreach ($weatherData['list'] as $item): ?>
            <?php if (strpos($item['dt_txt'], '12:00:00') !== false): ?>
                <li><?= date('D d M', strtotime($item['dt_txt'])); ?> : <?= round($item['main']['temp']); ?>°C, <?= htmlspecialchars($item['weather'][0]['description']); ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<h2>Dernière ville consultée :</h2>
<ul>
    <?php
    // Vérifier si le cookie 'ville_consultee' existe
    if (isset($_COOKIE['ville_consultee'])) {
        $derniereVille = $_COOKIE['ville_consultee'];
        echo "<li>" . htmlspecialchars($derniereVille) . " - " . date('Y-m-d H:i') . "</li>";
    } else {
        echo "<li>Aucune ville consultée pour le moment.</li>";
    }
    ?>
</ul>

</body>
</html>
