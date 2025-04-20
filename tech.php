<?php

// 1. Fonction pour récupérer les données de l'API NASA (APOD)
function getNasaApodData($apiKey, $date) {
    // URL de l'API NASA
    $url = "https://api.nasa.gov/planetary/apod?api_key=$apiKey&date=$date";
    // Utilisation de file_get_contents pour récupérer les données de l'API
    $response = file_get_contents($url);
    // Décodage de la réponse JSON(tableau)
    return json_decode($response, true);
}

// 2. Fonction pour récupérer les données de l'API GeoPlugin
function getGeoPluginData($ip) {
    // URL de l'API GeoPlugin 
    $url = "http://www.geoplugin.net/xml.gp?ip=$ip";
    // Récupérer les données XML de l'API
    $response = file_get_contents($url);
    // Convertir la réponse XML en un objet SimpleXML
    $xml = simplexml_load_string($response);
    // Extraire les informations de géolocalisation
    return [
        'city' => (string)$xml->geoplugin_city,
        'country' => (string)$xml->geoplugin_countryName,
        'latitude' => (string)$xml->geoplugin_latitude,
        'longitude' => (string)$xml->geoplugin_longitude
    ];
}

// 3. Fonction pour récupérer les données de l'API ipinfo.io
function getIpInfoData($ip) {
    // URL de l'API ipinfo.io 
    $url = "https://ipinfo.io/$ip/geo";
    // récupérer les données de l'API
    $response = file_get_contents($url);
    // Décodage de la réponse JSON
    $data = json_decode($response, true);
    // Extraire les informations de la réponse JSON
    return [
        'city' => $data['city'],
        'region' => $data['region'],
        'country' => $data['country'],
        'location' => $data['loc']
    ];
}


// 4. WhatIsMyIP
function getWhatIsMyIPData($apikey4, $ip4) {
    $whatismyipUrl = "https://api.whatismyip.com/ip-address-lookup.php?key=$apikey4&input=$ip4&output=xml";
    $whatismyipXml = simplexml_load_file($whatismyipUrl);

    if ($whatismyipXml !== false && isset($whatismyipXml->server_data)) {
        return [
            'city' => (string)$whatismyipXml->server_data->city ?? "Non détectée",
            'country' => (string)$whatismyipXml->server_data->country ?? "Non détecté"
        ];
    }
    return [
        'city' => 'Non détectée',
        'country' => 'Non détecté'
    ];
}


// ------------- Appels aux fonctions ----------------

// NASA APOD API : Récupération de l'image ou vidéo du jour avec ses métadonnées
$apiKey = "qai3giqRwyORdxYs61T1SQAwDHOBgKJNUIpoFbZa"; // Clé API pour NASA
$date = date("Y-m-d"); // Date actuelle pour la requête
$data = getNasaApodData($apiKey, $date); // Appel de la fonction pour récupérer les données JSON de l'API NASA APOD

// GeoPlugin API : Récupération des données de géolocalisation basées sur une adresse IP
$ip = "193.54.115.192"; // Adresse IP à interroger
$geoData = getGeoPluginData($ip); // Appel de la fonction pour récupérer les données XML de GeoPlugin
$city = $geoData['city']; // Extraction de la ville
$country = $geoData['country']; // Extraction du pays
$latitude = $geoData['latitude']; // Extraction de la latitude
$longitude = $geoData['longitude']; // Extraction de la longitude

// ipinfo.io API : Récupération des données de géolocalisation basées sur la même adresse IP
$data3 = getIpInfoData($ip); // Appel de la fonction pour récupérer les données JSON de ipinfo.io
$city3 = $data3['city']; // Extraction de la ville
$region3 = $data3['region']; // Extraction de la région
$country3 = $data3['country']; // Extraction du pays
$location3 = $data3['location']; // Extraction des coordonnées (latitude, longitude)

// WhatIsMyIP API : Récupération des données de géolocalisation pour une autre adresse IP
$ip4 = "193.54.115.235"; // Adresse IP différente pour cette API
$apikey4 = "4294b26d8d795d8e19ea1c2cc1100092"; // Clé API pour WhatIsMyIP
$whatismyipData = getWhatIsMyIPData($apikey4, $ip4); // Appel de la fonction pour récupérer les données XML de WhatIsMyIP
$city4 = $whatismyipData['city']; // Extraction de la ville
$country4 = $whatismyipData['country']; // Extraction du pays

?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="author" content="Céline"/>
    <meta name="description" content="Page tech du projet L2-Informatique S4 Développement Web" />
    <link rel="stylesheet" href="./css/styles.css" id="light-theme"/>
    <link rel="stylesheet" href="./css/alternative.css" id="dark-theme"/>
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>
    <title>Page tech - Projet ARKAM AYASH </title>
  <style>
     div {
         max-width: 600px; 
        margin: auto; 
    }

     /* Styles pour l'image */
    img {
      max-width: 300px; 
      display: block; 
      margin: 20px auto; /* Centre horizontalement, marge en haut */
      border-radius: 10px; /* Coins arrondis */
    }

    /* Styles pour la vidéo (iframe) */
    iframe {
      max-width: 300px; 
      display: block; 
      margin: 20px auto; /* Centre horizontalement, marge en haut */
    }


   </style>
</head>
<body>
<header>
     <a href="index.php" class="header-logo">
         <img src="images/logosite.png" alt="Logo-Météo France" style="height: 120px ;"/>
     </a>
    <nav>
        <ul>
            <li> <p><a href="index.php">Accueil</a></p> </li>
            <li> <p><a href="meteo.php">Meteo</a></p></li>
            <li> <p><a href="statistiques.php">Statistiques</a></p></li>
        </ul>
    </nav>
    <div id="theme-switcher" style="position: absolute; top: 20px; right: 20px;">
      <button onclick="setTheme('light')">☀️ Mode clair</button>
      <button onclick="setTheme('dark')">🌙 Mode sombre</button>
    </div>
</header>
    <h1>Prise en main des formats d’échanges JSON et XML des API Web</h1>

    <section>
      <h2>Image du jour de l'API APOD de la NASA</h2>

      <p><?php echo nl2br(htmlspecialchars($data['explanation'])); ?></p>

      <div style="text-align: center; margin-top: 20px;">
        <?php if ($data['media_type'] === 'image'): ?>
        <img src="<?php echo htmlspecialchars($data['url']); ?>" alt="<?php echo htmlspecialchars($data['title']); ?>" style="max-width: 500px;"/>
        <figcaption style="font-style: italic; margin-top: 10px;"><?php echo htmlspecialchars($data['title']); ?></figcaption>
    
        <?php elseif ($data['media_type'] === 'video'): ?>
        <iframe width="560" height="315" src="<?php echo htmlspecialchars($data['url']); ?>" allow="autoplay; encrypted-media"></iframe>
        <figcaption style="font-style: italic; margin-top: 10px;"><?php echo htmlspecialchars($data['title']); ?></figcaption>
    
        <?php else: ?>
        <!-- Image par défaut -->
        <img src="images/nasa.jpg" alt="Image par défaut" style="max-width: 500px;"/>
        <figcaption style="font-style: italic; margin-top: 10px;">Contenu indisponible – image par défaut affichée.</figcaption>
        <?php endif; ?>
      </div>
   </section>



    <section>

       <h2>Votre Localisation avec GeoPlugin </h2>
       <p>Ville :<?php echo htmlspecialchars($city); ?></p>
       <p>Pays : <?php echo htmlspecialchars($country); ?></p>
       <p>Latitude : <?php echo htmlspecialchars($latitude); ?></p>
       <p>Longitude : <?php echo htmlspecialchars($longitude); ?></p>
    </section>

    <section>
      <h2>Extraction d'informations depuis un flux JSON ou XML </h2>

      <h3>Flux JSON (ipinfo)</h3>
      <p>Ville :<?php echo htmlspecialchars($city3); ?></p>
      <p>Région : <?php echo htmlspecialchars($region3); ?></p>
      <p>Pays :<?php echo htmlspecialchars($country3); ?></p>
      <p>Localisation :<?php echo htmlspecialchars($location3); ?></p>

      <h3>Flux XML (whatismyip)</h3>
    <p>Ville : <?php echo htmlspecialchars($city4); ?></p>
    <p>Pays : <?php echo htmlspecialchars($country4); ?></p>
      
    </section>
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