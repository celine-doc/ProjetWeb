<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Arkam Céline, Ayash Waseel" />
    <meta name="description" content="Plan du site de prévisions météorologiques pour la France" />
    <link rel="stylesheet" href="./css/styles.css" id="light-theme"/>
    <link rel="stylesheet" href="./css/alternative.css" id="dark-theme" />
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg" />
    <title>Plan du site - Météo France</title>
</head>

<body>
    <header>
      <a href="index.php" class="header-logo">
            <img src="images/logosite.png" alt="Logo-Météo France" style="height: 120px ;"/>
      </a>
        <nav>
            <ul>
                <li><p><a href="index.php">Accueil</a></p></li>
                <li><p><a href="meteo.php">Météo</a></p></li>
                <li><p><a href="statistiques.php">Statistiques</a></p></li>
            </ul>
        </nav>
        <div id="theme-switcher" style="position: absolute; top: 20px; right: 20px;">
      <button onclick="setTheme('light')">☀️ Mode clair</button>
      <button onclick="setTheme('dark')">🌙 Mode sombre</button>
    </div>
    </header>

    <main>
    <h1>Plan du site</h1>

    <section class="plan-lien" style="color:#cfd8ff;" >
        <h2>Sommaire</h2>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="meteo.php">Météo</a></li>
            <li><a href="statistiques.php">Statistiques</a></li>
            <li><a href="nous.php">À propos de nous</a></li>
            <li><a href="tech.php">Page tech</a></li>
            <li><a href="plan.php">Plan du site</a></li>
        </ul>
    </section>
</main>



    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h4>Aide ?</h4>
                <p>Réalisé par Céline ARKAM - Waseel AYASH ®</p>
                <a href="nous.php">À propos de nous</a>
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