<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Arkam Céline, Ayash Waseel" />
    <meta name="description" content="À propos des créateurs du site de prévisions météorologiques pour la France" />
    <link rel="stylesheet" href="./css/styles.css" id="light-theme"/>
    <link rel="stylesheet" href="./css/alternative.css" id="dark-theme" disabled/>
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>
    <title>À propos de nous - Météo France</title>
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

    <main>
        <h1>Bienvenue sur notre site de prévisions météorologiques. Merci pour votre visite !</h1>

        <section>
            <h2>À propos de nous</h2>
            <p>Nous sommes Arkam Céline et Ayash Waseel, deux étudiants en L2 Informatique, passionnés par les sciences et engagés dans la création de solutions permettant d’offrir des informations météorologiques précises et accessibles à tous.
                 Dans le cadre du projet du module Développement Web, encadré par Monsieur Lemaire, nous avons à cœur de mettre nos compétences en développement web au service de la diffusion de données météo fiables et compréhensibles pour tous.
                Ensemble, nous formons une équipe enthousiaste et déterminée, prête à relever ce défi et à concevoir une solution innovante pour améliorer l’accès aux prévisions météorologiques.          </p>
        </section>

        <section>
            <h2>Avantages</h2>
            <ul>
                <li>Consultation facile des prévisions météo pour plusieurs jours</li>
                <li>Informations détaillées sur la température, l'humidité et la pression atmosphérique</li>
                <li>Affichage des prévisions heure par heure pour les jours à venir</li>
                <li>Carte interactive des conditions météorologiques en temps réel</li>
            </ul>
        </section>

        <section>
    <h2>Contact & Informations</h2>
    <p>Si vous avez des questions ou des suggestions, n'hésitez pas à nous contacter :</p>
    
    <ul>
        <li>
            <strong>Céline ARKAM :</strong>
            📧 <a href="mailto:celine.arkam@etu.cyu.fr">celine.arkam@etu.cyu.fr</a>
        </li>
        <li>
            <strong>Waseel AYASH:</strong>
            📧 <a href="mailto:waseel.ayash@etu.cyu.fr">waseel.ayash@etu.cyu.fr</a>
        </li>
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