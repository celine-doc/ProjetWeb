<?php


// Clé API pour l'API NASA
$apiKey = "qai3giqRwyORdxYs61T1SQAwDHOBgKJNUIpoFbZa"; 
// Date actuelle 
$date = date("Y-m-d");
// URL de l'API NASA
$url = "https://api.nasa.gov/planetary/apod?api_key=$apiKey&date=$date";
// Utilisation de file_get_contents pour récupérer les données de l'API
$response = file_get_contents($url);
// Décodage de la réponse JSON(tableau)
$data = json_decode($response, true);



// Adresse IP 
$ip ="193.54.115.192";
// URL de l'API GeoPlugin 
$url2 = "http://www.geoplugin.net/xml.gp?ip=$ip"; 
// Récupérer les données XML de l'API
$response2 = file_get_contents($url2);
// Convertir la réponse XML en un objet SimpleXML
$xml = simplexml_load_string($response2);
// Extraire les informations de géolocalisation
$city = (string)$xml->geoplugin_city;  
$country = (string)$xml->geoplugin_countryName;  
$latitude = (string)$xml->geoplugin_latitude;  
$longitude = (string)$xml->geoplugin_longitude; 


//on utilise la même IP 
$ip ="193.54.115.192";
// URL de l'API ipinfo.io 
$url3 = "https://ipinfo.io/$ip/geo";
// récupérer les données de l'API
$response3 = file_get_contents($url3);
// Décodage de la réponse JSON
$data3 = json_decode($response3, true);
 // Extraire les informations de la réponse JSON
$city3= $data3['city'];
$region3 = $data3['region'];
$country3 = $data3['country'];
$location3 = $data3['loc']; 



$ip4 ="193.54.115.235";
// Clé API de whatismyip
$apikey4 ="9131034c528a592980a8c3d9edba6357"; 
// URL de l'API 
$url4 = "https://api.whatismyip.com/ip-address-lookup.php?key=$apikey4&input=$ip4&output=xml";
// Récupérer le contenu XML de l'API
$response4 = file_get_contents($url4);
// Charger et analyser le flux XML
$xml4 = simplexml_load_string($response4);
// Extraire les informations depuis le XML
$ip4= (string)$xml4->ip;
$country4 = (string)$xml4->country;
$region4 = (string)$xml4->region;
$city4= (string)$xml4->city;
$postal_code4 = (string)$xml4->postal_code;
$isp4 = (string)$xml4->isp;
$timezone4 = (string)$xml4->timezone;
$latitude4 = (string)$xml4->latitude;
$longitude4 = (string)$xml4->longitude;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="author" content="Céline"/>
    <meta name="description" content="Page tech du projet L2-Informatique S4 Développement Web" />
    <link rel="stylesheet" href="./css/styles.css" id="light-theme"/>
    <link rel="stylesheet" href="./css/alternative.css" id="dark-theme" disabled/>
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            <img src="images/logosite.png" alt="Logo-Météo France" />
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
        <img src="<?php echo htmlspecialchars($data['url']); ?>" alt="<?php echo htmlspecialchars($data['title']); ?>" style="max-width: 500px;">
        <figcaption style="font-style: italic; margin-top: 10px;"><?php echo htmlspecialchars($data['title']); ?></figcaption>
    
        <?php elseif ($data['media_type'] === 'video'): ?>
        <iframe width="560" height="315" src="<?php echo htmlspecialchars($data['url']); ?>" allow="autoplay; encrypted-media"></iframe>
        <figcaption style="font-style: italic; margin-top: 10px;"><?php echo htmlspecialchars($data['title']); ?></figcaption>
    
        <?php else: ?>
        <!-- Image par défaut -->
        <img src="images/nasa.jpg" alt="Image par défaut" style="max-width: 500px;">
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
      <p>Pays :<?php echo htmlspecialchars($country4); ?></p>
      <p>Région : <?php echo htmlspecialchars($region4); ?></p>
      <p>Ville : <?php echo htmlspecialchars($city4); ?></p>
      <p>Code postal :<?php echo htmlspecialchars($postal_code4); ?></p>
      <p>Fournisseur (ISP) :<?php echo htmlspecialchars($isp4); ?></p>
      <p>Fuseau horaire :<?php echo htmlspecialchars($timezone4); ?></p>
      <p>Latitude :<?php echo htmlspecialchars($latitude4); ?></p>
      <p>Longitude :<?php echo htmlspecialchars($longitude4); ?></p>
      
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
